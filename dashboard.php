<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbarAdmin.php'; ?>
<?php include 'conexion.php'; 
//Recuento alumnos
$stmtAlumnos = $pdo ->query('SELECT * FROM alumnos');
$alumnos = $stmtAlumnos->fetchAll();

//Recuento profesores
$stmtProfesores = $pdo ->query('SELECT * FROM usuarios WHERE rol = "profesor"');
$profesores = $stmtProfesores->fetchAll();

//No cierro la conexión a bd porque se cierra automáticamente
?>

<div class="container mt-4">
    <h1>Bienvenido a Scolary, <?= htmlspecialchars($_SESSION['usuario']) ?> 👋</h1>
    <p>Panel de administración</p>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Estadísticas rápidas</h5>
            <p class="card-text">Alumnos: <?php echo count($alumnos)?> • Profesores: <?php echo count($profesores)?></p>
        </div>
    </div>
    <!-- Botón para abrir el dialog -->
<button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearAlumno">
  Gestionar alumno
</button>

<button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearAlumno">
  Gestionar usuario
</button>

<!-- Dialog -->
<div class="modal fade" id="editarAlumno" tabindex="-1" aria-labelledby="crearAlumnoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="crearAlumnoLabel">Crear Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form action="service/guardarAlumno.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Nombre del Alumno</label>
            <input type="text" name="nombre" class="form-control" placeholder="Introduce el nombre">
          </div>
          <div class="mb-3">
            <label class="form-label">Id tutor</label>
            <input type="text" name="idTutor" class="form-control" placeholder="Introduce el curso">
          </div>
          <div class="mb-3">
            <label class="form-label">Id aula</label>
            <input type="text" name="idAula" class="form-control" placeholder="Introduce el curso">
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
             <button type="submit" class="btn btn-success">Guardar</button>
          </div>
        </form>
      </div>

      

    </div>
  </div>
</div>

</div>

<?php include 'includes/footer.php'; ?>
