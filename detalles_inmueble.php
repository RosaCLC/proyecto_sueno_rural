<?php
    session_start();
    require_once "conexionBD.php";

    $id_inmueble = $_GET['id'] ?? null;
    $id_usuario = $_SESSION['id_usuario'] ?? null;

    if (!$id_inmueble){
        die ('Inmueble no especificado.');
    }

    $sql_detalles = "SELECT i.*, m.*, f.id_favorito
    FROM inmueble i 
    JOIN municipio m ON i.id_municipio = m.id_municipio
    LEFT JOIN favorito f 
        ON i.id_inmueble = f.id_inmueble 
        AND f.id_usuario = :id_usuario
    WHERE i.id_inmueble = :id_inmueble
    ";

    $consulta_detalles = $conexion->prepare($sql_detalles);
    $consulta_detalles-> bindParam(':id_inmueble', $id_inmueble, PDO::PARAM_INT);
    $consulta_detalles-> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $consulta_detalles-> execute();
    $detalle = $consulta_detalles->fetch(PDO::FETCH_ASSOC);

    if (!$detalle){
        die ('Inmueble no encontrado');
    }

    $sql_fotos = "SELECT * FROM foto 
    WHERE id_inmueble = :id_inmueble
    ";

    $consulta_fotos = $conexion->prepare($sql_fotos);
    $consulta_fotos-> bindParam(':id_inmueble',$id_inmueble, PDO::PARAM_INT);
    $consulta_fotos-> execute();
    $fotos = $consulta_fotos->fetchAll(PDO::FETCH_ASSOC);

    $latitud = $detalle['latitud_inmueble'];
    $longitud = $detalle['longitud_inmueble'];
    $id_municipio = $detalle['id_municipio'];

    $sql_servicios = "SELECT s.*, /*formula haversine para calcular distancias*/
    (
        6371 * ACOS(
            COS(RADIANS(:latitud)) *
            COS(RADIANS(s.latitud_servicio)) *
            COS(RADIANS(s.longitud_servicio) - RADIANS(:longitud)) +
            SIN(RADIANS(:latitud)) *
            SIN(RADIANS(s.latitud_servicio))
        )
    ) AS distancia FROM servicio s
    WHERE s.id_municipio = :id_municipio";
    
    $consulta_servicios = $conexion->prepare($sql_servicios);
    $consulta_servicios-> bindParam(':latitud', $latitud);
    $consulta_servicios-> bindParam(':longitud', $longitud);
    $consulta_servicios-> bindParam(':id_municipio',$id_municipio, PDO::PARAM_INT);
    $consulta_servicios-> execute();
    $servicios = $consulta_servicios->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title><?php echo htmlspecialchars($detalle['titulo_inmueble'])?> en <?php echo htmlspecialchars($detalle['region_municipio'])?></title>
