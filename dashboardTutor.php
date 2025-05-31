<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuarioRol'] != 'tutor') {
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

$stmtAutorizacionesPendientes = $pdo ->query("SELECT 
    a.id AS id_autorizacion,
    a.titulo,
    a.tipo,
    a.descripcion,
    a.fecha_creacion,
    a.estado AS estado_autorizacion,
    al.nombre AS nombre_alumno,
    aa.estado AS estado_firma,
    aa.id AS autorizacion_alumno
FROM autorizaciones a
JOIN autorizaciones_alumnos aa ON a.id = aa.id_autorizacion
JOIN alumnos al ON aa.id_alumno = al.id
WHERE al.id_tutor = ".$_SESSION['usuarioId']." AND aa.estado = 'pendiente';");
$autorizacionesPendientes = $stmtAutorizacionesPendientes->fetchAll();

$stmtAutorizacionesFirmadas = $pdo ->query("SELECT 
    a.id AS id_autorizacion,
    a.titulo,
    a.tipo,
    a.descripcion,
    a.fecha_creacion,
    a.estado AS estado_autorizacion,
    al.nombre AS nombre_alumno,
    aa.estado AS estado_firma,
    aa.id AS autorizacion_alumno,
    fa.firma AS firma
FROM autorizaciones a
JOIN autorizaciones_alumnos aa ON a.id = aa.id_autorizacion
JOIN alumnos al ON aa.id_alumno = al.id
JOIN firmas_autorizacion fa ON fa.id_autorizacion_alumno = aa.id
WHERE al.id_tutor = ".$_SESSION['usuarioId']." AND aa.estado = 'firmada';");
$autorizacionesFirmadas = $stmtAutorizacionesFirmadas->fetchAll();

?>

<div class="container mt-4">
    
    <h1>Bienvenido a Scolary, <?php echo $_SESSION['usuario'] ?> 👋</h1>
    <h3>Tus autorizaciones pendientes</h3><br>
    <?php if(count($autorizacionesPendientes) == 0) {?>
        <h7 class="card-text" >¡No tienes autorizaciones pendientes! - 👍<h7/>
    <?php }?>
    <?php foreach ($autorizacionesPendientes as $autorizacion) { 
        $_SESSION['id_autorizacion_alumno'] = $autorizacion['autorizacion_alumno'];
        $_SESSION['id_autorizacion'] = $autorizacion['id_autorizacion'];
    
    ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            
            <h6 class="text-muted mb-1"><?php echo strtoupper($autorizacion['tipo']) ?> - <?php echo $autorizacion['fecha_creacion'] ?> 
            - Referencial al alumno: <?php echo $autorizacion['nombre_alumno'] ?> - ID Autorización de alumno: <?php echo $autorizacion['autorizacion_alumno']?></h6>
            <hr class="my-3 border-top border-1 border-secondary-subtle">
            
            <h5 class="card-title"><?php echo $autorizacion['titulo'] ?></h5>
            <hr class="my-3 border-top border-1 border-secondary-subtle">
         
            <p class="card-text"> <?php echo $autorizacion['descripcion'] ?> </p>
            <hr class="my-3 border-top border-1 border-secondary-subtle">
            
         <?php include 'service/firma/firma.php'?> 
            
        </div>
    </div>
    <?php }?>
    <br><br>
    <h3>Tus autorizaciones firmadas</h3><br>
    <?php foreach ($autorizacionesFirmadas as $autorizacion) { 
        
    ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            
            <h6 class="text-muted mb-1"><?php echo strtoupper($autorizacion['tipo']) ?> - <?php echo $autorizacion['fecha_creacion'] ?> 
            - Referencial al alumno: <?php echo $autorizacion['nombre_alumno'] ?> - ID Autorización de alumno: <?php echo $autorizacion['autorizacion_alumno']?></h6>
            <hr class="my-3 border-top border-1 border-secondary-subtle">
            
            <h5 class="card-title"><?php echo $autorizacion['titulo'] ?></h5>
            <hr class="my-3 border-top border-1 border-secondary-subtle">
         
            <p class="card-text"> <?php echo $autorizacion['descripcion'] ?> </p>
            <hr class="my-3 border-top border-1 border-secondary-subtle">
 
            <img src="<?php echo $autorizacion['firma']?>" alt="firma">
            <hr class="my-3 border-top border-1 border-secondary-subtle">

            <a class="text-decoration-none" href="service/generarPdf.php?id=<?php echo $autorizacion['autorizacion_alumno']; ?>" target="_blank" title="Descargar PDF">
            Descargar autorización pdf - 
            <img src="view/images/pdf-icon.png" alt="Descargar PDF" width="32" height="32">
            </a>
        </div>
    </div>
    <?php }?>
</div>

<?php include 'includes/footer.php'; ?>