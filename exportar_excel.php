<?php
// Incluir la biblioteca o clase para exportar datos a Excel
include 'phpexcel.php'; // Reemplaza 'phpexcel.php' con el archivo real de la biblioteca que estás utilizando

// Crear un objeto de la biblioteca o clase
$objPHPExcel = new PHPExcel();

// Agregar datos a la hoja de Excel
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Apellido');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'DNI');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Legajo');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Fecha');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Entrada');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Salida');

// Realizar una consulta para obtener los datos de usuarios y registros
$sql_usuarios = "SELECT dni, apellido, nombre, legajo FROM usuarios";
$result_usuarios = $mysqli->query($sql_usuarios);
$row_number = 2; // Comienza a partir de la segunda fila para agregar datos

while ($row_usuario = $result_usuarios->fetch_assoc()) {
    // Agregar datos de usuarios a la hoja de Excel
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row_number, $row_usuario['apellido']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $row_number, $row_usuario['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row_number, $row_usuario['dni']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row_number, $row_usuario['legajo']);

    $dni_usuario = $row_usuario['dni'];
    $sql_registros = "SELECT fecha, entrada, salida FROM registros WHERE dni_usuario = '$dni_usuario'";
    $result_registros = $mysqli->query($sql_registros);

    while ($row_registro = $result_registros->fetch_assoc()) {
        // Agregar datos de registros a la hoja de Excel
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $row_number, $row_registro['fecha']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row_number, $row_registro['entrada']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $row_number, $row_registro['salida']);
        $row_number++;
    }
}

// Configurar el encabezado para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="informe_usuarios.xlsx"');
header('Cache-Control: max-age=0');

// Exportar el archivo de Excel
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
