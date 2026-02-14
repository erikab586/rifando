import { test, expect } from '@playwright/test';

const BASE_URL = 'http://localhost:8000';
const ADMIN_EMAIL = 'admin@example.com';
const ADMIN_PASSWORD = 'password';
const CLIENT_EMAIL = 'cliente@example.com';
const CLIENT_PASSWORD = 'password';

test.describe('Sistema de Rifas - Test Suite Completo', () => {
  
  // ========================
  // TESTS LANDING PAGE
  // ========================
  
  test('Landing Page - Debe mostrar lista de rifas activas', async ({ page }) => {
    await page.goto(`${BASE_URL}/`);
    
    // Verificar que la página carga
    await expect(page).toHaveTitle('Rifas');
    
    // Verificar que hay un grid de rifas
    const rifas = await page.locator('[data-testid="rifa-card"]').count();
    expect(rifas).toBeGreaterThan(0);
  });

  test('Landing Page - Debe mostrar detalles de una rifa', async ({ page }) => {
    // Ir a la landing page
    await page.goto(`${BASE_URL}/`);
    
    // Hacer clic en la primera rifa
    await page.locator('[data-testid="rifa-card"]').first().click();
    
    // Verificar que estamos en la página de detalle
    const title = await page.locator('h1').first().textContent();
    expect(title).toBeTruthy();
    
    // Verificar que hay grid de boletos
    const boletos = await page.locator('[data-testid="boleto-item"]').count();
    expect(boletos).toBeGreaterThan(0);
  });

  test('Landing Page - Seleccionar boletos', async ({ page }) => {
    await page.goto(`${BASE_URL}/`);
    
    // Ir a detalle de rifa
    await page.locator('[data-testid="rifa-card"]').first().click();
    
    // Seleccionar 3 boletos
    for (let i = 0; i < 3; i++) {
      await page.locator('[data-testid="boleto-item"]').nth(i).click();
    }
    
    // Verificar que el carrito se actualizó
    const cartCount = await page.locator('[data-testid="cart-count"]').textContent();
    expect(cartCount).toContain('3');
    
    // Verificar total
    const total = await page.locator('[data-testid="cart-total"]').textContent();
    expect(total).toMatch(/\$[0-9]+/);
  });

  test('Landing Page - Aplicar cupón de descuento', async ({ page }) => {
    await page.goto(`${BASE_URL}/`);
    await page.locator('[data-testid="rifa-card"]').first().click();
    
    // Seleccionar boleto
    await page.locator('[data-testid="boleto-item"]').first().click();
    
    // Ir a comprar
    await page.locator('button:has-text("Ir a Comprar")').click();
    
    // Esperar formulario
    await page.waitForLoadState('networkidle');
    
    // Ingresar cupón
    await page.locator('input[name="cupon_codigo"]').fill('DESCUENTO10');
    await page.locator('button:has-text("Aplicar Cupón")').click();
    
    // Verificar que el descuento se aplicó
    const descuento = await page.locator('[data-testid="descuento-monto"]').textContent();
    expect(descuento).toMatch(/\$[0-9]+/);
  });

  // ========================
  // TESTS COMPRA
  // ========================

  test('Compra - Crear compra exitosamente', async ({ page }) => {
    await page.goto(`${BASE_URL}/`);
    await page.locator('[data-testid="rifa-card"]').first().click();
    
    // Seleccionar boleto
    await page.locator('[data-testid="boleto-item"]').first().click();
    await page.locator('button:has-text("Ir a Comprar")').click();
    
    // Esperar formulario
    await page.waitForLoadState('networkidle');
    
    // Llenar formulario
    await page.locator('input[name="cliente"]').fill('Juan Pérez');
    await page.locator('input[name="apellido"]').fill('García');
    await page.locator('input[name="cedula"]').fill('12345678');
    await page.locator('input[name="telefono"]').fill('584121234567');
    await page.locator('input[name="email"]').fill('juan@test.com');
    
    // Enviar formulario
    await page.locator('form').locator('button[type="submit"]').click();
    
    // Verificar página de éxito
    await page.waitForURL(/\/compra\/\d+\/exitosa/);
    const success = await page.locator('[data-testid="success-message"]').isVisible();
    expect(success).toBeTruthy();
  });

  test('Compra - Validar campos requeridos', async ({ page }) => {
    await page.goto(`${BASE_URL}/`);
    await page.locator('[data-testid="rifa-card"]').first().click();
    await page.locator('[data-testid="boleto-item"]').first().click();
    await page.locator('button:has-text("Ir a Comprar")').click();
    
    // Intentar enviar sin llenar formulario
    await page.locator('form').locator('button[type="submit"]').click();
    
    // Verificar errores de validación
    const errors = await page.locator('[data-testid="error-message"]').count();
    expect(errors).toBeGreaterThan(0);
  });

  test('Compra - Email inválido', async ({ page }) => {
    await page.goto(`${BASE_URL}/`);
    await page.locator('[data-testid="rifa-card"]').first().click();
    await page.locator('[data-testid="boleto-item"]').first().click();
    await page.locator('button:has-text("Ir a Comprar")').click();
    
    // Llenar con email inválido
    await page.locator('input[name="cliente"]').fill('Juan');
    await page.locator('input[name="apellido"]').fill('Pérez');
    await page.locator('input[name="cedula"]').fill('12345678');
    await page.locator('input[name="telefono"]').fill('584121234567');
    await page.locator('input[name="email"]').fill('email-invalido');
    
    // Enviar
    await page.locator('form').locator('button[type="submit"]').click();
    
    // Verificar error
    const emailError = await page.locator('text=correo válido').isVisible();
    expect(emailError).toBeTruthy();
  });

  // ========================
  // TESTS ADMIN - AUTENTICACIÓN
  // ========================

  test('Admin - Login exitoso', async ({ page }) => {
    await page.goto(`${BASE_URL}/login`);
    
    // Llenar credenciales
    await page.locator('input[type="email"]').fill(ADMIN_EMAIL);
    await page.locator('input[type="password"]').fill(ADMIN_PASSWORD);
    await page.locator('button[type="submit"]').click();
    
    // Verificar redirección a dashboard
    await page.waitForURL(/\/admin/);
    const title = await page.locator('h1').textContent();
    expect(title).toContain('Dashboard');
  });

  test('Admin - No acceso sin autenticación', async ({ page }) => {
    // Intentar acceder a admin sin estar autenticado
    await page.goto(`${BASE_URL}/admin`);
    
    // Debe redirigir a login
    await expect(page).toHaveURL(/\/login/);
  });

  // ========================
  // TESTS ADMIN - DASHBOARD
  // ========================

  test('Admin - Dashboard muestra estadísticas', async ({ page }) => {
    // Login
    await page.goto(`${BASE_URL}/login`);
    await page.locator('input[type="email"]').fill(ADMIN_EMAIL);
    await page.locator('input[type="password"]').fill(ADMIN_PASSWORD);
    await page.locator('button[type="submit"]').click();
    
    // Esperar dashboard
    await page.waitForURL(/\/admin$/);
    
    // Verificar tarjetas de estadísticas
    const cards = await page.locator('[data-testid="stat-card"]').count();
    expect(cards).toBe(4); // Total rifas, compras, ingresos, boletos vendidos
    
    // Verificar tabla de compras recientes
    const table = await page.locator('[data-testid="recent-purchases-table"]').isVisible();
    expect(table).toBeTruthy();
  });

  // ========================
  // TESTS ADMIN - RIFAS CRUD
  // ========================

  test('Admin - Crear rifa', async ({ page }) => {
    // Login
    await loginAsAdmin(page);
    
    // Ir a rifas
    await page.goto(`${BASE_URL}/admin/rifas`);
    
    // Click en crear
    await page.locator('button:has-text("Crear Rifa")').click();
    await page.waitForURL(/\/admin\/rifas\/create/);
    
    // Llenar formulario
    await page.locator('input[name="name"]').fill('Rifa Test Nueva');
    await page.locator('input[name="slug"]').fill('rifa-test-nueva');
    await page.locator('textarea[name="description"]').fill('Descripción de test');
    await page.locator('input[name="num_boletos"]').fill('100');
    await page.locator('input[name="amount"]').fill('10');
    await page.locator('input[name="start"]').fill('2024-02-01');
    await page.locator('input[name="end"]').fill('2024-03-01');
    await page.locator('input[name="status"]').check();
    
    // Subir imagen
    await page.locator('input[type="file"]').setInputFiles('test-image.jpg');
    
    // Enviar
    await page.locator('form').locator('button[type="submit"]').click();
    
    // Verificar éxito
    await page.waitForURL(/\/admin\/rifas/);
    const success = await page.locator('text=Rifa creada exitosamente').isVisible();
    expect(success).toBeTruthy();
  });

  test('Admin - Editar rifa', async ({ page }) => {
    // Login
    await loginAsAdmin(page);
    
    // Ir a rifas
    await page.goto(`${BASE_URL}/admin/rifas`);
    
    // Click en editar primera rifa
    await page.locator('[data-testid="edit-rifa-btn"]').first().click();
    
    // Esperar formulario
    await page.waitForLoadState('networkidle');
    
    // Modificar nombre
    await page.locator('input[name="name"]').fill('Nombre Actualizado');
    
    // Enviar
    await page.locator('form').locator('button[type="submit"]').click();
    
    // Verificar éxito
    const success = await page.locator('text=actualizada exitosamente').isVisible();
    expect(success).toBeTruthy();
  });

  test('Admin - Ver detalle rifa', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto(`${BASE_URL}/admin/rifas`);
    
    // Click en ver
    await page.locator('[data-testid="view-rifa-btn"]').first().click();
    
    // Verificar detalles
    const stats = await page.locator('[data-testid="rifa-stat"]').count();
    expect(stats).toBeGreaterThan(0);
    
    // Verificar grid de boletos
    const boletos = await page.locator('[data-testid="boleto-status-item"]').count();
    expect(boletos).toBeGreaterThan(0);
  });

  // ========================
  // TESTS ADMIN - COMPRAS
  // ========================

  test('Admin - Listar compras', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto(`${BASE_URL}/admin/compras`);
    
    // Verificar tabla
    const rows = await page.locator('[data-testid="compra-row"]').count();
    expect(rows).toBeGreaterThanOrEqual(0);
  });

  test('Admin - Ver detalle compra', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto(`${BASE_URL}/admin/compras`);
    
    // Click en primera compra
    const firstCompra = await page.locator('[data-testid="compra-row"]').first();
    if (await firstCompra.isVisible()) {
      await firstCompra.click();
      
      // Verificar información
      const clientInfo = await page.locator('[data-testid="client-info"]').isVisible();
      expect(clientInfo).toBeTruthy();
      
      const boletosGrid = await page.locator('[data-testid="compra-boletos"]').isVisible();
      expect(boletosGrid).toBeTruthy();
    }
  });

  test('Admin - Confirmar pago', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto(`${BASE_URL}/admin/compras`);
    
    // Encontrar compra pendiente
    const rows = await page.locator('[data-testid="compra-row"]:has-text("Pendiente")').count();
    if (rows > 0) {
      await page.locator('[data-testid="compra-row"]:has-text("Pendiente")').first().click();
      
      // Click en confirmar pago
      await page.locator('button:has-text("Confirmar Pago")').click();
      
      // Confirmar
      await page.locator('button:has-text("Sí, confirmar")').click();
      
      // Verificar éxito
      const success = await page.locator('text=confirmado exitosamente').isVisible();
      expect(success).toBeTruthy();
      
      // Verificar estado cambió a Pagado
      const estado = await page.locator('[data-testid="compra-estado"]').textContent();
      expect(estado).toContain('Pagado');
    }
  });

  // ========================
  // TESTS API
  // ========================

  test('API - Verificar boleto disponible', async ({ request }) => {
    const response = await request.post(`${BASE_URL}/api/verificar-boleto`, {
      data: { boleto_id: 1 }
    });
    
    expect(response.status()).toBe(200);
    const json = await response.json();
    expect(json).toHaveProperty('disponible');
  });

  test('API - Crear compra vía POST', async ({ request }) => {
    const response = await request.post(`${BASE_URL}/compra/crear`, {
      data: {
        rifa_id: 1,
        cliente: 'Test User',
        apellido: 'Test',
        cedula: '12345678',
        telefono: '584121234567',
        email: 'test@example.com',
        boletos: [1, 2]
      }
    });
    
    expect([200, 201]).toContain(response.status());
  });

  // ========================
  // FUNCIONES AUXILIARES
  // ========================
});

async function loginAsAdmin(page) {
  await page.goto(`${BASE_URL}/login`);
  await page.locator('input[type="email"]').fill(ADMIN_EMAIL);
  await page.locator('input[type="password"]').fill(ADMIN_PASSWORD);
  await page.locator('button[type="submit"]').click();
  await page.waitForURL(/\/admin/);
}

async function loginAsClient(page) {
  await page.goto(`${BASE_URL}/login`);
  await page.locator('input[type="email"]').fill(CLIENT_EMAIL);
  await page.locator('input[type="password"]').fill(CLIENT_PASSWORD);
  await page.locator('button[type="submit"]').click();
  await page.waitForURL(/\/dashboard/);
}
