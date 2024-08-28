<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Registrar Usuarios</a></li>
                <li><a href="{{ route('admin.profile') }}">Lista de Profesionales</a></li>
                <li><a href="#">Crear Sucursal</a></li>
                <li><a href="#">Dar de Alta Usuario</a></li>
                <li><a href="#">Dar de Baja Usuario</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, Administrador</h2>
        <p>Aquí puedes gestionar las funciones administrativas de la plataforma.</p>
        <!-- Añade más contenido aquí según tus necesidades -->
    </main>
</body>
</html>
