<?php
// Incluir la conexión a la base de datos y otras configuraciones necesarias
include 'dbcon.php';

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
</head>
<body>
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
</body>
</html>
