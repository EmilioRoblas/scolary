<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
include 'includes/navbarTutor.php';
include 'conexion.php';

$stmtFirmadas = $pdo ->query('SELECT * FROM autorizaciones_alumnos WHERE estado = "firmada"');
$firmadas = $stmtFirmadas->fetchAll();

$stmtPendientes = $pdo ->query('SELECT * FROM autorizaciones_alumnos WHERE estado = "pendiente"');
$pendientes = $stmtPendientes->fetchAll();

$stmtRechazadas = $pdo ->query('SELECT * FROM autorizaciones_alumnos WHERE estado = "rechazada"');
$rechazadas = $stmtRechazadas->fetchAll();



?>

<div class="container mt-4">
    <img src="<?php echo substr($_SESSION['usuarioAvatar'],3); ?>" alt="Avatar del usuario" width="100">
    <h1>Bienvenido a Scolary, <?php echo $_SESSION['usuario'] ?> 👋</h1>
    <p>Panel del tutor</p>

    <div class="card mt-4">
        
        <div class="card-body">
            <h5 class="card-title">Tus autorizaciones</h5>
           <p style="display:inline; color:green;" class="card-text">Firmadas: </p> <p style="display:inline;"><?php echo count($firmadas)?> • </p> 
           <p style="display:inline; color:orange;" class="card-text">Pendientes: </p> <p style="display:inline;"><?php echo count($pendientes)?> • </p> 
           <p style="display:inline; color:red;" class="card-text">Rechazadas: </p> <p style="display:inline;"><?php echo count($rechazadas)?> </p> 
        </div>
    </div>
</div>