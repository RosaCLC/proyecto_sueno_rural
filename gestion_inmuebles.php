<?php
    session_start();
    require_once "conexionBD.php";

    if(!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario']!=='administrador'){
        header("Location: login_form.php?admin=null");
        exit();
    }

    $region= $_GET['region'] ?? '';
    $tipo= $_GET['tipo'] ?? '';
    $precio_min= $_GET['min'] ?? '';
    $precio_max= $_GET['max'] ?? '';
    $id_inmueble= $_GET['id_inmueble'] ?? null;
    $titulo= trim($_GET['titulo'] ?? '');

    $precio_min = is_numeric($precio_min) ? (int)$precio_min : null;
    $precio_max = is_numeric($precio_max) ? (int)$precio_max : null;
    $id_inmueble = is_numeric($id_inmueble) ? (int)$id_inmueble : null;

    $sql_inmueble = "SELECT i.*, m.region_municipio, m.nombre_municipio, f.url_foto, f.descripcion_foto 
    FROM inmueble i JOIN municipio m ON i.id_municipio = m.id_municipio
    LEFT JOIN foto f ON f.id_foto = (
        SELECT MIN(ff.id_foto)
        FROM foto ff
        WHERE ff.id_inmueble = i.id_inmueble
    )
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
    if ($precio_min !== null && $precio_max !== null){
        $sql_inmueble .= " AND i.precio_inmueble BETWEEN :min AND :max";
        $seleccion[':min'] = $precio_min;
        $seleccion[':max'] = $precio_max;
    } elseif ($precio_min !== null){
        $sql_inmueble .= " AND i.precio_inmueble >= :min";
        $seleccion[':min'] = $precio_min;
    } elseif ($precio_max !== null){
        $sql_inmueble .= " AND i.precio_inmueble <= :max";
        $seleccion[':max'] = $precio_max;
    }
    if ($id_inmueble !== null){
        $sql_inmueble .= " AND i.id_inmueble = :id_inmueble";
        $seleccion[':id_inmueble'] = $id_inmueble;
    }
    if ($titulo !== ''){
        $sql_inmueble .= " AND i.titulo_inmueble LIKE :titulo";
        $seleccion[':titulo'] = "%$titulo%";
    }

    $sql_inmueble .= " ORDER BY i.fecha_publicacion_inmueble DESC";

    $inmuebles = [];

    $consulta_inmueble = $conexion->prepare($sql_inmueble);
    $consulta_inmueble->execute($seleccion);
    $inmuebles = $consulta_inmueble->fetchAll();

    //Consulta para regiones
    $sql_region = "SELECT DISTINCT m.region_municipio
    FROM municipio m JOIN inmueble i
    WHERE m.id_municipio = i.id_municipio";

    $consulta_region = $conexion->query($sql_region);
    $regiones = $consulta_region->fetchAll(PDO::FETCH_ASSOC); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title>ADMIN: Gestion de inmuebles</title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorInmuebles">
            <?php if(isset($_GET['delete']) && $_GET['delete'] == 'ok'): ?>
                <div class="card noResult">
                    <p class="aviso">Inmueble eliminado correctamente de la base de datos.</p>
                </div>
            <?php endif;?>
            <h1>Gestión de inmuebles:</h1>
            <div class="botonesGestion">
                <button type="button" class="botonFiltros" id="botonFiltros">Filtrar inmuebles</button>
                <a class="botonSimulado" href="inmueble_form.php">Añadir inmueble nuevo</a>
            </div>
            <div class="contenedorFiltros">
                <form id="formFiltros" action="gestion_inmuebles.php" method="GET">
                    <select name="region" id="region">
                        <option value="">Todas las regiones</option>
                        <?php foreach ($regiones as $fila_region):?>
                            <option value="<?php echo $fila_region['region_municipio']; ?>" 
                            <?php if($region === $fila_region['region_municipio']) echo 'selected'; ?>>
                                <?php echo $fila_region['region_municipio']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>
                    <select name="tipo" id="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="casa" <?php if($tipo === 'casa') echo 'selected'?>>Casa</option>
                        <option value="piso" <?php if($tipo === 'piso') echo 'selected'?>>Piso</option>
                        <option value="cabana" <?php if($tipo === 'cabana') echo 'selected'?>>Cabaña</option>
                        <option value="terreno" <?php if($tipo === 'terreno') echo 'selected'?>>Terreno</option>
                    </select><br>
                    <input type="number" name="min" id="precioMin" value="<?php echo htmlspecialchars($precio_min); ?>" placeholder="Precio mínimo">
                    <input type="number" name="max" id="precioMax" value="<?php echo htmlspecialchars($precio_max); ?>" placeholder="Precio máximo"><br>
                    <span class="mensaje errorPrecioMin errorPrecioMax"></span><br>
                    <input type="number" name="id_inmueble" id="id_inmueble" value="<?php echo htmlspecialchars($id_inmueble); ?>" placeholder="ID del inmueble"><br>
                    <span class="mensaje errorIdInmueble"></span><br>
                    <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($titulo); ?>" placeholder="Buscar por palabras en el título"><br>

                <button type="submit">Aplicar filtros</button>
                </form>
            </div>
            <div class="contenedorCards">
                <?php if(count($inmuebles)>0): ?>
                    <?php foreach ($inmuebles as $inmueble): ?>
                        <div class="card favoritoCard">
                            <div class="fotoCard"><img src="<?php echo htmlspecialchars($inmueble['url_foto'])?>" alt="<?php echo htmlspecialchars($inmueble['descripcion_foto'])?>"></div>
                            <div class="infoCard">
                                <div class="topCard">
                                    <h3 class="tituloCard"><?php echo htmlspecialchars($inmueble['titulo_inmueble']);?></h3>
                                    <p class="precioCard"><?php echo htmlspecialchars(number_format($inmueble['precio_inmueble'],0,',','.')). ' €'; ?></p>
                                </div>
                                <div class="ubicacionCard">
                                    <p class="lugar"><?php echo htmlspecialchars($inmueble['nombre_municipio']) .' ('. htmlspecialchars($inmueble['region_municipio']).')' ; ?></p>
                                </div>
                                <div class="datosCard">
                                    <div class="metros-viv">
                                        <svg class="icon-rule" viewBox="0 0 24 24">
                                            <path d="M12 19.875c0 .621 -.512 1.125 -1.143 1.125h-5.714a1.134 1.134 0 0 1 -1.143 -1.125v-15.875a1 1 0 0 1 1 -1h5.857c.631 0 1.143 .504 1.143 1.125z"/>
                                            <path d="M12 9h-2"/>
                                            <path d="M12 6h-3"/>
                                            <path d="M12 12h-3"/>
                                            <path d="M12 18h-3"/>
                                            <path d="M12 15h-2"/>
                                            <path d="M21 3h-4"/>
                                            <path d="M19 3v18"/>
                                            <path d="M21 21h-4"/>
                                        </svg>
                                        <svg class="icons" viewBox="0 0 24 24">
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                                        </svg>
                                        <p><?php echo !empty($inmueble['metros_vivienda']) ? htmlspecialchars($inmueble['metros_vivienda']) . ' m² de vivienda' : '-'?></p>
                                    </div>
                                    <div class="metros-terr">
                                        <svg class="icon-rule" viewBox="0 0 24 24">
                                            <path d="M12 19.875c0 .621 -.512 1.125 -1.143 1.125h-5.714a1.134 1.134 0 0 1 -1.143 -1.125v-15.875a1 1 0 0 1 1 -1h5.857c.631 0 1.143 .504 1.143 1.125z"/>
                                            <path d="M12 9h-2"/>
                                            <path d="M12 6h-3"/>
                                            <path d="M12 12h-3"/>
                                            <path d="M12 18h-3"/>
                                            <path d="M12 15h-2"/>
                                            <path d="M21 3h-4"/>
                                            <path d="M19 3v18"/>
                                            <path d="M21 21h-4"/>
                                        </svg>
                                        <svg class="icons" viewBox="0 0 24 24">
                                            <g transform="translate(-1,0)">
                                                <path d="M12 13l-2 -2" />
                                                <path d="M12 12l2 -2" />
                                                <path d="M12 20v-12" />
                                                <path d="M9.824 16a3 3 0 0 1 -2.743 -3.69a3 3 0 0 1 .304 -4.833a3 3 0 0 1 4.615 -3.707a3 3 0 0 1 4.614 3.707a3 3 0 0 1 .305 4.833a3 3 0 0 1 -2.919 3.695h-4z" />
                                            </g>
                                            <path d="M7 21h13" />
                                        </svg>
                                        <p><?php echo !empty($inmueble['metros_terreno']) ? htmlspecialchars($inmueble['metros_terreno']) . ' m² de terreno' : 'No dispone de terreno'?></p>
                                    </div>
                                    <div class="num-habitaciones">
                                        <svg class="icons" viewBox="0 0 24 24">
                                            <path d="M7 9m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                            <path d="M22 17v-3h-20"/>
                                            <path d="M2 8v9"/>
                                            <path d="M12 14h10v-2a3 3 0 0 0 -3 -3h-7v5z"/>
                                        </svg>
                                        <p>
                                            <?php if(!empty($inmueble['habitaciones_inmueble'])){
                                                $num= $inmueble['habitaciones_inmueble'];
                                                echo htmlspecialchars($num).' '. ($num==1 ? 'habitación' : 'habitaciones');
                                            } else { echo '-';}?>
                                        </p>
                                    </div>
                                    <div class="num-banos">
                                        <svg class="icons" viewBox="0 0 24 24">
                                            <path d="M4 12h16a1 1 0 0 1 1 1v3a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4v-3a1 1 0 0 1 1 -1z"/>
                                            <path d="M6 12v-7a2 2 0 0 1 2 -2h3v2.25"/>
                                            <path d="M4 21l1 -1.5"/>
                                            <path d="M20 21l-1 -1.5"/>
                                        </svg>
                                        <p>
                                            <?php if(!empty($inmueble['banos_inmueble'])){
                                                $num= $inmueble['banos_inmueble'];
                                                echo htmlspecialchars($num).' '. ($num==1 ? 'cuarto de baño' : 'cuartos de baño');
                                            } else { echo '-';}?>
                                        </p>
                                    </div>
                                    <div class="estado-viv">
                                        <svg class="icons 
                                        <?php if($inmueble['tipo_inmueble'] !== 'terreno'){
                                                $estado_inm= $inmueble['estado_inmueble'];
                                                if ($estado_inm === 'listo para entrar') {
                                                    echo 'estado-entrar';
                                                } elseif ($estado_inm === 'necesita reformas'){
                                                    echo 'estado-reformar';
                                                } else {
                                                    echo 'estado-ruina';
                                                }
                                        }?>" viewBox="0 0 24 24">
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                        </svg>
                                        <p>
                                            <?php if($inmueble['tipo_inmueble'] !== 'terreno'){
                                                $estado_inm= $inmueble['estado_inmueble'];
                                                if ($estado_inm === 'listo para entrar') {
                                                    echo 'Lista para entrar a vivir';
                                                } elseif ($estado_inm === 'necesita reformas'){
                                                    echo 'Necesita reformas para ser habitada';
                                                } else {
                                                    echo 'En ruinas';
                                                }
                                            } else { echo 'Terreno vacío';}?>
                                        </p>
                                    </div>
                                </div>
                                <div class="baseFavorito">
                                    <a class="enlaces enlaceFav" href="detalles_inmueble.php?id=<?php echo $inmueble['id_inmueble']; ?>">Ver más detalles del inmueble</a>
                                    <div class="editarInm">
                                        <a class="enlaces" href="inmueble_form.php?id=<?php echo $inmueble['id_inmueble'];?>">
                                        Editar inmueble
                                        <svg class="icons tool" viewBox="0 0 24 24">
                                            <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
                                        </svg>
                                        </a>
                                        <form action="eliminar_inmueble.php" method="POST" onsubmit="return confirm('¿Seguro que quiere eliminar este inmueble?');">
                                            <input type="hidden" name="id_inmueble" value="<?php echo $inmueble['id_inmueble']; ?>">
                                            <button type="submit" class="enlaces favGuarda botonGuardar">
                                                ELIMINAR INMUEBLE
                                                <svg class="icons trash" viewBox="0 0 24 24">
                                                    <path d="M4 7h16"/>
                                                    <path d="M10 11v6"/>
                                                    <path d="M14 11v6"/>
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card noResult">
                        <p class="aviso">
                            No se han encontrado inmuebles con esos filtros de búsqueda.
                        </p>
                    </div>
                <?php endif; ?>
            </div>


        </div>

    </div>
    <?php require_once "footer.php"; ?>    
    <script type="module" src="js/script.js"></script>
</body>
</html>