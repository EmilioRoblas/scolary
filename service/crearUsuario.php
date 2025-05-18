<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if(isset($_POST["nombre"]) && !empty($_POST["nombre"]) && !empty($_POST["correo"]) && !isset($_POST["rol"])){
    
   if (filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL)) {
    
   
   
   
        include 'crearContrasena.php'; 

        $contrasena = generarContrasena($_POST["nombre"]); //Genero la contraseña para el nuevo tutor legal.

        // Importo librerías de pgpmailer 
        // require '../includes/libs/src/PHPMailer.php';
        // require '../includes/libs/src/SMTP.php';
        // require '../includes/libs/src/Exception.php';

        // use PHPMailer\PHPMailer\PHPMailer; // Importa la clase PHPMailer
        // use PHPMailer\PHPMailer\Exception; // Importa la clase Exception para manejo de errores

        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        try {
            
        include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, 'tutor')");

        $stmt->execute([
            ':nombre'     => $_POST["nombre"],
            ':email'      => $_POST["correo"],
            ':password' => $contrasenaHash
        ]);

        
        header("Location: ../dashboardAdmin.php?mensaje=Tutor ".$_POST['nombre']." añadido correctamente.".$contrasena); // Mensaje de éxito al crear al tutor
        exit();
            
        } catch (PDOException $e) {
            echo "Error al insertar usuario: " . $e->getMessage();
        }


    }else{
        header("Location: ../dashboardAdmin.php?error=Formato no válido de email"); // Mensaje de error en caso de formato de mail no válido
        exit();

    }



}else if(isset($_POST["rol"])){
    
    if (filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL)) {
    
   
   
   
        include 'crearContrasena.php'; 


        if($_POST["rol"]== "admin"){

            $contrasena = "admin123";
        }else{
            $contrasena = generarContrasena($_POST["nombre"]); //Genero la contraseña para el nuevo tutor legal.
        }

        // Importo librerías de pgpmailer 
        // require '../includes/libs/src/PHPMailer.php';
        // require '../includes/libs/src/SMTP.php';
        // require '../includes/libs/src/Exception.php';

        // use PHPMailer\PHPMailer\PHPMailer; // Importa la clase PHPMailer
        // use PHPMailer\PHPMailer\Exception; // Importa la clase Exception para manejo de errores

        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        try {
            
        include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)");

        $stmt->execute([
            ':nombre'     => $_POST["nombre"],
            ':email'      => $_POST["correo"],
            ':password' => $contrasenaHash,
            ':rol'        => $_POST["rol"]
        ]);

        
        header("Location: ../dashboardAdmin.php?mensaje=".$_POST["rol"]." ".$_POST['nombre']." añadido correctamente.".$contrasena); // Mensaje de éxito al crear al tutor
        exit();
            
        } catch (PDOException $e) {
            echo "Error al insertar usuario: " . $e->getMessage();
        }

    }

}else{
    header("Location: ../dashboardAdmin.php?error=Ningún campo puede estar vacío"); // Mensaje de error en caso de envíar parámetros vacíos
    exit();
}