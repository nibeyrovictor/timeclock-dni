

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión de Administrador</title>
  <!-- Agrega la referencia a Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <h1 class="mt-5">Iniciar Sesión de Administrador</h1>
  <form action="admin.php" method="post" class="mt-3">
    <div class="mb-3">
      <label for="dni" class="form-label">DNI</label>
      <input type="text" name="dni" class="form-control form-control-lg" id="dni" placeholder="DNI" required>
    </div>
    <div class="mb-3">
      <label for="clave" class="form-label">Clave</label>
      <input type="password" name="clave" class="form-control form-control-lg" id="clave" placeholder="Clave" required>
    </div>
  
    <button type="submit" name="btnIniciarSesion" class="btn btn-primary btn-lg">Iniciar Sesión</button>
  </form>
</div>

<!-- Agrega la referencia a Bootstrap 5 JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Incluye la lógica de autenticación y la configuración de la base de datos aquí
session_start(); // Inicia la sesión

include 'dbcon.php';

if (isset($_POST['btnIniciarSesion'])) {
  // Obtén el DNI y la contraseña ingresados por el administrador
  $dni = $_POST['dni'];
  $clave = $_POST['clave'];

  // Realiza una consulta para verificar las credenciales y el rol del usuario
  $sql = "SELECT * FROM usuarios WHERE dni = '$dni' AND clave = '$clave' AND rol = 'admin'";
  $result = $mysqli->query($sql);

  if ($result->num_rows > 0) {
    // Las credenciales son válidas y el usuario tiene el rol "admin"
    // Configura la variable de sesión para indicar que es un administrador
    $_SESSION['admin'] = true;
    
    // Redirige al administrador a la página de administración
    header('Location: admin_dashboard.php');
  } else {
    // Las credenciales son inválidas o el usuario no es administrador
    // Puedes mostrar un mensaje de error o redirigir a una página de inicio de sesión incorrecta
    echo "Credenciales incorrectas o no eres administrador.";
  }
}
?>
