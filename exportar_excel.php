<?php
// Incluir la biblioteca o clase para exportar datos a Excel
include 'dbcon.php';
include './ex/vendor/autoload.php'; // Reemplaza 'phpexcel.php' con el archivo real de la biblioteca que estás utilizando

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Font;

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
$sheet->setCellValue('H1', 'Total de horas por día');

// Aplicar formato en negrita al encabezado
$encabezadoStyle = $sheet->getStyle('A1:H1');
$encabezadoStyle->applyFromArray([
    'font' => [
        'bold' => true,
    ],
]);

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
    $sql_registros = "SELECT fecha, hora_entrada, hora_salida, total_horas FROM asistencia WHERE dni = '$dni_usuario'";
    $result_registros = $mysqli->query($sql_registros);

    while ($row_registro = $result_registros->fetch_assoc()) {
        // Agregar datos de registros a la hoja de Excel
        $sheet->setCellValue('E' . $row_number, $row_registro['fecha']);
        $sheet->setCellValue('F' . $row_number, $row_registro['hora_entrada']);
        $sheet->setCellValue('G' . $row_number, $row_registro['hora_salida']);
        $sheet->setCellValue('H' . $row_number, $row_registro['total_horas']);
        $row_number++;
    }
}

// Obtener la fecha actual
$currentDate = date('Y-m-d');

// Crear un objeto de exportación de Excel
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

// Configurar el nombre del archivo con la fecha actual
$filename = 'informe_usuarios_' . $currentDate . '.xlsx';

// Configurar el encabezado para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Guardar el archivo de Excel en la salida (descargarlo)
$writer->save('php://output');

?>
