<?php

// Variables de conexión a la base de datos
$host = "localhost";
$username = "root";
$password = "";
$database = "timeclock";

// Conectamos a la base de datos
$mysqli = new mysqli($host, $username, $password, $database);

// Comprobamos la conexión
if ($mysqli->connect_error) {
  die("Error al conectar a la base de datos: " . $mysqli->connect_error);
}

// Establecemos la codificación de la conexión
$mysqli->set_charset("utf8mb4");

?>

<?php

if (isset($_POST["btnEntrada"])) {
  // Get user input
  $dni = $_POST["dni"];
  $clave = $_POST["clave"];
  
  // Validate and sanitize user input (for security)
  // ...

  // Insert entrance record
  $sql = "INSERT INTO asistencia (dni, fecha, hora_entrada) VALUES ('$dni', CURDATE(), CURTIME())";
  $result = $mysqli->query($sql);

  if ($result) {
      echo "Entrance record inserted successfully.";
  } else {
      echo "Error inserting entrance record.";
  }
}


if (isset($_POST["btnSalida"])) {
  // Get user input
  $dni = $_POST["dni"];
  $clave = $_POST["clave"];

  $sql = "SELECT hora_entrada FROM asistencia WHERE dni = '$dni' AND fecha = CURDATE()";
  $result = $mysqli->query($sql);

  if ($result && $result->num_rows > 0) {
    // Fetch the entrance time
    $row = $result->fetch_assoc();
    $hora_entrada = $row["hora_entrada"];

    // Get the current time as 'hora_salida'
    $hora_salida = date("H:i:s");

    // Calculate the difference between 'hora_entrada' and 'hora_salida' using TIMEDIFF
    $sql = "CREATE TEMPORARY TABLE TempTable AS SELECT MAX(id) AS max_id FROM asistencia WHERE dni = '$dni' AND fecha = CURDATE();
            UPDATE asistencia SET hora_salida = CURTIME() WHERE id = (SELECT max_id FROM TempTable);
            DROP TEMPORARY TABLE IF EXISTS TempTable;";
    $result = $mysqli->multi_query($sql);

    // Consume the result of the multi-query
    while ($mysqli->more_results()) {
      $mysqli->next_result();
    }

    // Now you can execute the new query
    $sql = "SELECT total_horas FROM asistencia WHERE dni = '$dni' AND fecha = CURDATE()";
    $result = $mysqli->query($sql);

    if ($result) {
      // Fetch the total horas from the database
      $row = $result->fetch_assoc();
      $total_horas = $row["total_horas"];
      echo "Exit record updated successfully. Total horas: $total_horas";
    } else {
      echo "Error fetching total horas.";
    }
  } else {
    echo "No entrance record found for today.";
  }
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de asistencia</title>
  <style>
  body {
    background-color: #ffffff;
    color: #000000;
  }

  h1 {
    font-size: 24px;
  }

  form {
    width: 500px;
    margin: 0 auto;
  }

  input {
    width: 200px;
  }

  button {
    margin-top: 10px;
  }
</style>

</head>
<body>

  <h1>Control de asistencia</h1>

  <form action="index.php" method="post">
    <input type="text" name="dni" placeholder="DNI" required>
    <input type="password" name="clave" placeholder="Clave" required>
    <input type="submit" name="btnEntrada" value="Entrada">
    <input type="submit" name="btnSalida" value="Salida">
  </form>

</body>
</html>