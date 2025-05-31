<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../conexion.php'; // Ajusta a tu archivo de conexión PDO

if (!isset($_GET['id'])) {
    die("Falta el parámetro 'id'");
}

$idAutorizacionAlumno = $_GET['id'];

// Obtener datos desde la base de datos
$stmt = $pdo->prepare(
    "SELECT 
    a.id AS id_autorizacion,
    a.titulo,
    a.tipo,
    a.descripcion,
    a.fecha_creacion,
    a.estado AS estado_autorizacion,
    al.nombre AS nombre_alumno,
    aa.estado AS estado_firma,
    aa.id AS autorizacion_alumno,
    fa.firma AS firma
FROM autorizaciones a
JOIN autorizaciones_alumnos aa ON a.id = aa.id_autorizacion
JOIN alumnos al ON aa.id_alumno = al.id
JOIN firmas_autorizacion fa ON fa.id_autorizacion_alumno = aa.id
WHERE aa.id = :id");
$stmt->execute(['id' => $idAutorizacionAlumno]);
$autorizacion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$autorizacion) {
    die("No se encontró la autorización.");
}

// Crear PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('Sistema Escolar');
$pdf->SetAuthor('Centro Educativo');
$pdf->SetTitle('Autorización Firmada');
$pdf->SetMargins(15, 30, 15);
$pdf->AddPage();

// Cabecera institucional
$logoPath = __DIR__ . '/view/images/logoescuela.png';
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 15, 10, 20, '', 'PNG');
}
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetXY(40, 10);
$pdf->Cell(0, 10, 'Instituto de Educación Secundaria "Ejemplo"', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(40, 18);
$pdf->Cell(0, 10, 'Dirección: Calle Falsa 123 - Ciudad - Provincia', 0, 1, 'L');
$pdf->Cell(0, 5, 'Tel: 999-999-999 | Email: contacto@institutoejemplo.edu', 0, 1, 'L');
$pdf->Ln(10);

// Contenido
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, "Título: " . $autorizacion['titulo'], '', 0, 'L', true);
$pdf->Write(0, "Tipo: " . ucfirst($autorizacion['tipo']), '', 0, 'L', true);
$pdf->Write(0, "Alumno: " . $autorizacion['nombre_alumno'], '', 0, 'L', true);
$pdf->Write(0, "Fecha de creación: " . $autorizacion['fecha_creacion'], '', 0, 'L', true);
$pdf->Ln(3);

$pdf->MultiCell(0, 0, "Descripción:\n" . $autorizacion['descripcion'], 0, 'L', false);
$pdf->Ln(10);

// Firma
$firmaPath = __DIR__ . '/../' . $autorizacion['firma']; 
if (file_exists($firmaPath)) {
    $pdf->Write(0, "Firma del tutor:", '', 0, 'L', true);
    $pdf->Image($firmaPath, $pdf->GetX(), $pdf->GetY(), 40, 0, 'PNG');
    $pdf->Ln(20);
} else {
    $pdf->Write(0, "Firma no disponible.", '', 0, 'L', true);
}

// Descargar el PDF
$pdf->Output('autorizacion_' . $autorizacion['nombre_alumno'] . '.pdf', 'D');
