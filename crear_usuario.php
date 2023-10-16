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
$id_usuario = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica si se envió un ID de usuario para edición
    if (isset($_POST["id_usuario"])) {
        $id_usuario = $_POST["id_usuario"];
    }

    // Obtén y valida los datos del formulario
    $dni = $_POST["dni"];
    $clave = $_POST["clave"];
    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $legajo = $_POST["legajo"];
    $rol = $_POST["rol"];

    if ($id_usuario === null) {
        // Estamos creando un nuevo usuario
        $sql = "INSERT INTO usuarios (dni, clave, apellido, nombre, legajo, rol) VALUES ('$dni', '$clave', '$apellido', '$nombre', '$legajo', '$rol')";

        if ($mysqli->query($sql)) {
            $mensaje = "Usuario creado exitosamente.";
        } else {
            $mensaje = "Error al crear el usuario: " . $mysqli->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear o Editar Usuario</title>
</head>
<body>
  <h1>Crear o Editar Usuario</h1>
  <p><?php echo $mensaje; ?></p>
  <form method="post" action="crear_usuario.php">
    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
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

    <input type="submit" value="Guardar">
  </form>
</body>
</html>
