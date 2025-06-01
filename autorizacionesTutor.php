<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuarioRol'] != 'tutor') {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
include 'includes/navbarTutor.php';
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
    WHERE ap.estado = '$estado' AND al.id_tutor = :id_tutor");
$stmt->bindParam(':id_tutor', $_SESSION['usuarioId'], PDO::PARAM_INT);
$stmt->execute();
$autorizaciones = $stmt->fetchAll();

// Alumnos
$stmtAlumnos = $pdo ->prepare("SELECT * FROM alumnos WHERE id_tutor = :id_tutor");
$stmtAlumnos->bindParam(':id_tutor', $_SESSION['usuarioId'], PDO::PARAM_INT);
$stmtAlumnos->execute();
$alumnos = $stmtAlumnos->fetchAll();

// Profesores
$stmtProfesores = $pdo ->prepare("SELECT * FROM usuarios WHERE rol = 'profesor'");
$stmtProfesores->execute();
$profesores = $stmtProfesores->fetchAll();


?>
<main class="main-content">
<div class="container mt-4">
    <h1>Tus autorizaciones, <?php echo $_SESSION['usuario'] ?> 📃</h1>

    <?php if (isset($_GET['mensaje'])){ ?>
    <div class="alert alert-success"><?= $_GET['mensaje'] ?></div>
    <?php }?>
    <?php if (isset($_GET['error'])){ ?>
    <div class="alert alert-danger"><?= $_GET['error'] ?></div>
    <?php }?>
   
    <!-- Botón para abrir modal crear autorización -->
    <button type="button" class="btn botonCrear mt-3" data-bs-toggle="modal" data-bs-target="#crearAutorizacion">
    + Crear autorización
    </button>

    <div class="mb-3">
        <form action="autorizacionesTutor.php">
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
        </form>
        </div>
    </div>
    <?php }?>
    </div>
</div>
</div>
        <!-- Modal autorizaciones -->
<div class="modal fade" id="crearAutorizacion" tabindex="-1" aria-labelledby="crearAutorizacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="crearAutorizacionLabel">Crear Autorización</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <form action="service/crearAutorizacion.php" method="POST" id="formAutorizacion">
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-control" required>
                <option value="">Selecciona tipo</option>
                <option value="falta">Falta</option>
                <option value="otro">Otro</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alumno</label>
                <select name="id_alumno" class="form-control">
                <option value="">Selecciona un alumno</option>
                <?php foreach ($alumnos as $alumno): ?>
                    <option value="<?= $alumno['id'] ?>"><?= $alumno['nombre'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Profesor</label>
                <select name="id_profesor" class="form-control">
                <option value="">Selecciona un profesor</option>
                <?php foreach ($profesores as $profesor): ?>
                    <option value="<?= $profesor['id'] ?>"><?= $profesor['nombre'] ?></option>
                <?php endforeach; ?>
                </select>
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
</main>
<?php include 'includes/footer.php';?>