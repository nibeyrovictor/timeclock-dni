<?php
// Incluir la conexión a la base de datos
session_start();
include 'dbcon.php';

// Verifica si el usuario tiene permisos de administrador
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: index.php'); // Redirige al inicio de sesión si no es un administrador
    exit();
}

// Realizar una consulta para obtener la información de todos los usuarios
$sql_usuarios = "SELECT dni, apellido, nombre, legajo FROM usuarios";
$result_usuarios = $mysqli->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Usuarios</title>
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

<h1>Informe de Usuarios</h1>

<!-- Botón para exportar a Excel -->
<form method="post" action="exportar_excel.php">
    <input type="submit" name="exportar_excel" value="Exportar a Excel" class="btn btn-primary mt-3">
</form>

<!-- Tabla con todos los usuarios -->
<div class="table-responsive">
    <?php
    while ($row_usuario = $result_usuarios->fetch_assoc()) {
        // Extrae el valor de DNI del usuario actual
        $dni = $row_usuario['dni'];
    ?>
    <table class="table table-bordered table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Legajo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $row_usuario['apellido']; ?></td>
                <td><?php echo $row_usuario['nombre']; ?></td>
                <td><?php echo $row_usuario['dni']; ?></td>
                <td><?php echo $row_usuario['legajo']; ?></td>
            </tr>

            <?php
            // Inicializar la variable $total_horas
            $total_horas = 0;

            // Consulta para obtener registros de entradas y salidas por usuario para un mes y año específicos
            $currentMonth = date('m');
            $currentYear = date('Y');
            $sql_registros = "SELECT fecha, hora_entrada, hora_salida, total_horas FROM asistencia WHERE dni = '$dni' AND MONTH(fecha) = $currentMonth AND YEAR(fecha) = $currentYear";
            $result_registros = $mysqli->query($sql_registros);

            // Tabla de registros de entradas y salidas
            echo '<tr>';
            echo '<td colspan="4">';
            echo '<table class="table table-bordered">';
            echo '<tr>';
            echo '<th>Fecha</th>';
            echo '<th>Entrada</th>';
            echo '<th>Salida</th>';
            echo '<th>Total hora:minutos:segundos</th>';
            echo '</tr>';

            while ($row_registro = $result_registros->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row_registro['fecha'] . '</td';
                echo '<td></td>';
                // Agregar la hora de entrada y hora de salida en sus respectivas celdas
                if ($row_registro['hora_entrada'] !== null) {
                    echo '<td>' . $row_registro['hora_entrada'] . '</td>';
                } else {
                    echo '<td></td>';
                }

                if ($row_registro['hora_salida'] !== null) {
                    echo '<td>' . $row_registro['hora_salida'] . '</td>';
                    $salida = strtotime($row_registro['hora_salida']);
                } else {
                    echo '<td></td>';
                    $salida = 0; // Establecer la salida en 0 si es nula
                }
                if ($row_registro['total_horas'] !== null) {
                    echo '<td>' . $row_registro['total_horas'] . '</td>';
                    $t_hora = strtotime($row_registro['total_horas']);
                } else {
                    echo '<td></td>';
                    $t_hora = 0; // Establecer la salida en 0 si es nula
                }

                echo '</tr>';

                // Verificar si la hora de entrada no es nula
                if ($row_registro['hora_entrada'] !== null) {
                    $entrada = strtotime($row_registro['hora_entrada']);
                } else {
                    $entrada = 0; // Establecer la entrada en 0 si es nula
                }

                // Calcula las horas trabajadas y suma al total
                $diferencia = $salida - $entrada;
                $total_horas += $diferencia;
            }

            echo '</table>';
            // Convierte el total de segundos a formato HH:MM
            $total_horas_formateado = gmdate("H:i:s", $total_horas);
            echo 'Total horas trabajadas: ' . $total_horas_formateado; // Muestra el total de horas en formato HH:MM
            echo '</td>';
            echo '</tr>';
            ?>
        </tbody>
    </table>
    <?php
    }
    ?>
</div>


<!-- Agrega la referencia a Bootstrap 5 JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
