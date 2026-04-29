<?php
    $servidor = "localhost";
    $usuario = "root";
    $contrasena = "";
    $base = "sueno_rural";

    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$base;charset=utf8", $usuario, $contrasena);
        $conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion-> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e){
        echo "Error de conexión: " .$e->getMessage();
    }
?>