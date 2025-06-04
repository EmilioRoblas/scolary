<?php
session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Política de Privacidad - Scolary</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container py-5">
    <div class="card shadow-lg">
      <div class="card-body">
        <h1 class="card-title text-center mb-4">Política de Privacidad</h1>

        <p class="text-muted">Última actualización: junio de 2025</p>

        <p>
          En <strong>Scolary</strong>, nos comprometemos a proteger la privacidad de los alumnos, padres y tutores legales en cumplimiento con el <strong>Reglamento General de Protección de Datos (RGPD)</strong> y la legislación española aplicable.
        </p>

        <h4 class="mt-4">1. Responsable del tratamiento</h4>
        <p>
          El responsable del tratamiento de los datos personales es el centro educativo correspondiente, con domicilio en su sede oficial y correo de contacto proporcionado por la administración del centro.
        </p>

        <h4 class="mt-4">2. Datos recogidos</h4>
        <ul>
          <li>Nombre y apellidos del alumno y sus tutores legales</li>
          <li>Datos académicos (grupo, calificaciones, autorizaciones)</li>
          <li>Información de contacto (correo electrónico, teléfono)</li>
          <li>Otros datos necesarios para la gestión educativa</li>
        </ul>

        <h4 class="mt-4">3. Finalidad del tratamiento</h4>
        <p>
          Los datos son tratados exclusivamente para fines educativos, administrativos y de comunicación entre el centro y las familias. Esto incluye:
        </p>
        <ul>
          <li>Comunicación con padres y tutores</li>
          <li>Organización de actividades escolares</li>
          <li>Cumplimiento de obligaciones legales</li>
        </ul>

        <h4 class="mt-4">4. Conservación de los datos</h4>
        <p>
          Los datos se conservarán mientras el alumno esté matriculado en el centro, y posteriormente durante el plazo legal establecido para la conservación de expedientes académicos.
        </p>

        <h4 class="mt-4">5. Derechos de los interesados</h4>
        <p>
          Los padres, madres, tutores y tienen derecho a:
        </p>
        <ul>
          <li>Acceder a sus datos personales</li>
          <li>Rectificarlos o suprimirlos</li>
          <li>Limitar u oponerse a su tratamiento</li>
          <li>Portar sus datos a otro responsable</li>
        </ul>
        <p>
          Para ejercer estos derechos pueden darse de baja desde el botón dar de baja al final de la aplicación web.
        </p>

        <h4 class="mt-4">6. Cesión de datos</h4>
        <p>
          Los datos no se cederán a terceros salvo por obligación legal o por necesidades justificadas del servicio educativo (por ejemplo, plataformas educativas oficiales).
        </p>

        <h4 class="mt-4">7. Medidas de seguridad</h4>
        <p>
          El centro educativo adopta las medidas técnicas y organizativas necesarias para garantizar la seguridad e integridad de los datos personales tratados.
        </p>

        <div class="text-center mt-5">
          <a href="<?php if(isset($_SESSION['usuarioRol']) && $_SESSION['usuarioRol'] == 'admin'){
            echo "dashboardAdmin.php";
          }else if(isset($_SESSION['usuarioRol']) && $_SESSION['usuarioRol'] == 'profesor'){
            echo "dashboardProfesor.php";
          }else{
            echo "dashboardTutor.php";
          }
          ?>" class="btn btn-secondary">Volver al Panel</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
