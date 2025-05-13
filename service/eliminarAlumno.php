<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["idAlumno"])) {
    
    if (!empty($_POST['idAlumno']) && is_numeric($_POST['idAlumno'])) {
        $idAlumno = (int)$_POST['idAlumno'];

        try {
            include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos

            $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = :id");
            $stmt->bindParam(':id', $idAlumno, PDO::PARAM_INT);
            $stmt->execute();

            // Opcional: redirige de vuelta a la lista con mensaje
            header("Location: ../alumnos.php?mensaje=Alumno eliminado");
            exit();
        } catch (PDOException $e) {
            echo "Error al eliminar: " . $e->getMessage();
        }
    } else {
        echo "ID inválido.";
    }

} else {
    echo "Acceso no permitido.";
}