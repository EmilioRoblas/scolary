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
    <h2>Gestión de profesores</h2>

    <?php
    // Configuración de paginación
    $registrosPorPagina = 10;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $registrosPorPagina;

    // Consulta con paginación
    $stmtUsuarios = $pdo->query("
        SELECT 
            nombre, 
            email
        FROM usuarios 
        LIMIT $registrosPorPagina OFFSET $offset");

    $profesores = $stmtUsuarios->fetchAll();

    // Total de registros para calcular páginas
    $totalRegistros = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
    ?>

<table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profesores as $profesor){ ?>
                <tr>
                    <td><?= $profesor['nombre']?></td>
                    <td><?= $profesor['email'] ?></td>
                </tr>
            <?php } ?>
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
