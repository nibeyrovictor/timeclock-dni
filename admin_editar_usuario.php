<?php
// Incluir la conexión a la base de datos y otras configuraciones necesarias
include 'dbcon.php';

// Variables para los campos del formulario
$dni = "";
$clave = "";
$apellido = "";
$nombre = "";
$legajo = "";
$rol = "";
$id_usuario = null;

// Verificar si se ha enviado una solicitud para eliminar un usuario
if (isset($_GET['eliminar'])) {
    $dni_usuario = $_GET['eliminar'];
    // Asegurarse de que $dni_usuario sea seguro para evitar inyecciones SQL
    $dni_usuario = $mysqli->real_escape_string($dni_usuario);
    $sql = "DELETE FROM usuarios WHERE dni = '$dni_usuario'";
    
    if ($mysqli->query($sql)) {
        // Éxito al eliminar el usuario
        // Puedes redirigir o mostrar un mensaje de éxito aquí
    } else {
        // Error al eliminar el usuario
        // Manejar el error según tus necesidades
    }
}


// Verificar si se ha enviado una solicitud para editar un usuario
if (isset($_GET['editar'])) {
    $dni = $_GET['editar'];
    // Asegurarse de que $dni_usuario sea seguro para evitar inyecciones SQL
    $dni = $mysqli->real_escape_string($dni);
    
    // Realizar una consulta para obtener los datos del usuario seleccionado
    $sql = "SELECT dni, clave, apellido, nombre, legajo, rol FROM usuarios WHERE dni = '$dni'";
    $result = $mysqli->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $id_usuario = $dni;
        $dni = $row['dni'];
        $clave = $row['clave'];
        $apellido = $row['apellido'];
        $nombre = $row['nombre'];
        $legajo = $row['legajo'];
        $rol = $row['rol'];
    }
}

// Verificar si se ha enviado una solicitud para guardar cambios en la edición
if (isset($_POST['guardar_edicion'])) {
    $dni = $_POST["dni"];
    $clave = $_POST["clave"];
    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $legajo = $_POST["legajo"];
    $rol = $_POST["rol"];

    // Realizar la actualización en la base de datos y mostrar un mensaje de éxito o error
    $sql = "UPDATE usuarios SET dni = '$dni', clave = '$clave', apellido = '$apellido', nombre = '$nombre', legajo = '$legajo', rol = '$rol' WHERE dni = '$dni'";
    
    if ($mysqli->query($sql)) {
        $mensaje = "Usuario actualizado exitosamente.";
    } else {
        $mensaje = "Error al actualizar el usuario: " . $mysqli->error;
    }
}

// Realizar una consulta para obtener la información de todos los usuarios
$sql = "SELECT dni, clave, apellido, nombre, legajo, rol FROM usuarios";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
</head>
<body>
    <h1>Administrar Usuarios</h1>

    <table border="1">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Clave</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Legajo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['dni']; ?></td>
                    <td><?php echo $row['clave']; ?></td>
                    <td><?php echo $row['apellido']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['legajo']; ?></td>
                    <td><?php echo $row['rol']; ?></td>
                    <td>
                        <a href="admin_editar_usuario.php?editar=<?php echo $row['dni']; ?>">Editar</a>
                        <a href="admin_editar_usuario.php?eliminar=<?php echo $row['dni']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Formulario para editar un usuario -->
    <h2>Editar Usuario</h2>
    <form method="post" action="admin_editar_usuario.php">
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

        <input type="submit" name="guardar_edicion" value="Guardar Cambios">
    </form>


</body>
</html>
