<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputUsuario = $_POST['usuario'];
    $inputPassword = $_POST['clave'];
    

     if (empty($inputUsuario) || empty($inputPassword)){
        // Redireccionar a la página de login con un mensaje de error
        header("Location: ../login.php?error=Debes introducir un usuario y contraseña");
        exit();
    } else{       
        include '../conexion.php';

        $stmtUsuario = $pdo ->prepare("SELECT * FROM usuarios WHERE nombre = :nombre");
        $stmtUsuario->execute([':nombre' => $inputUsuario]);
        $usuario = $stmtUsuario->fetch();
        
        if ($usuario) {
            // Usuario encontrado, ahora puedes verificar la contraseña
            if (password_verify($inputPassword, $usuario['password'])) {
                // Contraseña correcta, iniciar sesión
                session_start();
                $_SESSION['usuario'] = $usuario["nombre"];
                $_SESSION['usuarioId'] = $usuario["id"];
                $_SESSION['usuarioRol'] = $usuario["rol"];
                $_SESSION['usuarioEmail'] = $usuario["email"];
                $_SESSION['usuarioAvatar'] = $usuario["avatar"];

                if($usuario["rol"] == "admin"){
                    header("Location: ../dashboardAdmin.php"); 
                    exit();
                }else if($usuario["rol"] == "tutor"){
                    header("Location: ../dashboardTutor.php"); 
                    exit();
                }else{
                    header("Location: ../dashboardProfesor.php"); 
                    exit();
                }
               
            } else {
                // Contraseña incorrecta
                header("Location: ../login.php?error=Contraseña incorrecta");
                exit();
            }
        } else {
            // Usuario no existe
            header("Location: ../login.php?error=Usuario no encontrado");
            exit();
        }
      
    }
    

}else{
    header("Location: ../login.php");
    exit();
}