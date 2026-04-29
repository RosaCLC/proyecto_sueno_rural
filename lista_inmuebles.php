<?php
    session_start();
    require_once "conexionBD.php";

    $region= $_GET['region'] ?? '';
    $tipo= $_GET['tipo'] ?? '';
    $precio_min= $_GET['min'] ?? '';
    $precio_max= $_GET['max'] ?? '';
    $habitaciones= $_GET['habitaciones'] ?? '';
    $banos= $_GET['banos'] ?? '';
    
    //validacion de los inputs numéricos, si no son enteros dan null
    $precio_min = is_numeric($precio_min) ? (int)$precio_min : null;
    $precio_max = is_numeric($precio_max) ? (int)$precio_max : null;
    $habitaciones= is_numeric($habitaciones) ? (int)$habitaciones : null;
    $banos= is_numeric($banos) ? (int)$banos : null;
    
    $estado= $_GET['estado'] ?? '';
    $internet= $_GET['internet'] ?? '';
    $acceso= $_GET['acceso'] ?? '';
    $agua= $_GET['agua'] ?? '';
    $electricidad= $_GET['electricidad'] ?? '';
    $saneamiento= $_GET['saneamiento'] ?? '';

    //CONSULTA PARA INMUEBLES y SUBCONSULTA PARA MOSTRAR LA PRIMERA FOTO ALMACENADA DE CADA INMUEBLE
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
    
    if ($habitaciones !== null){
        $sql_inmueble .= " AND i.habitaciones_inmueble = :habitaciones";
        $seleccion[':habitaciones'] = $habitaciones;
    }
    if ($banos !== null){
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

    //CONSULTA PARA REGIONES CON INMUEBLES DISPONIBLES
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
    <title>Inmuebles de SUEÑO RURAL</title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorInmuebles">
            <h1>Inmuebles disponibles: </h1>
            <button type="button" class="botonFiltros" id="botonFiltros">Filtros avanzados</button>
            <div class="contenedorFiltros">
                <form id="formFiltros" action="lista_inmuebles.php" method="GET">
                    <select name="region" id="region">
                        <option value="">Todas las regiones</option>
                        <?php foreach ($regiones as $fila_region):?>
                            <option value="<?php echo $fila_region['region_municipio']; ?>" 
                            <?php if($region === $fila_region['region_municipio']) echo 'selected'; ?>>
                                <?php echo $fila_region['region_municipio']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select><hr>
                    <select name="tipo" id="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="casa" <?php if($tipo === 'casa') echo 'selected'?>>Casa</option>
                        <option value="piso" <?php if($tipo === 'piso') echo 'selected'?>>Piso</option>
                        <option value="cabana" <?php if($tipo === 'cabana') echo 'selected'?>>Cabaña</option>
                        <option value="terreno" <?php if($tipo === 'terreno') echo 'selected'?>>Terreno</option>
                    </select><hr>

                    <input type="number" name="min" id="precioMin" value="<?php echo htmlspecialchars($precio_min); ?>" placeholder="Precio mínimo">
                    <input type="number" name="max" id="precioMax" value="<?php echo htmlspecialchars($precio_max); ?>" placeholder="Precio máximo"><br>
                    <span class="mensaje errorPrecioMin errorPrecioMax"></span>
                    <hr>
                    <input type="number" name="habitaciones" id="habitaciones" value="<?php echo htmlspecialchars($habitaciones); ?>" placeholder="Número de habitaciones">
                    <input type="number" name="banos" id="banos" value="<?php echo htmlspecialchars($banos); ?>" placeholder="Número de baños"><br>
                    <span class="mensaje errorBanos errorHabitaciones"></span>
                    <hr>
                    <select name="estado" id="estado">
                        <option value="" <?php if($estado === '') echo 'selected'?>>Estado de la vivienda</option>
                        <option value="listo para entrar" <?php if($estado === 'listo para entrar') echo 'selected'?>>Lista para entrar a vivir</option>
                        <option value="necesita reformas" <?php if($estado === 'necesita reformas') echo 'selected'?>>Necesita reformas para poder habitarla</option>
                        <option value="ruina" <?php if($estado === 'ruina') echo 'selected'?>>En ruinas</option>
                    </select>
                    <hr>
                    <select name="internet" id="internet">
                        <option value="" <?php if($internet === '') echo 'selected'?>>Disponibilidad de red de internet</option>
                        <option value="no" <?php if($internet === 'no') echo 'selected'?>>No hay acceso cercano a internet </option>
                        <option value="basico" <?php if($internet === 'basico') echo 'selected'?>>Llega red básica de internet</option>
                        <option value="alta velocidad" <?php if($internet === 'alta velocidad') echo 'selected'?>>Fibra óptica de internet</option>
                    </select>
                    <hr>
                    <select name="acceso" id="acceso">
                        <option value="" <?php if($acceso === '') echo 'selected'?>>Tipo de acceso al inmueble</option>
                        <option value="directo" <?php if($acceso === 'directo') echo 'selected'?>>Acceso directo en coche</option>
                        <option value="4 por 4" <?php if($acceso === '4 por 4') echo 'selected'?>>Sólo acceso con 4x4</option>
                        <option value="camino" <?php if($acceso === 'camino') echo 'selected'?>>Sólo acceso andando por camino</option>
                    </select>
                    <hr>
                    <label for="agua">Dispone de agua:</label>
                    <input type="checkbox" id="agua" name="agua" value="1" <?php if($agua == '1') echo 'checked'?>>
                    <hr>
                    <label for="electricidad">Dispone de electricidad:</label>
                    <input type="checkbox" id="electricidad" name="electricidad" value="1" <?php if($electricidad == '1') echo 'checked'?>>
                    <hr>
                    <label for="saneamiento">Dispone de conexión a alcantarillado:</label>
                    <input type="checkbox" id="saneamiento" name="saneamiento" value="1" <?php if($saneamiento == '1') echo 'checked'?>>
                    <br><br>

                <button type="submit">Aplicar filtros</button>
                <!--<a id="cerrarFiltros" class="enlaces">Cerrar filtros</a>-->
                </form>
            </div>
            <div class="contenedorCards">
                <?php if(count($inmuebles)>0): ?>
                    <?php foreach ($inmuebles as $inmueble): ?>
                        <a href="detalles_inmueble.php?id=<?php echo $inmueble['id_inmueble']; ?>">
                            <div class="card">
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
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card noResult">
                        <p class="aviso">
                            ¡Ups! No se han encontrado inmuebles con esos filtros. <br>
                            Aplique otros filtros de búsqueda.
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


    
    