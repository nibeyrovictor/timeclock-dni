<?php


// Variables para almacenar los mensajes de error
$usuarioError = "";
$claveError = "";

if (isset($_POST["btnEntrada"])) {
  // Get user input
  $dni = $_POST["dni"];
  $clave = $_POST["clave"];
 
  // Realiza una consulta SQL para verificar si el usuario existe en la tabla usuarios
  $sql = "SELECT * FROM usuarios WHERE dni = '$dni' AND clave = '$clave'";
  $result = $mysqli->query($sql);
  
  if ($result && $result->num_rows > 0) {
    // El usuario es válido, puedes proceder con la entrada
    $sql = "INSERT INTO asistencia (id,dni, fecha, hora_entrada) VALUES (CURTIME(),'$dni', CURDATE(), CURTIME())";
    $result = $mysqli->query($sql);
    if ($result) {
        echo "Entrance record inserted successfully.";
    } else {
        echo "Error inserting entrance record.";
    }
  } else {
    {
        // Comprueba si el error es en el DNI o la clave
        $sql = "SELECT * FROM usuarios WHERE dni = '$dni'";
        $result = $mysqli->query($sql);
      
        if ($result && $result->num_rows === 0) {
            $usuarioError = "Usuario no encontrado en la tabla de usuarios.";
        } else {
            $claveError ="Clave incorrecta.";
        }
      }
      }
    }

    if (isset($_POST["btnSalida"])) {
        // Get user input
        $dni = $_POST["dni"];
        $clave = $_POST["clave"];
      
        // Realiza una consulta SQL para verificar si el usuario y la clave son válidos
        $sql = "SELECT * FROM usuarios WHERE dni = '$dni' AND clave = '$clave'";
        $result = $mysqli->query($sql);
      
        if ($result && $result->num_rows > 0) {
          // La clave y el DNI son válidos
      
          // Realiza una consulta SQL para obtener hora_entrada
          $sql = "SELECT hora_entrada FROM asistencia WHERE dni = '$dni' AND fecha = CURDATE() ";
          $result = $mysqli->query($sql);
      
          if ($result && $result->num_rows > 0) {
            // Fetch the entrance time
            $row = $result->fetch_assoc();
            if (isset($row["hora_entrada"])) {
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

              // Calculate the difference between hora_entrada and hora_salida
              $sql = "UPDATE asistencia SET total_horas = TIMEDIFF(hora_salida, hora_entrada) WHERE dni = '$dni' AND fecha = CURDATE()";
              $result = $mysqli->query($sql);
      
              $sql = "SELECT total_horas FROM asistencia WHERE dni = '$dni' AND fecha = CURDATE()";
    $result = $mysqli->query($sql);
    if ($result && $result->num_rows > 0) {
                  // Retrieve the total_horas value from the database
                  $row = $result->fetch_assoc();
                  $total_horas = $row["total_horas"];
                  echo "Exit record updated successfully. Total horas: $total_horas";
                  } else {
                    echo "Error al calcular y actualizar total_horas.";
                  }
                } else {
                  echo "No entrance record found for today.";
                }
            } else {
              echo "No se encontró la hora de entrada.";
            }
        } else {
            {
        // Comprueba si el error es en el DNI o la clave
        $sql = "SELECT * FROM usuarios WHERE dni = '$dni'";
        $result = $mysqli->query($sql);
      
        if ($result && $result->num_rows === 0) {
            $usuarioError = "Usuario no encontrado en la tabla de usuarios.";
        } else {
            $claveError ="Clave incorrecta.";
        }
        }
    }
}
?>

<?php
if (isset($_POST["btnAdmin"])) {
  // Obtén el rol del usuario desde la base de datos
  $dni = $_POST["dni"];
  $clave = $_POST["clave"];
  $sql = "SELECT rol FROM usuarios WHERE dni = '$dni' AND clave = '$clave'";
  $result = $mysqli->query($sql);
  
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rol = $row['rol'];
    
    if ($rol == "admin") {
      // El usuario es un administrador, redirige a la página de administrador
      header("Location: admin.php");
      exit; // Asegúrate de salir para evitar que se procese el resto de la página
    } else {
      echo "No tienes permisos de administrador.";
    }
  }
}
?>