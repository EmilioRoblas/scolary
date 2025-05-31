<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuarioRol'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
include 'includes/navbarProfesor.php';
include 'includes/footer.php';
include 'conexion.php';
?>

<div class="container mt-4">
    <h1>Tus cursos</h1>
    <p>Panel de profesorado</p>

    
</div>