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