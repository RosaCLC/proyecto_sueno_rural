<?php
    session_start();

    require_once "conexionBD.php";

    $id = $_GET['id'] ?? null;

    if (!$id){
        die ('Inmueble no especificado.');
    }

    $sql_detalles = "SELECT i.*, m.*
    FROM inmueble i JOIN municipio m 
    ON i.id_municipio = m.id_municipio
    WHERE i.id_inmueble = :id
    ";

    $consulta_detalles = $conexion->prepare($sql_detalles);
    $consulta_detalles-> bindParam(':id', $id, PDO::PARAM_INT);
    $consulta_detalles-> execute();
    $detalle = $consulta_detalles->fetch(PDO::FETCH_ASSOC);

    if (!$detalle){
        die ('Inmueble no encontrado');
    }

    $sql_fotos = "SELECT * FROM foto 
    WHERE id_inmueble = :id
    ";

    $consulta_fotos = $conexion->prepare($sql_fotos);
    $consulta_fotos-> bindParam(':id',$id, PDO::PARAM_INT);
    $consulta_fotos-> execute();
    $fotos = $consulta_fotos->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($detalle['titulo_inmueble'])?> en <?php echo htmlspecialchars($detalle['region_municipio'])?></title>
</head>
<body>
    <?php if(isset($_SESSION['id_usuario'])):?>
        <p>¡Hola, <a href="perfil_usuario.php"><?php echo htmlspecialchars($_SESSION['nombre_usuario']);?></a>! <br>
        <a href="logout.php">Cerrar sesión</a>
    </p>
    <?php else:?>
        <p>
            <a href="login_form.php">Inicia sesión</a> <br>
            <a href="registro_form.php">Regístrate</a>
        </p>
    <?php endif; ?>  
    
    <a href="resultados.php">Volver a resultados</a>
    <?php if (count($fotos)>0):?>
        <?php foreach ($fotos as $foto):?>
            <div>
                <img src="<?php echo htmlspecialchars($foto['url_foto'])?>" alt="<?php echo htmlspecialchars($foto['descripcion_foto'])?>">
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <hr>
    <h1><?php echo htmlspecialchars(ucfirst($detalle['titulo_inmueble']))?></h1> 
    <h2><?php echo htmlspecialchars(ucfirst($detalle['nombre_municipio'])) .' ('. htmlspecialchars(ucfirst($detalle['region_municipio'])) .')'?></h3>
    <h2><strong><?php echo htmlspecialchars($detalle['precio_inmueble']); ?> €</strong></h2>
    
    <?php if(isset($_SESSION['id_usuario'])): ?>
        <form action="agregar_favorito.php" method="POST">
            <input type="hidden" name="idInmueble" value="<?php echo $detalle['id_inmueble']; ?>">
            <button type="submit">Agregar a favoritos</button>
        </form>
    <?php else: ?>    
        <form action="login_form.php" method="GET">
            <button type="submit">Agregar a favoritos</button>
        </form>
    <?php endif; ?>
        
    <hr>
    <p><?php echo htmlspecialchars(ucfirst($detalle['descripcion_inmueble']))?></p>
    <h3>Características:</h3>
    <?php if ($detalle['tipo_inmueble'] !== 'terreno'): ?>
        <p>Metros construidos: <?php echo htmlspecialchars($detalle['metros_vivienda']) . ' m^2' ?></p>
        <p>Metros de terreno: <?php echo htmlspecialchars($detalle['metros_terreno']) . ' m^2' ?></p>
        <p>Estado del inmueble: <?php echo htmlspecialchars(ucfirst($detalle['estado_inmueble']))?></p>
        <p>Nº de habitaciones: <?php echo htmlspecialchars($detalle['habitaciones_inmueble'])?></p>
        <p>Nº de baños: <?php echo htmlspecialchars($detalle['banos_inmueble'])?></p>
    <?php else: ?>
        <p>Metros: <?php echo htmlspecialchars($detalle['metros_terreno']) . ' m^2' ?></p>
    <?php endif; ?>
    <p>Acceso: 
        <?php if ($detalle['acceso_inmueble'] === 'directo') :?> 
            Se puede llegar en coche hasta el inmueble
        <?php elseif ($detalle['acceso_inmueble'] === '4 por 4') :?> 
            Sólo usando un 4x4 u otro vehículo preparado
        <?php else :?> 
            Camino, hay que dejar el vehículo a una distancia del inmueble
        <?php endif; ?>
    </p>
    <p>Acceso a internet: 
        <?php if ($detalle['internet_inmueble'] === 'no') :?> 
            No disponible
        <?php elseif ($detalle['internet_inmueble'] === 'basico') :?> 
            Diponible red básica de internet
        <?php else :?> 
            Disponible red de alta velocidad
        <?php endif; ?>        
    </p>
    <p>Suministros disponibles: <br>
        <ul>
            <?php if ($detalle['agua_inmueble'] === 1) :?> 
                <li>Agua</li>
            <?php endif; ?>
            <?php if ($detalle['electricidad_inmueble'] === 1) :?> 
                <li>Electricidad</li>
            <?php endif; ?>
            <?php if ($detalle['saneamiento_inmueble'] === 1) :?> 
                <li>Conexión a alcantarillado o fosa séptica</li>
            <?php endif; ?>        
        </ul>
    </p>





</body>
</html>


