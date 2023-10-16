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
      <input type="text" name="dni" class="form-control form-control-lg" id="dni" placeholder="DNI" required>
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
</div>
<?php include 'tabla_usuarios_asistencia.php' ?>  

<!-- Agrega la referencia a Bootstrap 5 JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>