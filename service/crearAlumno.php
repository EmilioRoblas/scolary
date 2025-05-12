<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if(isset($_POST["nombre"]) && !empty($_POST["nombre"]) && !empty($_POST["tutor"]) && !empty($_POST["idGrupo"])){
   
    echo $_POST["nombre"];
    echo $_POST["tutor"];
    echo $_POST["idGrupo"];

    try {
            
        include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos

        $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, id_tutor, id_grupo) VALUES (:nombre, :id_tutor, :id_grupo)");

        $stmt->execute([
            ':nombre'     => $_POST["nombre"],
            ':id_tutor'      => $_POST["tutor"],
            ':id_grupo' => $_POST["idGrupo"]
        ]);

    
        
         header("Location: ../dashboardAdmin.php?mensaje=Alumno ".$_POST['nombre']." añadido correctamente"); // Mensaje de error en caso de envíar parámetros vacíos
         exit();
            
        } catch (PDOException $e) {
            echo "Error al insertar alumno: " . $e->getMessage();
        }
}else{
    header("Location: ../dashboardAdmin.php?error=Ningún campo puede estar vacío"); // Mensaje de error en caso de envíar parámetros vacíos
    exit();
}