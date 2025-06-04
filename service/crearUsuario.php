<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}


        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require '../vendor/autoload.php';

if(isset($_POST["nombre"]) && !empty($_POST["nombre"]) && !empty($_POST["correo"]) && !isset($_POST["rol"])){
    echo "hola antes mail";
   if (filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL)) {
    
   
     
   
        include 'crearContrasena.php'; 

        $contrasena = generarContrasena($_POST["nombre"]); //Genero la contraseña para el nuevo tutor legal.

        

        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        try {
            
        include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, 'tutor')");

        $stmt->execute([
            ':nombre'     => $_POST["nombre"],
            ':email'      => $_POST["correo"],
            ':password' => $contrasenaHash
        ]);

        //Mandar correo

        

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'kuroshakismurf@gmail.com';
            $mail->Password = 'nwmj dral pdmb jmbm';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->setFrom('centroeducativo@edu.gva.es', 'CentroEducativo');
            $mail->addAddress($_POST['correo'], $_POST["nombre"]);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Creación de cuenta en Scolary';
            $mail->Body    = "<b>¡Hola ".$_POST['nombre']."</b><br><br><p>Se le ha dado de alta en la aplicación web de Scolary, donde podrá gestionar
            sus autorizaciones de sus hijos/hijas respecto al centro educativo.</p><br><br><p>Su usuario es ".$_POST['nombre']." y su contraseña es $contrasena</p>";
            $mail->AltBody = 'Este es el contenido alternativo (texto plano)';

            $mail->send();
            
        } catch (Exception $e) {
            echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        }


        header("Location: ../dashboardAdmin.php?mensaje=Tutor ".$_POST['nombre']." añadido correctamente.".$contrasena); // Mensaje de éxito al crear al tutor
        exit();
            
        } catch (PDOException $e) {
            echo "Error al insertar usuario: " . $e->getMessage();
        }


    }else{
        echo "hola";
        header("Location: ../dashboardAdmin.php?error=Formato no válido de email"); // Mensaje de error en caso de formato de mail no válido
        exit();

    }



}else if(isset($_POST["rol"])){
    
    echo "hola rol";
    if (filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL)) {
     
        include 'crearContrasena.php'; 

        if($_POST["rol"]== "admin"){

            $contrasena = "admin123";
        }else{
            $contrasena = generarContrasena($_POST["nombre"]); //Genero la contraseña para el nuevo tutor legal.
        }

        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $nombreOriginal = $_FILES['avatar']['name'];
            $tmp = $_FILES['avatar']['tmp_name'];
            $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);

            
            // Validar extensión opcionalmente (solo imágenes)
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($ext), $extensionesPermitidas)) {
                // header("Location: ../dashboardAdmin.php?error=Extensión no permitida"); 
                // exit();
            }

            // Crear nombre único
            $nuevoNombre = uniqid('avatar_', true) . '.' . $ext;

            // Ruta donde guardar (asegúrate de que la carpeta tenga permisos de escritura)
            $rutaDestino = '../view/avatars/' . $nuevoNombre;
        

            try {
                
            include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos

                if(move_uploaded_file($tmp, $rutaDestino)){
                    
                
                    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, avatar) VALUES (:nombre, :email, :password, :rol, :avatar)");

                    $stmt->execute([
                        ':nombre'     => $_POST["nombre"],
                        ':email'      => $_POST["correo"],
                        ':password' => $contrasenaHash,
                        ':rol'        => $_POST["rol"],
                        ':avatar'      => $rutaDestino
                    ]);
                    echo "entro en usuario con rol";
                    //Mandar correo
                     $mail = new PHPMailer(true);

                try {
                    // Configuración del servidor
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; 
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kuroshakismurf@gmail.com';
                    $mail->Password = 'nwmj dral pdmb jmbm';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Remitente y destinatario
                    $mail->setFrom('centroeducativo@edu.gva.es', 'CentroEducativo');
                    $mail->addAddress($_POST['correo'], $_POST["nombre"]);

                    // Contenido
                    $mail->isHTML(true);
                    $mail->Subject = 'Creación de cuenta en Scolary';
                    $mail->Body    = "<b>¡Hola ".$_POST['nombre']."</b><br><br><p>Se le ha dado de alta en la aplicación web de Scolary, donde podrá gestionar
                    sus autorizaciones de sus hijos/hijas respecto al centro educativo.</p><br><br><p>Su usuario es ".$_POST['nombre']." y su contraseña es $contrasena</p>";
                    $mail->AltBody = 'Este es el contenido alternativo (texto plano)';

                    $mail->send();
                    echo 'Correo enviado correctamente';
                } catch (Exception $e) {
                    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
                }

                     header("Location: ../dashboardAdmin.php?mensaje=".$_POST["rol"]." ".$_POST['nombre']." añadido correctamente.".$contrasena); // Mensaje de éxito al crear al tutor
                     exit();
                }else{
                    // header("Location: ../dashboardAdmin.php?error=Error al guardar imagen"); 
                    // exit();
                    
                }
            } catch (PDOException $e) {
                echo "Error al insertar usuario: " . $e->getMessage();
            }

        }else{

            include '../conexion.php';

            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)");

                    $stmt->execute([
                        ':nombre'     => $_POST["nombre"],
                        ':email'      => $_POST["correo"],
                        ':password' => $contrasenaHash,
                        ':rol'        => $_POST["rol"]
                    ]);

                $mail = new PHPMailer(true);

                try {
                    // Configuración del servidor
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; 
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kuroshakismurf@gmail.com';
                    $mail->Password = 'nwmj dral pdmb jmbm';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Remitente y destinatario
                    $mail->setFrom('centroeducativo@edu.gva.es', 'CentroEducativo');
                    $mail->addAddress($_POST['correo'], $_POST["nombre"]);

                    // Contenido
                    $mail->isHTML(true);
                    $mail->Subject = 'Creación de cuenta en Scolary';
                    $mail->Body    = "<b>¡Hola ".$_POST['nombre']."</b><br><br><p>Se le ha dado de alta en la aplicación web de Scolary, donde podrá gestionar
                    sus autorizaciones de sus hijos/hijas respecto al centro educativo.</p><br><br><p>Su usuario es ".$_POST['nombre']." y su contraseña es $contrasena</p>";
                    $mail->AltBody = 'Este es el contenido alternativo (texto plano)';

                    $mail->send();

                    header("Location: ../dashboardAdmin.php?mensaje=".$_POST["rol"]." ".$_POST['nombre']." añadido correctamente."); 
                    exit();
                } catch (Exception $e) {
                    header("Location: ../dashboardAdmin.php?error=No se ha podido envíar el correo al crear el usuario: $mail->ErrorInfo"); // Mensaje de error en caso de envíar parámetros vacíos
                    exit();
                    
                }
        }
    }else{
        header("Location: ../dashboardAdmin.php?error=Introduce un correo válido, por favor"); // Mensaje de error en caso de envíar parámetros vacíos
        exit();
    }

}else{
    header("Location: ../dashboardAdmin.php?error=Ningún campo puede estar vacío"); // Mensaje de error en caso de envíar parámetros vacíos
    exit();
}