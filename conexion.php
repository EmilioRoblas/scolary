<?php
$host = 'localhost';     // Servidor
$db   = 'scolary'; // Nombre de la base de datos
$usuario = 'root';       // Usuario de la base de datos. User en local: root
$pass = '';    // Contraseña del usuario
$charset = 'utf8mb4';    // Codificación de caracteres

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";  // Cadena de conexión

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Manejo de errores con excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch como array asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva emulación de consultas preparadas
];

try {
    $pdo = new PDO($dsn, $usuario, $pass, $options);
    
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
