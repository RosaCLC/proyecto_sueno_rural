<?php
    session_start();
    require_once "conexionBD.php";
    
    // validaciones y redirecciones
    if(!isset($_SESSION['id_usuario'])){
        header("Location: login_form.php");
        exit();
    }
    if ($_SERVER ["REQUEST_METHOD"] !== "POST"){
        header("Location: index.php");
        exit(); 
    }
    $id_usuario = $_SESSION['id_usuario'];
    $id_inmueble = $_POST['idInmueble'] ?? null;
            
    if (!$id_inmueble){
        die ("No se especifica el inmueble");
    }    
    
    //comprobación de que el inmueble se guarde una única vez
    try {
        $sql_duplicado = "SELECT id_favorito FROM favorito
        WHERE id_usuario = :id_usuario
        AND id_inmueble = :id_inmueble";

        $consulta_duplicado = $conexion->prepare ($sql_duplicado);
        $consulta_duplicado->bindParam(":id_usuario", $id_usuario);
        $consulta_duplicado->bindParam(":id_inmueble", $id_inmueble);
        $consulta_duplicado->execute();

        $favorito = $consulta_duplicado->fetch(PDO::FETCH_ASSOC);

        //si no existe ese inmueble en favoritos
        if (!$favorito){
            $sql_agregar= "INSERT INTO favorito (id_usuario, id_inmueble) 
            VALUES (:id_usuario, :id_inmueble)";

            $stmt_agregar = $conexion->prepare($sql_agregar);
            $stmt_agregar->bindParam(":id_usuario", $id_usuario);
            $stmt_agregar->bindParam(":id_inmueble", $id_inmueble);

            $stmt_agregar-> execute();
        }
        //se vuelve al inmueble
        header("Location: detalles_inmueble.php?id=$id_inmueble");
        exit();

    } catch (PDOException $e){
        echo "Error: " .$e->getMessage();
    }
 
?>