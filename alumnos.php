<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
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
            au.nombre AS nombreAula,
            u.nombre AS nombreTutor
        FROM alumnos a
        JOIN aulas au ON a.id_aula = au.id
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
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= $alumno['nombreAlumno']?></td>
                    <td><?= $alumno['nombreAula'] ?></td>
                    <td><?= $alumno['nombreTutor']?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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
