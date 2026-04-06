<?php
    session_start();

    require_once "conexionBD.php";

    $region= $_GET['region'] ?? '';
    $tipo= $_GET['tipo'] ?? '';
    $precio_min= $_GET['min'] ?? '';
    $precio_max= $_GET['max'] ?? '';
    $habitaciones= $_GET['habitaciones'] ?? '';
    $banos= $_GET['banos'] ?? '';
    $estado= $_GET['estado'] ?? '';
    $internet= $_GET['internet'] ?? '';
    $acceso= $_GET['acceso'] ?? '';
    $agua= $_GET['agua'] ?? '';
    $electricidad= $_GET['electricidad'] ?? '';
    $saneamiento= $_GET['saneamiento'] ?? '';

    $sql_inmueble = "SELECT i.*, m.region_municipio, m.nombre_municipio 
    FROM inmueble i JOIN municipio m 
    ON i.id_municipio = m.id_municipio
    WHERE 1=1";
        
    $seleccion = [];

    if(!empty($region)){
    $sql_inmueble .= " AND m.region_municipio = :region";
    $seleccion[':region'] = $region;
    }

    if (!empty($tipo)){
        $sql_inmueble .= " AND i.tipo_inmueble = :tipo";
        $seleccion[':tipo'] = $tipo;
    }

    if ($precio_min !== '' && $precio_max !== ''){
        $sql_inmueble .= " AND i.precio_inmueble BETWEEN :min AND :max";
        $seleccion[':min'] = $precio_min;
        $seleccion[':max'] = $precio_max;
    } elseif ($precio_min !== ''){
        $sql_inmueble .= " AND i.precio_inmueble >= :min";
        $seleccion[':min'] = $precio_min;
    } elseif ($precio_max !== ''){
        $sql_inmueble .= " AND i.precio_inmueble <= :max";
        $seleccion[':max'] = $precio_max;
    }
    
    if (!empty($habitaciones)){
        $sql_inmueble .= " AND i.habitaciones_inmueble = :habitaciones";
        $seleccion[':habitaciones'] = $habitaciones;
    }
    if (!empty($banos)){
        $sql_inmueble .= " AND i.banos_inmueble = :banos";
        $seleccion[':banos'] = $banos;
    }
    if (!empty($estado)){
        $sql_inmueble .= " AND i.estado_inmueble = :estado";
        $seleccion[':estado'] = $estado;
    }
    if (!empty($internet)){
        $sql_inmueble .= " AND i.internet_inmueble = :internet";
        $seleccion[':internet'] = $internet;
    }
    if (!empty($acceso)){
        $sql_inmueble .= " AND i.acceso_inmueble = :acceso";
        $seleccion[':acceso'] = $acceso;
    }
    if (!empty($agua)){
        $sql_inmueble .= " AND i.agua_inmueble = :agua";
        $seleccion[':agua'] = $agua;
    }

    if (!empty($electricidad)){
        $sql_inmueble .= " AND i.electricidad_inmueble = :electricidad";
        $seleccion[':electricidad'] = $electricidad;
    }

    if (!empty($saneamiento)){
        $sql_inmueble .= " AND i.saneamiento_inmueble = :saneamiento";
        $seleccion[':saneamiento'] = $saneamiento;
    }

    $sql_inmueble .= " ORDER BY i.fecha_publicacion_inmueble DESC";

    $inmuebles = [];

    $consulta_inmueble = $conexion->prepare($sql_inmueble);
    $consulta_inmueble->execute($seleccion);
    $inmuebles = $consulta_inmueble->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de SUEÑO RURAL</title>
</head>
<body>
    <?php if(isset($_SESSION['nombre_usuario'])):?>
        <p>¡Hola, <a href="perfil_usuario.php"><?php echo htmlspecialchars($_SESSION['nombre_usuario']);?></a>! <br>
        <a href="logout.php">Cerrar sesión</a>
    </p>
    <?php else:?>
        <p>
            <a href="login_form.php">Inicia sesión</a> <br>
            <a href="registro_form.php">Regístrate</a>
        </p>
    <?php endif; ?>    
    <h1>Resultados para la búsqueda: </h1>
    <form action="resultados.php" method="GET">
        <input type="hidden" name="region" value="<?php echo htmlspecialchars($region); ?>">
        <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>">

        <label for="min">Precio mínimo:</label><br>
        <input type="number" name="min" id="min" value="<?php echo htmlspecialchars($precio_min); ?>"><br>
        <hr>
        <label for="max">Precio máximo:</label><br>
        <input type="number" name="max" id="max" value="<?php echo htmlspecialchars($precio_max); ?>"><br>
        <hr>
        <label for="habitaciones">Habitaciones:</label><br>
        <input type="number" name="habitaciones" id="habitaciones">
        <hr>
        <label for="banos">Baños:</label><br>
        <input type="number" name="banos" id="banos">
        <hr>
        <label for="estado">Estado de la vivienda:</label><br>
        <select name="estado" id="estado">
            <option value="listo para entrar">Lista para entrar a vivir</option>
            <option value="necesita reformas">Necesita reformas para poder habitarla</option>
            <option value="ruina">Ruina</option>
        </select>
        <hr>
        <label for="internet">Disponibilidad de red de internet:</label><br>
        <select name="internet" id="internet">
            <option value="no">No hay acceso a internet cerca</option>
            <option value="basico">Llega internet</option>
            <option value="alta velocidad">Fibra óptica de internet</option>
        </select>
        <hr>
        <label for="acceso">Tipo de acceso al inmueble:</label><br>
        <select name="acceso" id="acceso">
            <option value="directo">Acceso directo en coche</option>
            <option value="4 por 4">Sólo acceso con 4x4</option>
            <option value="camino">Sólo acceso por camino andado</option>
        </select>
        <hr>
        <label for="agua">Dispone de agua:</label>
        <input type="checkbox" id="agua" name="agua" value="1">
        <hr>
        <label for="electricidad">Dispone de electricidad:</label>
        <input type="checkbox" id="electricidad" name="electricidad" value="1">
        <hr>
        <label for="saneamiento">Dispone de conexión a alcantarillado:</label>
        <input type="checkbox" id="saneamiento" name="saneamiento" value="1">

    <button type="submit">Aplicar filtros</button>

    </form>
    <?php if(count($inmuebles)>0): ?>
        <?php foreach ($inmuebles as $inmueble): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($inmueble['titulo_inmueble']); ?></h3>
                <p><?php echo htmlspecialchars($inmueble['nombre_municipio']) .' ('. htmlspecialchars($inmueble['region_municipio']).')' ; ?></p>
                <p><?php echo htmlspecialchars($inmueble['precio_inmueble']); ?> €</p>
                <a href="detalles_inmueble.php?id=<?php echo $inmueble['id_inmueble']; ?>">Ver más detalles</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No se han encontrado inmuebles con esos filtros.</p>
    <?php endif; ?>

</body>
</html>


    
    