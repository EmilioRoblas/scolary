<?php
// session_start();
// if (!isset($_SESSION['usuario'])) {
//     header("Location: login.php");
//     exit();
// }
// ?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbarAdmin.php'; ?>
<?php include 'conexion.php'; 
// Recuento alumnos
$stmtAlumnos = $pdo ->query('SELECT * FROM alumnos');
$alumnos = $stmtAlumnos->fetchAll();

// Recuento profesores
$stmtProfesores = $pdo ->query('SELECT * FROM usuarios WHERE rol = "profesor"');
$profesores = $stmtProfesores->fetchAll();

// Tutores
$stmtTutores = $pdo ->query('SELECT * FROM usuarios WHERE rol = "tutor"');
$tutores = $stmtTutores->fetchAll();

// Aulas
$stmtGrupos = $pdo ->query('SELECT * FROM grupo');
$grupos = $stmtGrupos ->fetchAll();



//No cierro la conexión a bd porque se cierra automáticamente
?>

<div class="container mt-4">
    <h1>Bienvenido a Scolary, <?php //htmlspecialchars($_SESSION['usuario']) ?> 👋</h1>
    <p>Panel de administración</p>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Estadísticas rápidas</h5>
            <p class="card-text">Alumnos: <?php echo count($alumnos)?> • Profesores: <?php echo count($profesores)?> • Tutores: <?php echo count($tutores)?></p>
        </div>
    </div>

  <?php 
    if(isset($_GET['mensaje'])){ 
      echo "  <div class='mb-3'>
              <p style='color:green'>".$_GET['mensaje']."</p>
              </div>";
    };
  ?>
    <!-- Botón para abrir el dialog -->
  <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearAlumno">
    + Crear alumno
  </button>

  <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearUsuario">
    + Crear usuario
  </button>

  <?php 
    if(isset($_GET['error'])){ 
      echo "  <div class='mb-3'>
              <p style='color:red'>".$_GET['error']."</p>
              </div>";
    };
  ?>
  <!-- Dialog crear alumno -->
  <div class="modal fade" id="crearAlumno" tabindex="-1" aria-labelledby="crearAlumnoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="crearAlumnoLabel">Crear Alumno</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <form action="service/crearAlumno.php" method="POST">
            <div class="mb-3">
              <label class="form-label">Nombre del Alumno</label>
              <input type="text" name="nombre" class="form-control" placeholder="Introduce el nombre">
            </div>
            <div class="mb-3">
              <label for="opciones" class="form-label">Tutor legal</label>
              <select class="form-control" name="tutor" id="opciones">
                <option value="">Elige un tutor</option> 
                <?php 
                foreach ($tutores as $tutor) {
                echo "<option value=".$tutor['id'].">".$tutor['nombre']."</option>";
                }
                ?>
              </select>
              <label for="opciones" class="form-label">Agregar tutor</label><br>
              <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearTutor">
                +
              </button>    

            </div>
            <div class="mb-3">
              <label for="grupo" class="form-label">Id aula</label>
              <select class="form-control" name="idGrupo" id="opciones">
                <option value="">Elige un grupo</option> 
                <?php 
                
                foreach ($grupos as $grupo) {
                echo "<option value=".$grupo['id'].">".$grupo['nombre']."</option>";
                }?>
              </select>
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

              <!-- Dialog nuevo tutor -->

  <div class="modal fade" id="crearTutor" tabindex="-1" aria-labelledby="crearAlumnoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="crearAlumnoLabel">Crear Tutor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <form action="service/crearUsuario.php" method="POST">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre del Tutor</label>
              <input type="text" name="nombre" class="form-control" placeholder="Introduce el nombre">
            </div>
            <div class="mb-3">
              <label for="opciones" class="form-label">Correo</label>
              <input type="text" name="correo" class="form-control" placeholder="tuCorreo@ejemplo.com">
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

              <!-- Dialog crear usuario -->
  <div class="modal fade" id="crearUsuario" tabindex="-1" aria-labelledby="crearAlumnoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="crearAlumnoLabel">Crear Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <form action="service/crearUsuario.php" method="POST">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre del Usuario</label>
              <input type="text" name="nombre" class="form-control" placeholder="Introduce el nombre">
            </div>
            <div class="mb-3">
              <label for="correo" class="form-label">Correo</label>
              <input type="text" name="correo" class="form-control" placeholder="tuCorreo@ejemplo.com">
            </div>
            <div class="mb-3">
              <label for="rol" class="form-label">Rol</label>
              <select class="form-control" name="rol" id="rol">
                <option value="">Elige un rol</option> 
                <option value="profesor">Profesor</option>
                <option value="tutor">Tutor</option>
                <option value="admin">Administrador</option>
              </select>
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
