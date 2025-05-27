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

$stmtFirmadas = $pdo ->query('SELECT * FROM autorizaciones_alumnos WHERE estado = "firmada"');
$firmadas = $stmtFirmadas->fetchAll();

$stmtPendientes = $pdo ->query('SELECT * FROM autorizaciones_alumnos WHERE estado = "pendiente"');
$pendientes = $stmtPendientes->fetchAll();

$stmtRechazadas = $pdo ->query('SELECT * FROM autorizaciones_alumnos WHERE estado = "rechazada"');
$rechazadas = $stmtRechazadas->fetchAll();

$stmtCaducadas = $pdo ->query('SELECT * FROM autorizaciones_alumnos WHERE estado = "caducada"');
$caducadas = $stmtCaducadas->fetchAll();

$stmtGrupos = $pdo ->query('SELECT * FROM grupo');
$grupos = $stmtGrupos->fetchAll();

$stmtAlumnos = $pdo ->query('SELECT * FROM alumnos');
$alumnos = $stmtAlumnos->fetchAll();

?>
<div class="container mt-4">
    <img src="<?php echo substr($_SESSION['usuarioAvatar'],3); ?>" alt="Avatar del usuario" width="100">
    <h1>Bienvenido a Scolary, <?php echo $_SESSION['usuario'] ?> 👋</h1>
    <p>Panel de profesorado</p>

    <?php if (isset($_GET['mensaje'])){ ?>
    <div class="alert alert-success"><?= $_GET['mensaje'] ?></div>
    <?php }?>
    <?php if (isset($_GET['error'])){ ?>
    <div class="alert alert-danger"><?= $_GET['error'] ?></div>
    <?php }?>

    <div class="card mt-4">
        
        <div class="card-body">
            <h5 class="card-title">Autorizaciones</h5>
           <p style="display:inline; color:green;" class="card-text">Firmadas: </p> <p style="display:inline;"><?php echo count($firmadas)?> • </p> 
           <p style="display:inline; color:orange;" class="card-text">Pendientes: </p> <p style="display:inline;"><?php echo count($pendientes)?> • </p> 
           <p style="display:inline; color:red;" class="card-text">Rechazadas: </p> <p style="display:inline;"><?php echo count($rechazadas)?> </p> 
        </div>
    </div>

    <!-- Botón para abrir modal crear autorización -->
    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearAutorizacion">
    + Crear autorización
    </button>

    <!-- Modal Crear Autorización -->
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
                <option value="excursion">Excursión</option>
                <option value="otro">Otro</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Asignar a:</label>
                <select name="asignar_a" class="form-control" id="selectorAsignacion" required>
                <option value="">Selecciona opción</option>
                <option value="grupo">Grupo completo</option>
                <option value="alumno">Alumno específico</option>
                </select>
            </div>

            <!-- Selector de grupo -->
            <div class="mb-3 d-none" id="grupoSelector">
                <label class="form-label">Grupo</label>
                <select name="id_grupo" class="form-control">
                <option value="">Selecciona un grupo</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>"><?= $grupo['nombre'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>

            <!-- Selector de alumno -->
            <div class="mb-3 d-none" id="alumnoSelector">
                <label class="form-label">Alumno</label>
                <select name="id_alumno" class="form-control">
                <option value="">Selecciona un alumno</option>
                <?php foreach ($alumnos as $alumno): ?>
                    <option value="<?= $alumno['id'] ?>"><?= $alumno['nombre'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>

            <!-- ID profesor oculto (puede venir de sesión) -->
            <input type="hidden" name="id_profesor" value="<?= $profesor_id ?>">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    </div>

</div>
<script src="js/dialogCrearAutorizacion.js"></script>
<?php include 'includes/footer.php'; ?>

 