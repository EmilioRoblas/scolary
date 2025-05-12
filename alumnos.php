<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbarAdmin.php'; ?>
<?php include 'conexion.php'; ?>

<div class="container mt-4">
    <h2>Gestión de alumnos</h2>

    <?php
    // Configuración de paginación
    $registrosPorPagina = 10;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $registrosPorPagina;

    // Consulta con paginación
    $stmtAlumnos = $pdo->query("
        SELECT 
            a.nombre AS nombreAlumno,
            g.nombre AS nombreGrupo,
            u.nombre AS nombreTutor
        FROM alumnos a
        JOIN grupo g ON a.id_grupo = g.id
        JOIN usuarios u ON a.id_tutor = u.id
        LIMIT $registrosPorPagina OFFSET $offset");

    $alumnos = $stmtAlumnos->fetchAll();

    // Total de registros para calcular páginas
    $totalRegistros = $pdo->query("SELECT COUNT(*) FROM alumnos")->fetchColumn();
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
    ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Curso</th>
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
                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#editarAlumno">
                    Editar
                    </button>
                    </td>
                    <td><button type="button" class="btn btn-danger mt-2">
                     <i class="bi bi-trash"></i>Eliminar
                    </button></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
            <!-- Dialog editarAlumno -->
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

<?php include 'includes/footer.php'; ?>
