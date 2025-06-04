<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuarioRol'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

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

//No cierro la conexión a bd porque se cierra automáticamente con pdo
?>
<main class="main-content">
<div class="container mt-4">
    <h1>Bienvenido a Scolary, <?php echo htmlspecialchars($_SESSION['usuario']) ?> 👋</h1>
    <p>Panel de administración</p>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Estadísticas rápidas</h5>
            <p class="card-text">Alumnos: <?php echo count($alumnos)?> • Profesores: <?php echo count($profesores)?> • Tutores: <?php echo count($tutores)?></p>
        </div>
    </div>

  <?php if (isset($_GET['mensaje'])){ ?>
    <div class="alert alert-success"><?= $_GET['mensaje'] ?></div>
    <?php }?>
    <?php if (isset($_GET['error'])){ ?>
    <div class="alert alert-danger"><?= $_GET['error'] ?></div>
    <?php }?>
    
    <!-- Botón para abrir el dialog -->
  <button type="button" class="btn botonCrear mt-3" data-bs-toggle="modal" data-bs-target="#crearAlumno">
    + Crear alumno
  </button>

  <button type="button" class="btn botonCrear mt-3" data-bs-toggle="modal" data-bs-target="#crearUsuario">
    + Crear usuario
  </button>

    <div class="mt-4 row">
  <div class="col-md-6">
    <h5 class="mb-3">📄 Insertar alumnos con archivo CSV</h5>
    <form action="service/introducirAlumnos.php" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-2">
      <div>
        <label for="csv" class="form-label">Selecciona archivo CSV</label>
        <input class="form-control form-control-sm" type="file" id="csv" name="csv" accept=".csv" required>
      </div>
      <button type="submit" class="btn botonCrear btn-sm">Subir e insertar</button>
      <div class="form-text">
        Formato: <code>nombre, id_tutor, id_grupo</code><br>
        Ejemplo: <code>Juan Pérez, 1, 2</code>
      </div>
    </form>
  </div>

  <div class="col-md-6">
    <?php if (!empty($_SESSION['mensajeInsert']) || !empty($_SESSION['erroresInsert'])){ ?>
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">📋 Resultado de la carga</h5>

        <?php if (!empty($_SESSION['mensajeInsert'])){ ?>
        <div class="alert alert-success p-2" role="alert">
          <?php foreach ($_SESSION['mensajeInsert'] as $msg): ?>
            <div>✅ <?php echo $msg; ?></div>
          <?php endforeach; ?>
        </div>
        <?php } ?>

        <?php if (!empty($_SESSION['erroresInsert'])){ ?>
        <div class="alert alert-danger p-2" role="alert">
          <?php foreach ($_SESSION['erroresInsert'] as $err){ ?>
            <div>❌ <?php echo $err; ?></div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
    <?php 
      // Limpiar los mensajes después de mostrarlos
      unset($_SESSION['mensajeInsert']);
      unset($_SESSION['erroresInsert']);
    }
    ?>
  </div>
</div>

  
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
          <form action="service/crearUsuario.php" method="POST" enctype="multipart/form-data">
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
            <div class="mb-3 d-none" id="campoAvatar">
              <label for="avatar" class="form-label">Avatar</label>
              <input type="file" name="avatar" class="form-control">
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
<script src="js/campoAvatar.js"></script>
</main>
<?php include 'includes/footer.php'; ?>
