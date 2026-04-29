<?php
session_start();
require_once 'conexionBD.php';

if(!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario']!=='administrador'){
    header("Location: login_form.php?admin=null");
    exit();
}
if ($_SERVER ["REQUEST_METHOD"] !== "POST"){
    header("Location: index.php");
    exit(); 
}

$id_inmueble = $_POST['id_inmueble'] ?? null;

if (!$id_inmueble || !is_numeric($id_inmueble)) {
    die("Identificador de inmueble no válido.");
}
$id_inmueble = (int)$id_inmueble;

try{
    $sql_eliminar = "DELETE FROM inmueble WHERE id_inmueble = :id_inmueble";
    $consulta_eliminar = $conexion->prepare($sql_eliminar);
    $consulta_eliminar->bindParam(':id_inmueble', $id_inmueble, PDO::PARAM_INT);
    $consulta_eliminar->execute();

    header("Location: gestion_inmuebles.php?delete=ok");
    exit();
} catch (PDOException $e){
    echo "Error al eliminar el inmueble de la base de datos: " .$e->getMessage();
}
?>
