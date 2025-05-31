<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

include '../conexion.php';
if(isset($_POST['confirmar'])){

    $stmtUpdateEstado = $pdo ->prepare("UPDATE autorizaciones_profesor SET estado = 'confirmado' WHERE id = :id");
    $stmtUpdateEstado->bindParam(':id', $_POST['idAutorizacion'], PDO::PARAM_INT);
	$stmtUpdateEstado->execute();
    header("Location: ../autorizacionesProfesor.php?mensaje=Autorización confirmada");
    exit();

}else if(isset($_POST['archivar'])){

    $stmtUpdateEstado = $pdo ->prepare("UPDATE autorizaciones_profesor SET estado = 'archivado' WHERE id = :id");
    $stmtUpdateEstado->bindParam(':id', $_POST['idAutorizacion'], PDO::PARAM_INT);
	$stmtUpdateEstado->execute();
    header("Location: ../autorizacionesProfesor.php?mensaje=Autorización archivada");
    exit();
}else{

    $stmtUpdateEstado = $pdo ->prepare("UPDATE autorizaciones_profesor SET estado = 'confirmado' WHERE id = :id");
    $stmtUpdateEstado->bindParam(':id', $_POST['idAutorizacion'], PDO::PARAM_INT);
	$stmtUpdateEstado->execute();
    header("Location: ../autorizacionesProfesor.php?mensaje=Autorización desarchivada");
    exit();

}

