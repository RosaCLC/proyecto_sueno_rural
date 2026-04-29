<?php
session_start();
require_once 'conexionBD.php';

if(!isset($_SESSION['id_usuario'])){
header("Location: login_form.php");
exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_favorito = (int)($_GET['id_favorito'] ?? 0);
$volver = $_GET['volver'] ?? 'lista_favoritos.php';

/*strpos encuentra la posicion numérica de una ocurrencia dentro de otra, 
si no la encuentra devuelve false por eso se compara con false*/
if (strpos($volver, 'lista_favoritos.php') !== false) {
    $volver .= (strpos($volver, '?') !== false ? '&' : '?') . 'borrar=ok';
}

if ($id_favorito > 0) {
    $consulta = $conexion->prepare('DELETE FROM favorito WHERE id_favorito=? AND id_usuario=?');
    $consulta->execute([$id_favorito, $id_usuario]);
}

header("Location: " . $volver);
exit();
?>