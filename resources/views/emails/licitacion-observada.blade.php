<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licitación Observada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
        }
        .alert-box h2 {
            color: #92400e;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .alert-box p {
            color: #78350f;
            margin: 0;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h3 {
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .info-item {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 140px;
        }
        .info-value {
            color: #111827;
        }
        .observacion-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .observacion-box h4 {
            color: #c2410c;
            margin: 0 0 10px 0;
        }
        .observacion-box p {
            color: #7c2d12;
            margin: 0;
            white-space: pre-wrap;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">⚠️</div>
            <h1>Licitación Observada</h1>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <h2>Atención: Se requieren correcciones</h2>
                <p>Tu licitación ha sido revisada por RyCE y requiere modificaciones antes de poder ser publicada.</p>
            </div>
            
            <div class="info-section">
                <h3>Datos de la Licitación</h3>
                <div class="info-item">
                    <span class="info-label">Código:</span>
                    <span class="info-value">{{ $licitacion->codigo_licitacion }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Título:</span>
                    <span class="info-value">{{ $licitacion->titulo }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Revisado por:</span>
                    <span class="info-value">{{ $revisorNombre }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            
            <div class="observacion-box">
                <h4>📝 Observaciones del Revisor</h4>
                <p>{{ $observacion }}</p>
            </div>
            
            <p>Por favor, accede a la plataforma y realiza las correcciones indicadas para que tu licitación pueda ser aprobada y publicada.</p>
            
            <center>
                <a href="{{ url('/principal/licitaciones/' . $licitacion->id) }}" class="action-button">
                    Ver Licitación
                </a>
            </center>
        </div>
        
        <div class="footer">
            <p>Este es un mensaje automático del sistema de Licitaciones RyCE.</p>
            <p>Por favor, no responda a este correo.</p>
        </div>
    </div>
</body>
</html>
