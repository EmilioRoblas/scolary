<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
include 'includes/navbarProfesor.php';
include 'includes/footer.php';
include 'conexion.php';

$stmtFirmadas = $pdo ->query('SELECT * FROM autorizaciones WHERE estado = "firmada"');
$firmadas = $stmtFirmadas->fetchAll();

$stmtPendientes = $pdo ->query('SELECT * FROM autorizaciones WHERE estado = "pendiente"');
$pendientes = $stmtPendientes->fetchAll();

$stmtRechazadas = $pdo ->query('SELECT * FROM autorizaciones WHERE estado = "rechazada"');
$rechazadas = $stmtRechazadas->fetchAll();

$stmtCaducadas = $pdo ->query('SELECT * FROM autorizaciones WHERE estado = "caducada"');
$caducadas = $stmtCaducadas->fetchAll();

?>
<div class="container mt-4">
    <img src="<?php echo substr($_SESSION['usuarioAvatar'],3); ?>" alt="Avatar del usuario" width="100">
    <h1>Bienvenido a Scolary, <?php echo $_SESSION['usuario'] ?> 👋</h1>
    <p>Panel de profesorado</p>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Autorizaciones</h5>
           <p style="display:inline; color:green;" class="card-text">Firmadas: </p> <p style="display:inline;"><?php echo count($firmadas)?> • </p> 
           <p style="display:inline; color:orange;" class="card-text">Pendientes: </p> <p style="display:inline;"><?php echo count($pendientes)?> • </p> 
           <p style="display:inline; color:red;" class="card-text">Rechazadas: </p> <p style="display:inline;"><?php echo count($rechazadas)?> • </p> 
           <p style="display:inline; color:grey;" class="card-text">Caducadas: </p> <p style="display:inline;"><?php echo count($caducadas)?></p>
        </div>
    </div>
</div>

 