<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style type="text/css">
        body {
            font-family: 'Verdana';
            font-size: 15px;
        }
        h1 {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Recordatorio para {{ $nombre }} de {{ $sucursal }}</h1>
    <p>Este es un recordatorio de que tienes turno para el {{ $fecha_hora }}. Por favor, no responder este mensaje.</p>
</body>
</html>
