<?php
if (isset($_POST["btnVerHorario"])) {
  // Obtener el DNI y la clave del usuario
  $dni = $_POST["dni"];
  $clave = $_POST["clave"];

  // Realiza una consulta SQL para verificar si el usuario existe en la tabla usuarios
  $sql = "SELECT * FROM usuarios WHERE dni = '$dni' AND clave = '$clave'";
  $result = $mysqli->query($sql);
  if ($result && $result->num_rows > 0) {
 // Mostrar el horario por mes

  // Realiza una consulta SQL para obtener todos los meses guardados
  $sql = "SELECT DISTINCT DATE_FORMAT(fecha, '%m-%Y') AS mes FROM asistencia WHERE dni = '$dni'";
  $result = $mysqli->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mes = $row['mes'];

        // Extraer el mes y el año
        list($numeroMes, $anio) = explode('-', $mes);
        $nombreMes = date('F', mktime(0, 0, 0, $numeroMes, 1));

        // Mostrar el mes y año y crear una tabla para ese mes
        echo "<h3>Mes: " . $nombreMes . " " . $anio . "</h3>";
        echo "<table class='table'>";
        echo "<tr><th>Fecha</th><th>Hora de Entrada</th><th>Hora de Salida</th><th>Total: Hora-Minuto-Segundos</th></tr>";

        $totalHoras = "00:00:00"; // Inicializa el total de horas a 0

        // Realiza una consulta SQL para obtener los registros de asistencia para el mes actual
        $sql = "SELECT fecha, hora_entrada, hora_salida, total_horas FROM asistencia WHERE dni = '$dni' AND DATE_FORMAT(fecha, '%m-%Y') = '$mes'";
        $resultMes = $mysqli->query($sql);

        while ($rowMes = $resultMes->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowMes['fecha'] . "</td>";
            echo "<td>" . $rowMes['hora_entrada'] . "</td>";
            echo "<td>" . $rowMes['hora_salida'] . "</td>";
            echo "<td>" . $rowMes['total_horas'] . "</td>";

            // Suma el tiempo total
            $totalHoras = sumarHoras($totalHoras, $rowMes['total_horas']);

            echo "</tr>";
        }

        // Convierte el tiempo total a un formato adecuado
        $totalHoras = date("H:i:s", strtotime($totalHoras));

        echo "<tr>";
        echo "<td colspan='3' align='right'><b>Total:</b></td>";
        echo "<td><b>" . $totalHoras . "</b></td>";
        echo "</tr>";

        echo "</table>";
    }
} else {
    echo "No se encontraron registros de asistencia para ningún mes.";
}
} else {
echo "Verifique sus usuario y clave ingresada.";
}


  }
  function sumarHoras($hora1, $hora2) {
    $tiempo1 = strtotime("1970-01-01 " . $hora1);
    $tiempo2 = strtotime("1970-01-01 " . $hora2);
    $suma = date("H:i:s", $tiempo1 + $tiempo2);

    return $suma;
}
?>
