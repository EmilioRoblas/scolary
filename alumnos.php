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
// Tutores
$stmtTutores = $pdo ->query('SELECT * FROM usuarios WHERE rol = "tutor"');
$tutores = $stmtTutores->fetchAll();
?>


<div class="container mt-4">
    <h2>Gestión de alumnos</h2>
    
      <div class="mb-3">
        <form action="alumnos.php">
          <label for="orden" class="form-label"></label>
            <select name="orden" id="orden" class="form-select" onchange="this.form.submit()">
              <option value="">Ordenar por...</option>
              <option value="nombre">Nombre</option>
              <option value="grupo">Grupo</option>
              <option value="tutor">Tutor</option>
            </select>
        </form>
      </div>
    
    <button type="button" class="btn btn-outline-success mt-3" data-bs-toggle="modal" data-bs-target="#crearAlumno">
    + Crear alumno
    </button>

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

    <?php
    if(isset($_GET["mensaje"])){
      echo "<p style='color:green'>".$_GET["mensaje"]."</p>";
    }

    if(isset($_GET["orden"])){
      if ($_GET["orden"] == "nombre") {
        $orden = "a.nombre";
      }else if($_GET["orden"] == "grupo"){
        $orden = "g.nombre";
      }else{
        $orden = "u.nombre";
      }
    }else{
      $orden = "a.nombre";
    }

    // Configuración de paginación
    $registrosPorPagina = 10;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $registrosPorPagina;

    // Consulta con paginación
    $stmtAlumnos = $pdo->query("
        SELECT 
            a.id AS idAlumno,
            a.nombre AS nombreAlumno,
            g.nombre AS nombreGrupo,
            u.nombre AS nombreTutor
        FROM alumnos a
        JOIN grupo g ON a.id_grupo = g.id
        JOIN usuarios u ON a.id_tutor = u.id
        ORDER BY $orden
        LIMIT $registrosPorPagina OFFSET $offset");

    $alumnos = $stmtAlumnos->fetchAll();

    // Total de registros para calcular páginas
    $totalRegistros = $pdo->query("SELECT COUNT(*) FROM alumnos")->fetchColumn();
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

    // Tutores
    $stmtTutores = $pdo ->query('SELECT * FROM usuarios WHERE rol = "tutor"');
    $tutores = $stmtTutores->fetchAll();

    // Aulas
    $stmtGrupos = $pdo ->query('SELECT * FROM grupo');
    $grupos = $stmtGrupos ->fetchAll();
    ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Grupo</th>
                <th>Tutor</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno){ ?>
                <tr>
                    <td><?= $alumno['nombreAlumno']?></td>
                    <td><?= $alumno['nombreGrupo'] ?></td>
                    <td><?= $alumno['nombreTutor']?></td>
                    <td> 
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#editarAlumno" 
                          data-id="<?php echo htmlspecialchars($alumno['idAlumno']) ?>"
                          data-nombre="<?php echo htmlspecialchars($alumno['nombreAlumno']) ?>">
                          <i class="bi bi-pencil-square"></i>
                          Editar
                        </button>
                    </td>
                    <td>
                      <form action="service/eliminarAlumno.php" method="POST">
                        <input type="hidden" name="idAlumno" value="<?php echo $alumno['idAlumno'] ?>">
                        <button type="submit" class="btn btn-danger mt-2">
                          <i class="bi bi-trash"></i>Eliminar
                        </button>
                      </form>   
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
            <!-- Dialog editarAlumno -->
  <div class="modal fade" id="editarAlumno" tabindex="-1" aria-labelledby="crearAlumnoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="crearAlumnoLabel">Editar alumno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form action="service/editarAlumno.php" method="POST">
          <input type="hidden" id="idAlumnoEditar" name="idAlumnoEditar" value="">
          
          <div class="mb-3">
            <label class="form-label">Nombre del Alumno</label>
            <input type="text" id="inputNombre" name="nombre" class="form-control" placeholder="Introduce el nombre">
          </div>
          <div class="mb-3">
            <label for="tutor" class="form-label">Tutor legal</label>
            <select id="selectTutor" class="form-control" name="tutor" id="opciones">
                <option value="">Elige un tutor</option> 
                <?php 
                foreach ($tutores as $tutor) {
                echo "<option value=".$tutor['id'].">".$tutor['nombre']."</option>";
                }
                ?>
              </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select id="selectGrupo" class="form-control" name="idGrupo" id="opciones">
                <option value="">Elige un grupo</option> 
                <?php 
                
                foreach ($grupos as $grupo) {
                echo "<option value=".$grupo['id'].">".$grupo['nombre']."</option>";
                }?>
              </select>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
             <button type="submit" class="btn btn-success">Confirmar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPaginas; $i++){?>
                <li class="page-item <?= $i == $pagina ? 'active' : '' ?>"> <!-- Para activar la página en la que te encuentras -->
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php }?>
        </ul>
    </nav>
</div>
<script src="js/dialogEditarAlumno.js"></script>
<?php include 'includes/footer.php'; ?>
