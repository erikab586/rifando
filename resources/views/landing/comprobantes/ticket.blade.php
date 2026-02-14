<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Compra - {{ $compra->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            width: 100%;
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: white;
            padding: 8px;
        }
        
        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 8px;
        }
        
        .max-w-2xl {
            width: 100%;
            max-width: 100%;
        }
        
        .bg-white {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 12px;
            text-align: center;
        }
        
        .mb-6 {
            margin-bottom: 10px;
        }
        
        .mb-4 {
            margin-bottom: 6px;
        }
        
        .mb-8 {
            margin-bottom: 10px;
        }
        
        .mb-3 {
            margin-bottom: 6px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        /* SVG Styles */
        .w-20 {
            width: 45px;
            height: 45px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #10b981;
            border-radius: 50%;
        }
        
        .text-green-500 {
            color: white;
        }
        
        .text-green-500 path {
            stroke: white;
        }
        
        /* Heading Styles */
        h1 {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin: 6px 0;
        }
        
        h2 {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        
        h3 {
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
            font-size: 12px;
        }
        
        p {
            margin: 0;
            padding: 0;
        }
        
        /* Main content styles */
        .text-gray-600 {
            color: #4b5563;
        }
        
        .text-lg {
            font-size: 13px;
        }
        
        .text-sm {
            font-size: 10px;
        }
        
        .font-bold {
            font-weight: 600;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        /* Background colors */
        .bg-gray-50 {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: left;
        }
        
        .bg-blue-50 {
            background-color: #eff6ff;
            border: 1px solid #93c5fd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: left;
        }
        
        .border-blue-200 {
            border: 1px solid #93c5fd;
        }
        
        .border-t {
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        /* Grid styles */
        .grid {
            display: grid;
            gap: 8px;
        }
        
        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .grid-cols-5 {
            grid-template-columns: repeat(8, 1fr);
        }
        
        .gap-2 {
            gap: 2px;
        }
        
        .gap-4 {
            gap: 8px;
        }
        
        /* Detail items - white background boxes */
        .grid-cols-2 > div {
            background-color: white;
            padding: 8px;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .grid-cols-2 > div p {
            display: inline;
            margin-right: 2px;
        }
        
        .text-green-600 {
            color: #10b981;
            font-weight: 600;
        }
        
        .text-purple-600 {
            color: #a855f7;
            font-weight: 600;
        }
        
        .text-blue-800 {
            color: #1e40af;
        }
        
        .text-blue-900 {
            color: #1e3a8a;
        }
        
        .bg-blue-500 {
            background-color: #3b82f6;
            color: white;
            border-radius: 6px;
            padding: 2px;
            text-align: center;
            font-weight: bold;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
        }
        
        .aspect-square {
            aspect-ratio: 1;
        }
        
        /* List styles */
        ol {
            list-style: decimal;
            padding-left: 16px;
            color: #1e3a8a;
            font-size: 10px;
            line-height: 1.4;
        }
        
        ol li {
            margin-bottom: 3px;
        }
        
        .space-y-2 > li {
            margin-bottom: 3px;
        }
        
        a {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
        }
        
        .text-xs {
            font-size: 9px;
        }
        
        .rounded-lg {
            border-radius: 8px;
        }
        
        /* Badge Primary Style */
        .badge-primary {
            background-color: #3b82f6;
            color: white;
            border-radius: 4px;
            padding: 3px 6px;
            font-size: 9px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Footer styles */
        .footer-content {
            margin-top: 10px;
            text-align: center;
            color: #4b5563;
            font-size: 10px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-link {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
        }
        
        @media print {
            body, html {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .container, .max-w-2xl {
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .bg-white {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">¡Compra Realizada Exitosamente!</h1>
            
                <p class="text-gray-600 text-lg mb-8">
                    Gracias por tu compra. Hemos registrado tu información y tus boletos.
                </p>

                <!-- Detalles de la Compra -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles de tu Compra</h2>
                
                    <div class="grid grid-cols-2 gap-4 ">
                        <div>
                            <p class="font-bold text-lg">ID de compra: #{{ $compra->id }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Rifa: {{ $compra->rifa->name }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Nombre: {{ $compra->cliente }} {{ $compra->apellido }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Teléfono: {{ $compra->telefono }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Cantidad de Boletos:  {{ $compra->boletos()->count() }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-green-600">Monto Total: ${{ number_format($compra->total, 2) }}</p>
                        </div>
                        @if($compra->paymentMethod)
                        <div>
                            <p class="text-gray-600 text-sm">Método de Pago</p>
                            <p class="font-bold text-lg text-purple-600">{{ $compra->paymentMethod->nombre }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="border-t">
                        <h3>Boletos Asignados</h3>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($compra->boletos as $boleto)
                                <span class="badge-primary">{{ $boleto->numero }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                </div>

                <!-- Próximos Pasos -->
                <div class="bg-blue-50">
                    <h2 class="text-blue-900">Próximos Pasos</h2>
                    <ol class="text-blue-800 space-y-2">
                        <li>Completa el pago a través del método que seleccionaste</li>
                        <li>Recibirás una confirmación por email</li>
                        <li>Tus boletos serán finalizados automáticamente al confirmar el pago</li>
                        <li>Participarás automáticamente en el sorteo</li>
                    </ol>
                </div>

                <!-- Footer -->
                <div class="footer-content">
                    <p>¿Tienes dudas? Contáctanos por WhatsApp</p>
                    <a href="https://wa.me/5551981296129" class="footer-link">+55 51 98129-6129</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
