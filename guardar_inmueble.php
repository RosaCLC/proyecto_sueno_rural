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

//Valores requeridos
$tipo = $_POST['tipo'] ?? '';
$titulo = trim($_POST['titulo'] ?? '');
$precio = $_POST['precio'] ?? '';
$id_municipio = $_POST['id_municipio'] ?? '';
$latitud = $_POST['latitud'] ?? '';
$longitud = $_POST['longitud'] ?? '';
$descripcion = trim($_POST['descripcion'] ?? '');
$internet = $_POST['internet'] ?? '';
$acceso = $_POST['acceso'] ?? '';

//Valores opcionales
$metros_vivienda = (isset($_POST['metros_viv']) && $_POST['metros_viv'] !== '') ? $_POST['metros_viv'] : null;
$metros_terreno = (isset($_POST['metros_terr']) && $_POST['metros_terr'] !== '') ? $_POST['metros_terr'] : null;
$habitaciones = (isset($_POST['habitaciones']) && $_POST['habitaciones'] !== '') ? $_POST['habitaciones'] : null;
$banos = (isset($_POST['banos']) && $_POST['banos'] !== '') ? $_POST['banos'] : null;
$estado = (isset($_POST['estado']) && $_POST['estado'] !== '') ? $_POST['estado'] : null;

$agua = isset($_POST['agua']) ? 1 : 0;
$electricidad = isset($_POST['electricidad']) ? 1 : 0;
$saneamiento = isset($_POST['saneamiento']) ? 1 : 0;

//VALIDADCIONES
if (empty($tipo) || empty($titulo) || $precio === '' || empty($id_municipio) || $latitud === '' || $longitud === '' || empty($descripcion)) {
    die("Faltan campos obligatorios.");
}
if ($precio < 0) {
    die("El precio no puede ser negativo.");
}
if ($latitud < -90 || $latitud > 90) {
    die("Latitud no válida.");
}
if ($longitud < -180 || $longitud > 180) {
    die("Longitud no válida.");
}
if ($metros_vivienda !== null && $metros_vivienda < 0) {
    die("Los metros de vivienda no pueden ser negativos.");
}
if ($metros_terreno !== null && $metros_terreno < 0) {
    die("Los metros de terreno no pueden ser negativos.");
}

if ($habitaciones !== null && $habitaciones < 0) {
    die("El número de habitaciones no puede ser negativo.");
}

if ($banos !== null && $banos < 0) {
    die("El número de baños no puede ser negativo.");
}

//si es terreno automaticamente estos campos envian null
if ($tipo === 'terreno') {
    $habitaciones = null;
    $banos = null;
    $estado = null;
}

//EDITAR Y CREAR INMUEBLES
if($id_inmueble){
    $id_inmueble = (int)$id_inmueble;

    $sql_editar = "UPDATE inmueble SET ";
    $tabla_valor = [];
    $edicion = [];

    //FORMACION SQL
    $tabla_valor[] = "tipo_inmueble = :tipo";
    $tabla_valor[] = "titulo_inmueble = :titulo";
    $tabla_valor[] = "precio_inmueble = :precio";
    $tabla_valor[] = "metros_vivienda = :metros_viv";
    $tabla_valor[] = "metros_terreno = :metros_terr";
    $tabla_valor[] = "latitud_inmueble = :latitud";
    $tabla_valor[] = "longitud_inmueble = :longitud";
    $tabla_valor[] = "habitaciones_inmueble = :habitaciones";
    $tabla_valor[] = "banos_inmueble = :banos";
    $tabla_valor[] = "estado_inmueble = :estado";
    $tabla_valor[] = "descripcion_inmueble = :descripcion";
    $tabla_valor[] = "id_municipio = :id_municipio";
    $tabla_valor[] = "internet_inmueble = :internet";    
    $tabla_valor[] = "acceso_inmueble = :acceso";
    $tabla_valor[] = "agua_inmueble = :agua";    
    $tabla_valor[] = "electricidad_inmueble = :electricidad";    
    $tabla_valor[] = "saneamiento_inmueble = :saneamiento";

    //implode convierte arrays en strings, este une los elementos con ', '
    $sql_editar .= implode(", ", $tabla_valor);
    $sql_editar .= " WHERE id_inmueble = :id_inmueble";

    //Array con todos los nuevos valores
    $edicion[':tipo'] = $tipo;
    $edicion[':titulo'] = $titulo;
    $edicion[':precio'] = $precio;
    $edicion[':metros_viv'] = $metros_vivienda;
    $edicion[':metros_terr'] = $metros_terreno;
    $edicion[':latitud'] = $latitud;
    $edicion[':longitud'] = $longitud;
    $edicion[':habitaciones'] = $habitaciones;
    $edicion[':banos'] = $banos;
    $edicion[':estado'] = $estado;
    $edicion[':descripcion'] = $descripcion;
    $edicion[':id_municipio'] = $id_municipio;
    $edicion[':internet'] = $internet;
    $edicion[':acceso'] = $acceso;
    $edicion[':agua'] = $agua;
    $edicion[':electricidad'] = $electricidad;
    $edicion[':saneamiento'] = $saneamiento;

    $edicion[':id_inmueble'] = $id_inmueble;

    $consulta_editar = $conexion->prepare($sql_editar);
    //ejecución con los nuevos valores del array
    $consulta_editar->execute($edicion);

    header("Location: detalles_inmueble.php?id=" . $id_inmueble . "&edit=ok");
    exit();


} else{
    $sql_crear = "INSERT INTO inmueble (tipo_inmueble, titulo_inmueble, precio_inmueble, id_municipio, latitud_inmueble, longitud_inmueble, descripcion_inmueble, internet_inmueble, acceso_inmueble, metros_vivienda, metros_terreno, habitaciones_inmueble, banos_inmueble, estado_inmueble, agua_inmueble, electricidad_inmueble, saneamiento_inmueble) VALUES (:tipo, :titulo, :precio, :id_municipio, :latitud, :longitud, :descripcion, :internet, :acceso, :metros_viv, :metros_terr, :habitaciones, :banos, :estado, :agua, :electricidad, :saneamiento);";

    $creacion = [];
    $creacion[':tipo'] = $tipo;
    $creacion[':titulo'] = $titulo;
    $creacion[':precio'] = $precio;
    $creacion[':metros_viv'] = $metros_vivienda;
    $creacion[':metros_terr'] = $metros_terreno;
    $creacion[':latitud'] = $latitud;
    $creacion[':longitud'] = $longitud;    
    $creacion[':habitaciones'] = $habitaciones;
    $creacion[':banos'] = $banos;
    $creacion[':estado'] = $estado;
    $creacion[':descripcion'] = $descripcion;
    $creacion[':id_municipio'] = $id_municipio;
    $creacion[':internet'] = $internet;
    $creacion[':acceso'] = $acceso;
    $creacion[':agua'] = $agua;
    $creacion[':electricidad'] = $electricidad;
    $creacion[':saneamiento'] = $saneamiento;

    $consulta_crear = $conexion->prepare($sql_crear);
    $consulta_crear->execute($creacion);

    $id_nuevo = $conexion->lastInsertId();
    
    header("Location: detalles_inmueble.php?id=" . $id_nuevo . "&create=ok");
    exit();

}