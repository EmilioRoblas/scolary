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
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Curso</th>
                <th>Email</th>
            </tr>
        </thead>
        <!-- Aquí metere cada fetch de la cunsulta de alumnos -->
        <tbody>
            <tr>
                <td>Carlos López</td>
                <td>3º ESO</td>
                <td>carlos@example.com</td>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
