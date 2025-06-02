<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuarioRol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
$_SESSION['erroresInsert'] = [];
$_SESSION['mensajeInsert'] = [];

if (isset($_FILES['csv']) && $_FILES['csv']['error'] == 0) {
    $file = $_FILES['csv']['tmp_name'];
    $leerArchivo = fopen($file, 'r');

    include '../conexion.php';

    if ($leerArchivo) {
        $linea = 0;
        while (($data = fgetcsv($leerArchivo, 1000, ",")) !== FALSE) {
            $linea++;

            // Saltar encabezado si lo tiene
            if ($linea == 1 && strtolower($data[0]) == 'nombre') continue;

            $nombre = trim($data[0]);
            $id_tutor = (int)$data[1];
            $id_grupo = (int)$data[2];

            // Validar tutor
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE id = ?");
            $stmt->execute([$id_tutor]);
            if ($stmt->fetchColumn() == 0) {
                $_SESSION['erroresInsert'][] = "Línea $linea: Tutor con ID $id_tutor no existe.<br>";
                echo "tutor no existe";
                continue;
            }

            // Validar grupo
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM grupo WHERE id = ?");
            $stmt->execute([$id_grupo]);
            if ($stmt->fetchColumn() == 0) {
                $_SESSION['erroresInsert'][] = "Línea $linea: Grupo con ID $id_grupo no existe.<br>";
                echo "grupo no existe";
                continue;
            }

            // Insertar alumno
            $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, id_tutor, id_grupo) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $id_tutor, $id_grupo]);

            $_SESSION['mensajeInsert'][] = "Línea $linea: Alumno '$nombre' insertado correctamente.<br>";
             
            
             
           
        }
        fclose($leerArchivo);
        header("Location: ../dashboardAdmin.php");
        exit();

        
    } else {
        header("Location: ../dashboardAdmin.php?error=No se pudo abrir el archivo.");
        exit();
    }
} else {
    header("Location: ../dashboardAdmin.php?error=No se pudo subir el archivo.");
    exit();
}