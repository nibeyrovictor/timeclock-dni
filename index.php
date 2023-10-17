<?php
// Inicializa $usuarioExiste como falso
$usuarioExiste = false;

// Verifica si se ha enviado el formulario
if (isset($_POST['btnEntrada'])) {
    $dni = $_POST['dni'];
    $clave = $_POST['clave'];
    include 'dbcon.php';
    // Realiza la consulta a la base de datos para verificar si el usuario existe
    $consulta = "SELECT nombre, apellido FROM usuarios WHERE dni = '$dni' AND clave = '$clave'";
    
    // Ejecuta la consulta
    $resultado = mysqli_query($mysqli, $consulta); // Asumiendo que $conexion es tu conexión a la base de datos

    // Verifica si la consulta tuvo resultados
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuarioExiste = true;
        $row = mysqli_fetch_assoc($resultado);
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];

    }
    if ($usuarioExiste) {
        // Si el usuario existe, muestra un mensaje de bienvenida
        $row = $resultado;
        echo '<div class="alert alert-success">Bienvenido, ' . $nombre . ' ' . $apellido . '.</div>';
    } else {
        echo '<div class="alert alert-danger">DNI o clave incorrectos. Por favor, intenta nuevamente.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de asistencia</title>
  <?php include 'dbcon.php' ?>
  <?php include 'botones_asistencia.php' ?>  
  <script>
    function refreshPageAfterDelay(delay) {
      setTimeout(function () {
        location.reload();
      }, delay);
    }

    

});
  </script>


<script>
  function applyInputMask(inputId, mask) {
    var input = document.getElementById(inputId);
    if (input) {
      input.addEventListener('input', function () {
        var value = input.value.replace(/\D/g, '');
        var maskedValue = '';
        for (var i = 0, j = 0; i < mask.length && j < value.length; i++) {
          if (mask[i] === '0') {
            maskedValue += value[j];
            j++;
          } else if (mask[i] === '.') {
            // Insert a dot for the thousand separator
            maskedValue += '.';
          } else {
            maskedValue += mask[i];
          }
        }
        input.value = maskedValue;
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    applyInputMask('dni', '00.000.000');
  });
</script>



  <!-- Agrega la referencia a Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Control de asistencia</h1>
    <form action="index.php" method="post" class="mt-3">
        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control form-control-lg" id="dni" placeholder="DNI">
            <span style="color: red;"><?php echo $usuarioError; ?></span>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Clave</label>
            <input type="password" name="clave" class="form-control form-control-lg" id="clave" placeholder="Clave" required>
            <span style="color: red;"><?php echo $claveError; ?></span>
        </div>
        <button type="submit" name="btnEntrada" class="btn btn-primary btn-lg" onclick="refreshPageAfterDelay(5000)">Entrada</button>
        <button type="submit" name="btnSalida" class="btn btn-danger btn-lg" onclick="refreshPageAfterDelay(5000)">Salida</button>
        <button type="submit" name="btnVerHorario" class="btn btn-success btn-lg">Ver mi horario por mes</button>
        <form action="admin.php" method="post">
            <button type="submit" name="btnAdmin" class="btn btn-primary btn-lg">Admin</button>
        </form>
    </form>
    <form action="registrar_usuario.php">
    <button type="submit" name="registrar" class="btn btn-primary btn-lg">Registrar Usuario</button>
</form>

</div>
<?php include 'tabla_usuarios_asistencia.php' ?>  

<!-- Agrega la referencia a Bootstrap 5 JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>