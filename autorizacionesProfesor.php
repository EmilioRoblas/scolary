<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuarioRol'] != 'profesor') {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
include 'includes/navbarProfesor.php';
include 'conexion.php';

  if(isset($_GET["mostrar"])){
      if ($_GET["mostrar"] == "pendiente") {
        $estado = "pendiente";
      }else if($_GET["mostrar"] == "confirmado"){
        $estado = "confirmado";
      }else{
        $estado = "archivado";
      }
    }else{
      $estado = "pendiente";
    }

$stmt = $pdo->prepare("
    SELECT 
    a.titulo AS titulo,
    a.descripcion AS descripcion,
    a.tipo AS tipo,
    a.fecha_creacion AS fecha,
    ap.id_alumno,
    ap.id AS idAutorizacion,
    al.nombre AS nombreAlumno,
    us.nombre AS nombreTutor
    FROM autorizaciones a
    JOIN autorizaciones_profesor ap ON a.id = ap.id_autorizacion
    JOIN alumnos al ON ap.id_alumno = al.id
    JOIN usuarios us ON us.id = al.id_tutor
    WHERE ap.estado = '$estado' AND a.id_profesor = :id_profesor
");
$stmt->bindParam(':id_profesor', $_SESSION['usuarioId'], PDO::PARAM_INT);
$stmt->execute();
$autorizaciones = $stmt->fetchAll();

?>

<div class="container mt-4">
    <img src="<?php echo substr($_SESSION['usuarioAvatar'],3); ?>" alt="Avatar del usuario" width="100">
    <h1>Tus autorizaciones, <?php echo $_SESSION['usuario'] ?> 📃</h1>

    <?php if (isset($_GET['mensaje'])){ ?>
    <div class="alert alert-success"><?= $_GET['mensaje'] ?></div>
    <?php }?>
    <?php if (isset($_GET['error'])){ ?>
    <div class="alert alert-danger"><?= $_GET['error'] ?></div>
    <?php }?>
   
    <div class="mb-3">
        <form action="autorizacionesProfesor.php">
          <label for="orden" class="form-label"></label>
            <select name="mostrar" id="mostrar" class="form-select" onchange="this.form.submit()">
              <option value="">Mostrar...</option>
              <option value="pendiente">Pendientes</option>
              <option value="confirmado">Confirmadas</option>
              <option value="archivado">Archivadas</option>
            </select>
        </form>
    </div>

    <div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
        <?php foreach ($autorizaciones as $index => $autorizacion) {?>
        <h2 class="accordion-header" id="flush-heading<?= $index ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $index ?>" aria-expanded="false" aria-controls="flush-collapse<?= $index ?>">
            Autorización: <?php echo $autorizacion['titulo']?> • Alumno: <?php echo $autorizacion['nombreAlumno']?> • Tutor:  <?php echo $autorizacion['nombreTutor']?>
        </button>
        </h2>
        <div id="flush-collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $index ?>" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">            
        <?php echo $autorizacion['descripcion']?></div>
        <hr class="my-3 border-top border-1 border-secondary-subtle">
       <form action="service/gestionAutorizacion.php" method="post">
        <?php if($estado == 'pendiente') {?>
        <button type="submit" class="btn btn-success mt-2">
            <input type="hidden" name="confirmar" id="confirmar" value="confirmado">
            <input type="hidden" name="idAutorizacion" id="idAutorizacion" value="<?php echo $autorizacion['idAutorizacion']?>">
            <i class="bi bi-check-circle"></i>
            Confirmar
        </button>
        <?php }?>
        <?php if($estado == 'pendiente' || $estado == 'confirmado') {?>
        <button type="submit" class="btn btn-primary mt-2">
            <input type="hidden" name="archivar" id="archivar" value="archivado">
            <input type="hidden" name="idAutorizacion" id="idAutorizacion" value="<?php echo $autorizacion['idAutorizacion']?>">
            <i class="bi bi-archive"></i>
            Archivar
        </button>
        <?php }?>
        <?php if($estado == 'archivado') {?>
        <button type="submit" class="btn btn-primary mt-2">
            <input type="hidden" name="desarchivar" id="archivar" value="confirmado">
            <input type="hidden" name="idAutorizacion" id="idAutorizacion" value="<?php echo $autorizacion['idAutorizacion']?>">
            <i class="bi bi-box-arrow-up"></i>
            Desarchivar
        </button>
        <?php }?>
        <hr class="my-3 border-top border-1 border-secondary-subtle">
        </form>
        </div>
    </div>
    <?php }?>
    </div>
</div>
</div>
<?php include 'includes/footer.php';?>

 