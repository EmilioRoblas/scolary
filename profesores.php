<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>Gestión de profesores</h2>
    <p>Aquí iría la lista de profesores</p>
</div>

<?php include 'includes/footer.php'; ?>