</head>
<body>
    <?php require_once "header.php"?>   
    <div class="pagina">
        <div class="todoDetalles">
            <?php if(isset($_GET['edit']) && $_GET['edit'] == 'ok'): ?>
                <div class="card noResult">
                    <p class="aviso">¡Inmueble actualizado con éxito!</p>
                </div>
            <?php endif;?>
            <?php if(isset($_GET['create']) && $_GET['create'] == 'ok'): ?>
                <div class="card noResult">
                    <p class="aviso">¡Nuevo inmueble creado con éxito!</p>
                </div>
            <?php endif;?>
            <div class="contenedorDetalles">
                <?php if (count($fotos)>0):?>
                    <div class="carrusel">
                        <div class="zonaHover" id="botonAnterior">
                                <svg class="icon-left" viewBox="0 0 24 24">
                                    <path d="M13 20l-3 -8l3 -8" />
                                </svg>
                        </div>                   
                        <div class="fotosInmueble">
                            <img id="fotoCarrusel" src="<?php echo htmlspecialchars($fotos[0]['url_foto'])?>" alt="<?php echo htmlspecialchars($fotos[0]['descripcion_foto'])?>">
                        </div>
                        <div class="zonaHover" id="botonSiguiente">
                                <svg class="icon-right" viewBox="0 0 24 24">
                                    <path d="M11 4l3 8l-3 8" />
                                </svg>
                        </div>
                    </div>
                <?php endif; ?>
                <hr>
                <div class="topDetalles">
                    <h1><?php echo htmlspecialchars(ucfirst($detalle['titulo_inmueble']))?></h1> 
                    <?php if(isset($_SESSION['id_usuario']) && ($_SESSION['rol_usuario'] === 'usuario') && $detalle['id_favorito']): ?>
                        <div class="quitarFav">
                            <a class="enlaces favGuarda" href="quitar_favorito.php?id_favorito=<?php echo $detalle['id_favorito'];?>&volver=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
                            Quitar favorito
                            <svg class="icons heart" viewBox="0 0 24 24">
                                <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/>
                            </svg>
                            </a>
                        </div>
                    <?php elseif(isset($_SESSION['id_usuario']) && ($_SESSION['rol_usuario'] === 'usuario') && empty($detalle['id_favorito'])): ?> 
                        <form action="agregar_favorito.php" method="POST">
                            <input type="hidden" name="idInmueble" value="<?php echo $detalle['id_inmueble']; ?>">
                            <button type="submit" class="enlaces favGuarda botonGuardar">
                                Guardar en favoritos
                                <svg class="icons heart" viewBox="0 0 24 24">
                                    <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/>
                                </svg>
                            </button>
                        </form>
                    <?php elseif(isset($_SESSION['rol_usuario']) && ($_SESSION['rol_usuario'] === 'administrador')): ?>
                        <div class="editarDet">
                            <a class="enlaces" href="inmueble_form.php?id=<?php echo $detalle['id_inmueble'];?>">
                            Editar inmueble
                            <svg class="icons tool" viewBox="0 0 24 24">
                                <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
                            </svg>
                            </a>
                            <form action="eliminar_inmueble.php" method="POST" onsubmit="return confirm('¿Seguro que quiere eliminar este inmueble?');">
                                <input type="hidden" name="id_inmueble" value="<?php echo $detalle['id_inmueble']; ?>">
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
                        
                    <?php else: ?>
                        <div class= "agregarSinSesion">
                            <button type="button" id="falsoAgregar" class="enlaces favGuarda botonGuardar">
                                Guardar en favoritos
                                <svg class="icons heart" viewBox="0 0 24 24">
                                    <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/>
                                </svg>
                            </button>
                            <div class="botonFalso">
                                <a href="login_form.php">Inicie sesión</a> para guardar este inmueble en favoritos
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="subTopDetalles">
                    <h3><?php echo htmlspecialchars(ucfirst($detalle['nombre_municipio'])) .' ('. htmlspecialchars(ucfirst($detalle['region_municipio'])) .')'?></h3>
                    <p class="precioCard"><?php echo htmlspecialchars(number_format($detalle['precio_inmueble'],0,',','.')). ' €'; ?></p>
                </div>   
                <hr>
                <div class="descripcionDetalles">
                    <div class="estadoDetalles">
                        <div class="estado">
                            <svg class="icons 
                            <?php if($detalle['tipo_inmueble'] !== 'terreno'){
                            $estado_inm= $detalle['estado_inmueble'];
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
                                <?php if($detalle['tipo_inmueble'] !== 'terreno'){
                                    $estado_inm= $detalle['estado_inmueble'];
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
                            <?php if(isset($_SESSION['id_usuario']) && ($_SESSION['rol_usuario'] === 'usuario')):?>
                                <div class="botonesDetalle">
                                    <?php if($detalle['tipo_inmueble'] !== 'terreno'):?>    
                                        <a class="botonRef" href="calcula_reforma.php?m2=<?php echo $detalle['metros_vivienda'];?>">CALCULA SU REFORMA</a>
                                    <?php endif; ?>
                                    <a class="botonRef" href="contacto.php">CONTACTA CON NOSOTROS</a>
                                </div>
                            <?php endif; ?>
                    </div>
                    <h3>Descripción:</h3>
                    <p><?php echo htmlspecialchars(ucfirst($detalle['descripcion_inmueble']))?></p>
                </div>
                <h3>Características:</h3>
                <div class="caracteristicasDetalles">
                    <div class="datosInmueble">    
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
                            <p><?php echo !empty($detalle['metros_vivienda']) ? htmlspecialchars($detalle['metros_vivienda']) . ' m² de vivienda' : '-'?></p>
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
                            <p><?php echo !empty($detalle['metros_terreno']) ? htmlspecialchars($detalle['metros_terreno']) . ' m² de terreno' : 'No dispone de terreno'?></p>
                        </div>
                        <div class="num-habitaciones">
                            <svg class="icons" viewBox="0 0 24 24">
                                <path d="M7 9m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                <path d="M22 17v-3h-20"/>
                                <path d="M2 8v9"/>
                                <path d="M12 14h10v-2a3 3 0 0 0 -3 -3h-7v5z"/>
                            </svg>
                            <p>
                                <?php if(!empty($detalle['habitaciones_inmueble'])){
                                    $num= $detalle['habitaciones_inmueble'];
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
                                <?php if(!empty($detalle['banos_inmueble'])){
                                    $num= $detalle['banos_inmueble'];
                                    echo htmlspecialchars($num).' '. ($num==1 ? 'cuarto de baño' : 'cuartos de baño');
                                } else { echo '-';}?>
                            </p>
                        </div>
                    </div>
                    <div class="extrasInmueble">               
                        <div class="accesoInmueble">
                            <svg class="icons" viewBox="0 0 24 24">
                                <path d="M7 3h10l3 18h-16z"/>
                                <path d="M12 6v2"/>
                                <path d="M12 10v2"/>
                                <path d="M12 14v2"/>
                                <path d="M12 18v2"/>
                            </svg>
                            <p>Acceso al inmueble: 
                                <?php if ($detalle['acceso_inmueble'] === 'directo') :?> 
                                    Se puede llegar en coche hasta el inmueble
                                <?php elseif ($detalle['acceso_inmueble'] === '4 por 4') :?> 
                                    Sólo usando un 4x4 u otro vehículo preparado
                                <?php else :?> 
                                    Camino, hay que dejar el vehículo a una distancia del inmueble
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="internetInmueble">
                            <svg class= "icons" viewBox="0 0 24 24">
                                <path d="M12 18l.01 0" />
                                <path d="M9.172 15.172a4 4 0 0 1 5.656 0" />
                                <path d="M6.343 12.343a8 8 0 0 1 11.314 0" />
                                <path d="M3.515 9.515c4.686 -4.687 12.284 -4.687 17 0" />
                            </svg>
                            <p>
                                <?php if ($detalle['internet_inmueble'] === 'no') :?> 
                                    No hay red de internet cercana
                                <?php elseif ($detalle['internet_inmueble'] === 'basico') :?> 
                                    Dispone de red básica de internet
                                <?php else :?> 
                                    Dispone de red de alta velocidad
                                <?php endif; ?>        
                            </p>
                        </div>    
                            
                        <p>Suministros disponibles: <br>
                            <?php if (($detalle['agua_inmueble'] === 0) && ($detalle['electricidad_inmueble'] === 0) && ($detalle['saneamiento_inmueble'] === 0)) :?>
                                <p>No dispone de ningún suministro ni red de saneamiento o fosa séptica</p>
                            <?php else :?>
                                <ul>
                                    <?php if ($detalle['agua_inmueble'] === 1) :?>
                                        <li class="listado">    
                                            <svg class="icons" viewBox="0 0 24 24">
                                                <path d="M12 3c0 0 -6 7 -6 11a6 6 0 0 0 12 0c0 -4 -6 -11 -6 -11z"/>
                                            </svg>
                                            <p>Agua</p>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($detalle['electricidad_inmueble'] === 1) :?>
                                        <li class="listado">    
                                            <svg class="icons" viewBox="0 0 24 24">
                                            <path d="M12 2l-7 11h6l-1 8l7 -11h-6l1 -8z"/>  
                                            </svg>
                                            <p>Electricidad</p>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($detalle['saneamiento_inmueble'] === 1) :?> 
                                        <li class="listado">
                                            <svg class="icons" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M8 8h8"/>
                                                <path d="M7 11h10"/>
                                                <path d="M7 13h10"/>
                                                <path d="M8 16h8"/>
                                            </svg>
                                            <p>Conexión a alcantarillado o fosa séptica</p>
                                        </li>
                                    <?php endif; ?>
                                </ul>        
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <h3>Servicios cercanos:</h3>
                <div class="serviciosCercanos">
                    <?php if (count($servicios)=== 0) :?>
                        <p>No hay ningún servicio registrado en el municipio más cercano al inmueble</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($servicios as $servicio): ?>
                                <?php if($servicio['tipo_servicio'] === 'alimentación') :?>
                                    <li class="listado">
                                        <svg class="icons" viewBox="0 0 24 24">
                                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                            <path d="M17 17h-11v-14h-2"/>
                                            <path d="M6 5l14 1l-1 7h-13"/>   
                                        </svg>
                                        <p><?php  echo htmlspecialchars ($servicio['nombre_servicio']) . ' a ' . round($servicio['distancia'], 2) . ' km'; ?></p>
                                    </li>
                                <?php elseif ($servicio['tipo_servicio'] === 'sanidad') :?>
                                    <li class="listado">
                                        <svg class="icons" viewBox="0 0 24 24">    
                                            <path d="M3 21l18 0" />
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                            <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
                                            <path d="M10 9l4 0" />
                                            <path d="M12 7l0 4" />
                                        </svg>
                                        <p><?php  echo htmlspecialchars ($servicio['nombre_servicio']) . ' a ' . round($servicio['distancia'], 2) . ' km'; ?></p>
                                    </li>
                                <?php elseif($servicio['tipo_servicio'] === 'colegio') :?>
                                    <li class="listado">
                                        <svg class="icons" viewBox="0 0 24 24">
                                            <path d="M5 3v18" />
                                            <path d="M19 21v-18" />
                                            <path d="M5 7h14" />
                                            <path d="M5 15h14" />
                                            <path d="M8 13v4" />
                                            <path d="M11 13v4" />
                                            <path d="M16 13v4" />
                                            <path d="M14 5v4" />
                                            <path d="M11 5v4" />
                                            <path d="M8 5v4" />
                                            <path d="M3 21h18" />
                                        </svg>
                                        <p><?php  echo htmlspecialchars ($servicio['nombre_servicio']) . ' a ' . round($servicio['distancia'], 2) . ' km'; ?></p>
                                    </li>
                                <?php else :?>
                                    <li class="listado">
                                        <svg class="icons" viewBox="0 0 24 24">    
                                            <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                            <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                        </svg>
                                        <p><?php  echo htmlspecialchars ($servicio['nombre_servicio']) . ' a ' . round($servicio['distancia'], 2) . ' km'; ?></p>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <h3>¿Cómo es vivir en <?php echo htmlspecialchars(ucfirst($detalle['nombre_municipio'])); ?>?</h3>
                <div class="descripcionMunicipio">
                    <p>Nivel de tranquilidad: <?php echo htmlspecialchars(ucfirst($detalle['tranquilidad_municipio'])); ?>/10</p>
                    <p><?php echo htmlspecialchars(ucfirst($detalle['descripcion_municipio'])); ?></p>
                </div>
            </div>
        </div>
        
    </div>
    <?php require_once "footer.php"; ?> 
    <!--El siguiente script mete el array $fotos en un JSON para ser legible por JS para el carrusel-->
    <script>
        const fotosInmueble = <?php echo json_encode($fotos); ?>;
    </script>
    <script type="module" src="js/script.js"></script>
</body>
</html>


