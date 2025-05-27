<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

include '../conexion.php';


if (empty($_POST['titulo']) || empty($_POST['descripcion']) || empty($_POST['tipo'])) {
    header("Location: ../dashboardProfesor.php?error=Rellene todos los campos, por favor");
    exit();
}

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo']; 
$idGrupo = $_POST['id_grupo'] ?? null;
$idAlumno = $_POST['id_alumno'] ?? null;
$target = $_POST['asignar_a'];

if ($target === 'grupo' && empty($idGrupo)) {
    header("Location: ../dashboardProfesor.php?error=Se requiere un grupo para una autorización de tipo grupo.");
    exit();
}
if ($target === 'alumno' && empty($idAlumno)) {
    header("Location: ../dashboardProfesor.php?error=Se requiere un alumno para una autorización de tipo alumno.");
    exit();
}

try {
    
    $stmt = $pdo->prepare("INSERT INTO autorizaciones (titulo, descripcion, tipo, id_profesor) VALUES (?, ?, ?,?)");
    $stmt->execute([$titulo, $descripcion, $tipo, $_SESSION['usuarioId']]);

    $idAutorizacion = $pdo->lastInsertId();

    
    if ($target === 'grupo') {
        
        $stmtAlumnos = $pdo->prepare("SELECT id FROM alumnos WHERE id_grupo = ?");
        $stmtAlumnos->execute([$idGrupo]);
        $alumnos = $stmtAlumnos->fetchAll();

        
        $stmtInsert = $pdo->prepare("INSERT INTO autorizaciones_alumnos (id_alumno, id_autorizacion) VALUES (?, ?)");
        foreach ($alumnos as $alumno) {
            $stmtInsert->execute([$alumno['id'], $idAutorizacion]);
        }
    } elseif ($target === 'alumno') {
        
        $stmtInsert = $pdo->prepare("INSERT INTO autorizaciones_alumnos (id_alumno, id_autorizacion) VALUES (?, ?)");
        $stmtInsert->execute([$idAlumno, $idAutorizacion]);
    }

    
    header("Location: ../dashboardProfesor.php?mensaje=Autorización creada con éxito");
    exit();

} catch (PDOException $e) {
    echo "Error al crear autorización: " . $e->getMessage();
}
