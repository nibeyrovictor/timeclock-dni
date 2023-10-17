<?php
// Incluir la conexión a la base de datos y otras configuraciones necesarias
session_start();
include 'dbcon.php';
// Verifica si el usuario tiene permisos de administrador
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: index.php'); // Redirige al inicio de sesión si no es un administrador
    exit();
}
// Variables para almacenar mensajes de error o éxito
$mensaje = "";

// Variables para los campos del formulario
$dni = "";
$clave = "";
$apellido = "";
$nombre = "";
$legajo = "";
$rol = "";

// Verifica si se envió un modo (crear o editar)
$modo = isset($_POST["modo"]) ? $_POST["modo"] : null;

// Obtén y valida los datos del formulario
if (isset($_POST["dni"])) {
    $dni = $_POST["dni"];
}
if (isset($_POST["clave"])) {
    $clave = $_POST["clave"];
}
if (isset($_POST["apellido"])) {
    $apellido = $_POST["apellido"];
}
if (isset($_POST["nombre"])) {
    $nombre = $_POST["nombre"];
}
if (isset($_POST["legajo"])) {
    $legajo = $_POST["legajo"];
}
if (isset($_POST["rol"])) {
    $rol = $_POST["rol"];
}

// Verifica si el formulario se envió y los campos no están vacíos
if (isset($_POST["crear_usuario"])) {
    if (empty($dni) || empty($clave) || empty($apellido) || empty($nombre) || empty($legajo)) {
        $mensaje = "Debe completar todos los campos.";
    } else {
        // Verifica si el usuario ya existe
        $sql = "SELECT dni FROM usuarios WHERE dni='$dni'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $mensaje = "El usuario con DNI $dni ya existe.";
        } else {
            // Estamos creando un nuevo usuario
            $sql = "INSERT INTO usuarios (dni, clave, apellido, nombre, legajo, rol) VALUES ('$dni', '$clave', '$apellido', '$nombre', '$legajo', '$rol')";

            if ($mysqli->query($sql)) {
                $mensaje = "Usuario creado exitosamente.";
            } else {
                $mensaje = "Error al crear el usuario: " . $mysqli->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Usuario</title>
    <!-- Agrega la referencia a Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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

<h1>Crear Usuario</h1>
  <p><?php echo $mensaje; ?></p>
  <form method="post" action="crear_usuario.php">
    <input type="hidden" name="crear_usuario" value="true">
    <label for="dni">DNI:</label>
    <input type="text" name="dni" value="<?php echo $dni; ?>" required>
    <br>

    <label for="clave">Clave:</label>
    <input type="password" name="clave" value="<?php echo $clave; ?>" required>
    <br>

    <label for="apellido">Apellido:</label>
    <input type="text" name="apellido" value="<?php echo $apellido; ?>" required>
    <br>

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>
    <br>

    <label for="legajo">Legajo:</label>
    <input type="text" name="legajo" value="<?php echo $legajo; ?>" required>
    <br>

    <label for="rol">Rol:</label>
    <select name="rol">
      <option value="admin" <?php if ($rol === "admin") echo "selected"; ?>>Admin</option>
      <option value="usuario" <?php if ($rol === "usuario") echo "selected"; ?>>Usuario</option>
    </select>
    <br>

    <input type="hidden" name="modo" value="crear">
    <input type="submit" value="Guardar">
  </form>
  
<!-- Agrega la referencia a Bootstrap 5 JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
