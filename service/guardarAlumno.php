<?php
session_start();

if(isset($_POST["nombre"]) && !empty($_POST["nombre"]) && !empty($_POST["idTutor"]) && !empty($_POST["idAula"])){
    include '../conexion.php'; //Abrimos conexion con bd tras comprobar que los campos no están vacíos

}