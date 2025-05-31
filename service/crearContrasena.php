<?php
function generarContrasena($nombreCompleto) {
    $nombreCompleto = trim(preg_replace('/\s+/', ' ', strtolower($nombreCompleto)));
    $partes = explode(' ', $nombreCompleto);

    if (count($partes) < 2) {
        header("Location: ../dashboardAdmin.php?error=Se necesita un nombre con apellido separado de un espacio"); // Mensaje de error en caso de formato de mail no válido
        exit();
    }

    $nombre = $partes[0];
    $apellido = $partes[1];

    $nombre3 = substr(str_pad($nombre, 3, 'x'), 0, 3);
    $apellido3 = substr(str_pad($apellido, 3, 'x'), 0, 3);

    $nombre3 = ucfirst($nombre3);

    $especiales = '!@#$%^&*';
    $especial = $especiales[random_int(0, strlen($especiales) - 1)];

    $numero = random_int(10, 99);
    
    $contrasena = $nombre3 . $apellido3 . $especial . $numero;

    return $contrasena;
}