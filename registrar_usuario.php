<?php
include 'dbcon.php';
// Inicializa la variable $mensaje
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
    <h1 class="text-center">Registro de usuarios</h1>
    <div id="menu">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">INICIO</a>
            </li>
        </ul>
    </div>
</div>

<div class="container">
    <h1><?php echo $mensaje; ?></h1>
    <form method="post" action="registrar_usuario.php">
        <input type="hidden" name="crear_usuario" value="true">
        <div class="mb-3">
            <label for="dni" class="form-label">DNI:</label>
            <input type="number" name="dni" class="form-control" value="<?php echo $dni; ?>" required>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Clave:</label>
            <input type="password" name="clave" class="form-control" value="<?php echo $clave; ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido:</label>
            <input type="text" name="apellido" class="form-control" value="<?php echo $apellido; ?>" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>" required>
        </div>
        <div class="mb-3">
            <label for="legajo" class="form-label">Legajo:</label>
            <input type="text" name="legajo" class="form-control" value="<?php echo $legajo; ?>" required>
        </div>
        <input type="hidden" name="rol" value="usuario">
        <input type="hidden" name="modo" value="crear">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

<!-- Agrega la referencia a Bootstrap 5 JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
