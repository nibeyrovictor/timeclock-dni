<?php
// Incluye la lógica de autenticación y la configuración de la base de datos aquí
session_start();

include 'dbcon.php';

// Verifica si el usuario tiene permisos de administrador
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: admin_login.php'); // Redirige al inicio de sesión si no es un administrador
    exit();
}
  

// Aquí puedes agregar lógica adicional para la administración
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <!-- Agrega la referencia a Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Agrega el botón de cierre de sesión en tu HTML -->
<div class="container">
    <h1 class="mt-5">Panel de Administrador</h1>
    <div id="menu">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">INICIO</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="crear_usuario.php">Crear Usuario</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_editar_usuario.php">Editar Usuario</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="informe_tablas.php">Ver Informe en Tablas</a>
            </li>
            <!-- Agrega un botón de cierre de sesión -->
            <li class="nav-item">
                <a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</div>

<!-- Agrega la referencia a Bootstrap 5 JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
