<?php
function generarContrasena($nombreCompleto){

     $nombreCompleto = trim(preg_replace('/\s+/', ' ', strtolower($nombreCompleto)));

    // Separar las palabras
    $partes = explode(' ', $nombreCompleto);

    // Verificar que al menos hay dos palabras (nombre y apellido)
    if (count($partes) < 2) {
        return "Error: Se necesita al menos un nombre y un apellido";
    }
    
    $nombre = $partes[0];
    $apellido = $partes[1];

    // Extraer primeras 3 letras (rellenar con "x" si tiene menos de 3 letras)
    $nombre3 = substr(str_pad($nombre, 3, 'x'), 0, 3);
    $apellido3 = substr(str_pad($apellido, 3, 'x'), 0, 3);

    // Número aleatorio de 3 cifras
    $numero = rand(100, 999);

    // Concatenar resultado
    return $nombre3 . $apellido3 . $numero;
}