<?php
// Incluir la biblioteca o clase para exportar datos a Excel
include 'dbcon.php';
include './ex/vendor/autoload.php'; // Reemplaza 'phpexcel.php' con el archivo real de la biblioteca que estás utilizando

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Crear un objeto de hoja de cálculo
$spreadsheet = new Spreadsheet();

// Obtener la hoja de trabajo activa
$sheet = $spreadsheet->getActiveSheet();

// Agregar datos a la hoja de Excel
$sheet->setCellValue('A1', 'Apellido');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'DNI');
$sheet->setCellValue('D1', 'Legajo');
$sheet->setCellValue('E1', 'Fecha');
$sheet->setCellValue('F1', 'Entrada');
$sheet->setCellValue('G1', 'Salida');

// Realizar una consulta para obtener los datos de usuarios y registros
$sql_usuarios = "SELECT dni, apellido, nombre, legajo FROM usuarios";
$result_usuarios = $mysqli->query($sql_usuarios);
$row_number = 2; // Comienza a partir de la segunda fila para agregar datos

while ($row_usuario = $result_usuarios->fetch_assoc()) {
    // Agregar datos de usuarios a la hoja de Excel
    $sheet->setCellValue('A' . $row_number, $row_usuario['apellido']);
    $sheet->setCellValue('B' . $row_number, $row_usuario['nombre']);
    $sheet->setCellValue('C' . $row_number, $row_usuario['dni']);
    $sheet->setCellValue('D' . $row_number, $row_usuario['legajo']);

    $dni_usuario = $row_usuario['dni'];
    $sql_registros = "SELECT fecha, hora_entrada, hora_salida FROM asistencia WHERE dni = '$dni_usuario'";
    $result_registros = $mysqli->query($sql_registros);

    $total_horas = 0; // Inicializa el total de horas para este usuario

    if ($result_registros->num_rows > 0) {
        // La consulta devolvió filas, por lo que procedemos a agregar los datos
        while ($row_registro = $result_registros->fetch_assoc()) {
            // Validar la fecha
            try {
                $fecha_formateada = date_format(date_create($row_registro['fecha']), 'Y-m-d');
            } catch (Exception $e) {
                // La fecha no es válida, por lo que mostramos un mensaje de error
                echo "La fecha de la fila $row_number no es válida.";
                continue;
            }

            // Agregar datos de registros a la hoja de Excel
            $sheet->setCellValue('E' . $row_number, $fecha_formateada);
            $sheet->setCellValue('F' . $row_number, $row_registro['hora_entrada']);
            $sheet->setCellValue('G' . $row_number, $row_registro['hora_salida']);

            // Calcula las horas trabajadas para este registro
            $hora_entrada = new DateTime($row_registro['hora_entrada']);
            $hora_salida = new DateTime($row_registro['hora_salida']);
            $diferencia = $hora_entrada->diff($hora_salida);

            // Suma las horas trabajadas al total
            $total_horas += $diferencia->h; // Suma las horas
            $total_horas += $diferencia->i / 60; // Suma los minutos convertidos a horas
            $row_number++;
        }

        // Agregar el total de horas trabajadas para este usuario en la columna H
        $sheet->setCellValue('H' . ($row_number - 1), $total_horas); // Columna H para el total de horas
    } else {
        // La consulta no devolvió filas, por lo que mostramos un mensaje de error
        echo "El usuario con DNI $dni_usuario no tiene registros de asistencia.";
    }
}

// Crear un objeto de exportación de Excel
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

// Configurar el encabezado para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
header('Content-Disposition: attachment;filename="informe_usuarios.xlsx"');
header('Cache-Control: max-age=0');

// Configurar el encoding interno de PHP a UTF-8
mb_internal_encoding('UTF-8');

// Guardar el archivo de Excel en la salida (descargarlo)
$writer->save('php://output');
?>
