<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
	

	$sig_string=$_POST['signature'];
	$nama_file="firma".date("his").".png";
	$ruta = "../../view/firmas/";
	$path = $ruta.$nama_file;
	file_put_contents($path, file_get_contents($sig_string));
	if(file_exists($nama_file)){
		echo "<p>File Signature berhasil disimpan - ".$nama_file."</p>";
		echo "<p style='border:solid 1px teal;width:355px;height:110px;'><img src='".$nama_file."'></p>";
	}

	echo $_SESSION['id_autorizacion_alumno'];
	include '../../conexion.php';
	// Recalculo ruta original desde la raiz, es la que quiero guardar en la base de datos
	$ruta = "view/firmas/";
	$path = $ruta.$nama_file;
	echo $path;
	$stmtFirmas = $pdo->prepare("INSERT INTO firmas_autorizacion (id_autorizacion, id_autorizacion_alumno, firmado_por, firma) VALUES (?, ?, ?, ?)");
    $stmtFirmas->execute([$_SESSION['id_autorizacion'], $_SESSION['id_autorizacion_alumno'], $_SESSION['usuarioRol'], $path]);

	$stmtUpdateEstado = $pdo ->prepare("UPDATE autorizaciones_alumnos SET estado = 'firmada' WHERE id = :id");
	$stmtUpdateEstado->bindParam(':id', $_SESSION['id_autorizacion_alumno'], PDO::PARAM_INT);
	$stmtUpdateEstado->execute();

	header("Location: ../../dashboardTutor.php");
    exit();
