<?php
// Incluir la conexión a la base de datos
include 'dbcon.php';

// Aquí debes cargar la biblioteca o clase para exportar datos a Excel
// Asegúrate de que esté disponible en tu proyecto

// Realizar una consulta para obtener la información de todos los usuarios
$sql_usuarios = "SELECT dni, apellido, nombre, legajo FROM usuarios";
$result_usuarios = $mysqli->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Usuarios</title>
</head>
<body>
    <h1>Informe de Usuarios</h1>

    <!-- Botón para exportar a Excel -->
    <form method="post" action="exportar_excel.php">
        <input type="submit" name="exportar_excel" value="Exportar a Excel">
    </form>

    <!-- Tabla con todos los usuarios -->
    <table border="1">
        <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Legajo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row_usuario = $result_usuarios->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row_usuario['apellido'] . '</td>';
                echo '<td>' . $row_usuario['nombre'] . '</td>';
                echo '<td>' . $row_usuario['dni'] . '</td>';
                echo '<td>' . $row_usuario['legajo'] . '</td>';
                echo '</tr>';

               // Extrae el valor de DNI del usuario actual
    $dni = $row_usuario['dni'];

    // Consulta para obtener registros de entradas y salidas por usuario
    $sql_registros = "SELECT fecha, hora_entrada, hora_salida FROM asistencia WHERE dni = '$dni'";
    $result_registros = $mysqli->query($sql_registros);

                // Tabla de registros de entradas y salidas
                echo '<tr>';
                echo '<td colspan="4">';
                echo '<table border="1">';
                echo '<tr>';
                echo '<th>Fecha</th>';
                echo '<th>Entrada</th>';
                echo '<th>Salida</th>';
                echo '</tr>';

                $total_horas = 0;

                while ($row_registro = $result_registros->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row_registro['fecha'] . '</td>';
                
                    // Verificar si la hora de entrada no es nula
                    if ($row_registro['hora_entrada'] !== null) {
                        echo '<td>' . $row_registro['hora_entrada'] . '</td>';
                        $entrada = strtotime($row_registro['hora_entrada']);
                    } else {
                        echo '<td></td>';
                        $entrada = 0; // Establecer la entrada en 0 si es nula
                    }
                
                    // Verificar si la hora de salida no es nula
                    if ($row_registro['hora_salida'] !== null) {
                        echo '<td>' . $row_registro['hora_salida'] . '</td>';
                        $salida = strtotime($row_registro['hora_salida']);
                    } else {
                        echo '<td></td>';
                        $salida = 0; // Establecer la salida en 0 si es nula
                    }
                
                    echo '</tr>';
                
                    // Calcula las horas trabajadas y suma al total
                    $diferencia = $salida - $entrada;
                    $total_horas += $diferencia;
                }
                

                echo '</table>';
                echo 'Total horas trabajadas: ' . gmdate("H:i", $total_horas); // Muestra el total de horas en formato HH:MM
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>
