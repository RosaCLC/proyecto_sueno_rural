<?php
    session_start();
    require_once "conexionBD.php";

    if(!isset($_SESSION['id_usuario'])){
    header("Location: login_form.php");
    exit();
    }

    $id_usuario = $_SESSION['id_usuario'];

    $sql_favorito = "SELECT f.*, i.*, m.nombre_municipio, m.region_municipio 
    FROM favorito f 
    JOIN inmueble i ON f.id_inmueble = i.id_inmueble
    JOIN municipio m ON i.id_municipio = m.id_municipio
    WHERE f.id_usuario = :id_usuario";

    $favoritos = [];

    $consulta_favorito = $conexion->prepare($sql_favorito);
    $consulta_favorito->bindParam(':id_usuario',$id_usuario);
    $consulta_favorito->execute();

    $favoritos = $consulta_favorito->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil: <?php echo htmlspecialchars ($_SESSION['nombre_usuario'])?> </title>
</head>
<body>
    <?php if(isset($_SESSION['id_usuario'])):?>
        <p>¡Hola, <a href="perfil_usuario.php"><?php echo htmlspecialchars($_SESSION['nombre_usuario']);?></a>!<br>
        <a href="logout.php">Cerrar sesión</a>
    </p>
    <?php else:?>
        <p>
            <a href="login_form.php">Inicia sesión</a> <br>
            <a href="registro_form.php">Regístrate</a>
        </p>
    <?php endif; ?>
    
    <h1>Tus favoritos:</h1>
    <?php if (count($favoritos)>0): ?>
        <?php foreach ($favoritos as $favorito):?>
            <div class="card">
                <h3><?php echo htmlspecialchars($favorito['titulo_inmueble']); ?></h3>
                <p><?php echo htmlspecialchars($favorito['nombre_municipio']) .' ('. htmlspecialchars($favorito['region_municipio']).')' ; ?></p>
                <p><?php echo htmlspecialchars($favorito['precio_inmueble']); ?> €</p>
                <a href="detalles_inmueble.php?id=<?php echo $favorito['id_inmueble']; ?>">Ver más detalles</a>
                <a href="quitar_favorito.php?id_favorito=<?php echo $favorito['id_favorito'];?>">Quitar favorito</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tiene ningún favorito guardado</p>
    <?php endif; ?>
</body>
</html>