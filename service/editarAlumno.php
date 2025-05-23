<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

echo "no entro";

if( isset($_POST["nombre"]) && !empty($_POST["nombre"]) && isset($_POST["idAlumnoEditar"]) && !empty($_POST["idAlumnoEditar"]) && isset($_POST["tutor"]) && !empty($_POST["tutor"]) && isset($_POST["idGrupo"]) && !empty($_POST["idGrupo"])){

    include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos
    

    try {
        $stmt = $pdo->prepare("UPDATE alumnos 
                       SET nombre = :nombre, 
                           id_tutor = :id_tutor, 
                           id_grupo = :id_grupo 
                       WHERE id = :id");

        $stmt->execute([
            ':nombre'    => $_POST["nombre"],
            ':id_tutor'  => $_POST["tutor"],
            ':id_grupo'  => $_POST["idGrupo"],
            ':id'        => $_POST["idAlumnoEditar"]
        ]);

        header("Location: ../dashboardAdmin.php?mensaje=Alumno ".$_POST['nombre']." editado correctamente"); // Mensaje de error en caso de envíar parámetros vacíos
         exit();
    } catch (\Throwable $th) {
        echo "Error al insertar alumno: " . $e->getMessage();
    }
}else{
    header("Location: ../dashboardAdmin.php?error=Ningún campo puede estar vacío"); // Mensaje de error en caso de envíar parámetros vacíos
    exit();
}