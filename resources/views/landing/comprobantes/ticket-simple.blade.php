<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket - Compra #{{ $compra->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: white;
            padding: 0;
            margin: 0;
            width: 80mm;
        }
        
        .ticket-container {
            width: 80mm;
            padding: 8mm;
            background-color: white;
            text-align: center;
            font-size: 10px;
            line-height: 1.4;
        }
        
        /* Icon */
        .success-icon {
            width: 30mm;
            height: 30mm;
            margin: 0 auto 5mm;
            background-color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-icon svg {
            width: 20mm;
            height: 20mm;
            stroke: white;
        }
        
        /* Title and Subtitle */
        .header-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3mm;
            line-height: 1.3;
        }
        
        .header-subtitle {
            font-size: 9px;
            color: #4b5563;
            margin-bottom: 6mm;
            line-height: 1.3;
        }
        
        .separator {
            border-bottom: 1px solid #e5e7eb;
            margin: 4mm 0;
        }
        
        .separator-bold {
            border-bottom: 2px solid #000;
            margin: 4mm 0;
        }
        
        /* Section Title */
        .section-title {
            font-weight: 600;
            font-size: 11px;
            color: #111827;
            margin-top: 4mm;
            margin-bottom: 3mm;
            text-align: left;
        }
        
        /* Details */
        .detail-item {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 2mm;
            text-align: left;
        }
        
        .detail-label {
            font-weight: 600;
            color: #4b5563;
            flex: 0 0 50%;
        }
        
        .detail-value {
            flex: 1;
            text-align: right;
            color: #111827;
            font-weight: 500;
        }
        
        .detail-value.green {
            color: #10b981;
            font-weight: 700;
        }
        
        .detail-value.purple {
            color: #a855f7;
        }
        
        .detail-value.large {
            font-size: 12px;
        }
        
        /* Boletos Grid */
        .boletos-title {
            font-weight: 600;
            font-size: 11px;
            color: #111827;
            margin-bottom: 2mm;
            text-align: left;
        }
        
        .boletos-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 2mm;
            justify-content: flex-start;
            margin-bottom: 4mm;
        }
        
        .boleto {
            width: 9mm;
            height: 9mm;
            background-color: #3b82f6;
            color: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 8px;
        }
        
        /* Next Steps */
        .next-steps {
            background-color: #eff6ff;
            border: 1px solid #93c5fd;
            border-radius: 4px;
            padding: 3mm;
            margin-bottom: 4mm;
            text-align: left;
        }
        
        .next-steps-title {
            font-weight: 600;
            font-size: 10px;
            color: #1e3a8a;
            margin-bottom: 2mm;
        }
        
        .next-steps ol {
            list-style: decimal;
            padding-left: 5mm;
            margin: 0;
        }
        
        .next-steps ol li {
            font-size: 9px;
            color: #1e40af;
            margin-bottom: 1mm;
            line-height: 1.3;
        }
        
        /* Footer */
        .footer {
            margin-top: 4mm;
            font-size: 9px;
            color: #4b5563;
            text-align: center;
        }
        
        .footer p {
            margin-bottom: 1mm;
            line-height: 1.3;
        }
        
        .footer-link {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
        }
        
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
            
            .ticket-container {
                width: 100%;
                padding: 8mm;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Success Icon -->
        <div class="success-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Title and Subtitle -->
        <div class="header-title">¡Compra Realizada Exitosamente!</div>
        <div class="header-subtitle">Gracias por la compra. Hemos registrado tu información y tus boletos.</div>

        <div class="separator"></div>

        <!-- Detalles de la Compra -->
        <div class="section-title">Detalles de tu Compra</div>
        
        <div class="detail-item">
            <div class="detail-label">ID de Compra</div>
            <div class="detail-value">#{{ $compra->id }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Rifa</div>
            <div class="detail-value">{{ $compra->rifa->name }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Nombre</div>
            <div class="detail-value">{{ $compra->cliente }} {{ $compra->apellido }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Teléfono</div>
            <div class="detail-value">{{ $compra->telefono }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Cantidad de Boletos</div>
            <div class="detail-value">{{ $compra->boletos()->count() }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Monto Total</div>
            <div class="detail-value green large">${{ number_format($compra->total, 2) }}</div>
        </div>

        @if($compra->paymentMethod)
        <div class="detail-item">
            <div class="detail-label">Método de Pago</div>
            <div class="detail-value purple">{{ $compra->paymentMethod->nombre }}</div>
        </div>
        @endif

        <div class="separator"></div>

        <!-- Boletos Asignados -->
        <div class="boletos-title">Boletos Asignados</div>
        <div class="boletos-grid">
            @foreach($compra->boletos as $boleto)
                <div class="boleto">{{ $boleto->numero }}</div>
            @endforeach
        </div>

        <!-- Próximos Pasos -->
        <div class="next-steps">
            <div class="next-steps-title">Próximos Pasos</div>
            <ol>
                <li>Completa el pago a través del método que seleccionaste</li>
                <li>Recibirás una confirmación por email</li>
                <li>Tus boletos serán finalizados automáticamente al confirmar el pago</li>
                <li>Participarás automáticamente en el sorteo</li>
            </ol>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>¿Tienes dudas? Contáctanos por WhatsApp</p>
            <p><a href="https://wa.me/5551981296129" class="footer-link">+55 51 98129-6129</a></p>
        </div>
    </div>
</body>
</html>
