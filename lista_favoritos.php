<?php
    session_start();
    require_once "conexionBD.php";

    if(!isset($_SESSION['id_usuario'])){
    header("Location: login_form.php");
    exit();
    }

    $id_usuario = $_SESSION['id_usuario'];

    $sql_favorito = "SELECT f.*, i.*, m.nombre_municipio, m.region_municipio,  ft.url_foto, ft.descripcion_foto
    FROM favorito f 
    JOIN inmueble i ON f.id_inmueble = i.id_inmueble
    JOIN municipio m ON i.id_municipio = m.id_municipio
    LEFT JOIN foto ft ON ft.id_foto = (
        SELECT MIN(ff.id_foto)
        FROM foto ff
        WHERE ff.id_inmueble = i.id_inmueble
    )
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
    <link rel="stylesheet" href="css\styles.css">
    <title>Favoritos de <?php echo htmlspecialchars ($_SESSION['nombre_usuario'])?> </title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorInmuebles">
            <?php if(isset($_GET['borrar']) && $_GET['borrar'] == 'ok'): ?>
                <div class="card noResult">
                    <p class="aviso">Inmueble eliminado correctamente de la lista de favoritos.</p>
                </div>
            <?php endif;?>
            <h1>Tus favoritos:</h1>
            <div class="contenedorCards">               
                <?php if(count($favoritos)>0): ?>
                    <?php foreach ($favoritos as $favorito): ?>
                        <div class="card favoritoCard">
                            <div class="fotoCard"><img src="<?php echo htmlspecialchars($favorito['url_foto'])?>" alt="<?php echo htmlspecialchars($favorito['descripcion_foto'])?>"></div>
                            <div class="infoCard">
                                <div class="topCard">
                                    <h3 class="tituloCard"><?php echo htmlspecialchars($favorito['titulo_inmueble']);?></h3>
                                    <p class="precioCard"><?php echo htmlspecialchars(number_format($favorito['precio_inmueble'],0,',','.')). ' €'; ?></p>
                                </div>
                                <div class="ubicacionCard">
                                    <p class="lugar"><?php echo htmlspecialchars($favorito['nombre_municipio']) .' ('. htmlspecialchars($favorito['region_municipio']).')' ; ?></p>
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
                                        <p><?php echo !empty($favorito['metros_vivienda']) ? htmlspecialchars($favorito['metros_vivienda']) . ' m² de vivienda' : '-'?></p>
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
                                        <p><?php echo !empty($favorito['metros_terreno']) ? htmlspecialchars($favorito['metros_terreno']) . ' m² de terreno' : 'No dispone de terreno'?></p>
                                    </div>
                                    <div class="num-habitaciones">
                                        <svg class="icons" viewBox="0 0 24 24">
                                            <path d="M7 9m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                            <path d="M22 17v-3h-20"/>
                                            <path d="M2 8v9"/>
                                            <path d="M12 14h10v-2a3 3 0 0 0 -3 -3h-7v5z"/>
                                        </svg>
                                        <p>
                                            <?php if(!empty($favorito['habitaciones_inmueble'])){
                                                $num= $favorito['habitaciones_inmueble'];
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
                                            <?php if(!empty($favorito['banos_inmueble'])){
                                                $num= $favorito['banos_inmueble'];
                                                echo htmlspecialchars($num).' '. ($num==1 ? 'cuarto de baño' : 'cuartos de baño');
                                            } else { echo '-';}?>
                                        </p>
                                    </div>
                                    <div class="estado-viv">
                                        <svg class="icons 
                                        <?php if($favorito['tipo_inmueble'] !== 'terreno'){
                                                $estado_inm= $favorito['estado_inmueble'];
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
                                            <?php if($favorito['tipo_inmueble'] !== 'terreno'){
                                                $estado_inm= $favorito['estado_inmueble'];
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
                                    <a class="enlaces enlaceFav" href="detalles_inmueble.php?id=<?php echo $favorito['id_inmueble']; ?>">Ver más detalles del inmueble</a>
                                    <div class="quitarFav">
                                        <a class="enlaces" href="quitar_favorito.php?id_favorito=<?php echo $favorito['id_favorito'];?>&volver=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
                                        Quitar favorito
                                        <svg class="icons trash" viewBox="0 0 24 24">
                                            <path d="M4 7h16"/>
                                            <path d="M10 11v6"/>
                                            <path d="M14 11v6"/>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                        </svg>
                                        </a>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card noResult">
                        <p class="aviso">
                            Tu lista de favoritos está vacía. <br>
                            Echa un vistazo a <a class="enlaces" href="lista_inmuebles.php">nuestros inmuebles</a> y guarda los que más te gusten.
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