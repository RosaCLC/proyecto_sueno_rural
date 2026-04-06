<?php
session_start();
require_once 'conexionBD.php';

if(!isset($_SESSION['id_usuario'])){
header("Location: login_form.php");
exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_favorito = (int)($_GET['id_favorito'] ?? 0);

if ($id_favorito > 0) {
    $consulta = $conexion->prepare('DELETE FROM favorito WHERE id_favorito=?');
    $consulta->execute([$id_favorito]);
}

header("Location: perfil_usuario.php");
exit();
?>