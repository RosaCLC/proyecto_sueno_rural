<?php
    session_start();
    require_once "conexionBD.php";

    if(!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario']!=='administrador'){
        header("Location: login_form.php?admin=null");
        exit();
    }

    $id_inmueble = $_GET['id'] ?? null;

    $modo_edicion = false;
    $inmueble = null;

    if($id_inmueble){
        $modo_edicion= true;

        $sql = "SELECT * FROM inmueble WHERE id_inmueble=:id";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':id', $id_inmueble, PDO::PARAM_INT);
        $consulta->execute();

        $inmueble = $consulta->fetch(PDO::FETCH_ASSOC); 
    }

    $tipo = $inmueble['tipo_inmueble'] ?? '';
    $id_municipio = $inmueble['id_municipio'] ?? '';
    $titulo = $inmueble['titulo_inmueble'] ?? '';
    $precio = $inmueble['precio_inmueble'] ?? '';
    $metros_vivienda = $inmueble['metros_vivienda'] ?? '';
    $metros_terreno = $inmueble['metros_terreno'] ?? '';
    $habitaciones = $inmueble['habitaciones_inmueble'] ?? '';
    $banos = $inmueble['banos_inmueble'] ?? '';
    $estado = $inmueble['estado_inmueble'] ?? '';
    $latitud = $inmueble['latitud_inmueble'] ?? '';
    $longitud = $inmueble['longitud_inmueble'] ?? '';
    $descripcion = $inmueble['descripcion_inmueble'] ?? '';
    $internet = $inmueble['internet_inmueble'] ?? '';
    $acceso = $inmueble['acceso_inmueble'] ?? '';
    $agua = $inmueble['agua_inmueble'] ?? '';
    $electricidad = $inmueble['electricidad_inmueble'] ?? '';
    $saneamiento = $inmueble['saneamiento_inmueble'] ?? '';

    $sql_municipios = "SELECT id_municipio, nombre_municipio, region_municipio
    FROM municipio
    ORDER BY region_municipio, nombre_municipio";

    $consulta_municipios = $conexion->query($sql_municipios);
    $municipios = $consulta_municipios->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title>
        <?php echo $modo_edicion ? 'ADMIN: Edición de inmueble' : 'ADMIN: Añadir inmueble'?>
    </title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorEstandar">
            <div class="contenedorFlex">
                <?php if ($modo_edicion): ?> 
                    <h1>Edita: <?php echo htmlspecialchars($titulo).', ID. '. $id_inmueble?></h1>
                <?php else: ?>
                    <h1>Añade un nuevo inmueble</h1>
                <?php endif ?>
                <div>
                    <form action="guardar_inmueble.php" method="POST" id="formFiltros">
                        <?php if ($modo_edicion):?>
                            <input type="hidden" name="id_inmueble" value="<?php echo $id_inmueble; ?>">
                        <?php endif ?>
                        <label for="tipo">Selecciona el tipo de inmueble:</label><br>
                        <select name="tipo" id="tipo" required>
                            <?php if (!$modo_edicion):?>
                                <option value="" selected disabled>- Tipo de inmueble -</option> 
                            <?php endif ?>
                            <option value="casa" <?php if($tipo === 'casa') echo 'selected'?>>Casa</option>
                            <option value="piso" <?php if($tipo === 'piso') echo 'selected'?>>Piso</option>
                            <option value="cabana" <?php if($tipo === 'cabana') echo 'selected'?>>Cabaña</option>
                            <option value="terreno" <?php if($tipo === 'terreno') echo 'selected'?>>Terreno</option>
                        </select><br>
                        <label for="id_municipio">Selecciona el municipio donde se ubica el inmueble:</label><br>
                        <select name="id_municipio" id="idMunicipio" required>
                            <?php if (!$modo_edicion): ?>
                                <option value="" selected disabled>- Municipio -</option>
                            <?php endif; ?>

                            <?php foreach ($municipios as $municipio): ?>
                                <option value="<?php echo $municipio['id_municipio']; ?>"
                                    <?php if ($id_municipio == $municipio['id_municipio']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($municipio['nombre_municipio'] . ' (' . $municipio['region_municipio'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>
                        <label for="titulo">Escribe un título para el inmueble:</label><br>
                        <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($titulo ?? ''); ?>" maxlength="100" placeholder="Ej. Piso con vistas al valle" required><br>
                        <label for="precio">Escribe el precio del inmueble:</label><br>
                        <input type="number" name="precio" id="precio" value="<?php echo htmlspecialchars($precio ?? '');?>" min="0" step="1" placeholder="Ej. 49000" required>
                        <span class="mensaje errorPrecio"></span><br>
                        <label for="metros_viv">Escribe los metros de la vivienda, si la tiene:</label><br>
                        <input type="number" name="metros_viv" id="metrosViv" maxlength="10" value="<?php echo htmlspecialchars($metros_vivienda ?? ''); ?>" min="0" step="1" placeholder="Ej. 110">
                        <span class="mensaje errorMetrosViv"></span><br>
                        <label for="metros_terr">Escribe los metros del terreno, si lo tiene:<label><br>
                        <input type="number" name="metros_terr" id="metrosTerr" value="<?php echo htmlspecialchars($metros_terreno ?? ''); ?>" min="0" step="1" placeholder="Ej. 350">
                        <span class="mensaje errorMetrosTerr"></span><br>
                        <label for="habitaciones">Escribe el número de habitaciones:</label><br>
                        <input type="number" name="habitaciones" id="habitaciones" value="<?php echo htmlspecialchars($habitaciones ?? ''); ?>" min="0" max="30" step="1" placeholder="Ej. 3">
                        <span class="mensaje errorHabitaciones"></span><br>
                        <label for="banos">Escribe el número de cuartos de baño:</label><br>
                        <input type="number" name="banos" id="banos" value="<?php echo htmlspecialchars($banos ?? ''); ?>" min="0" max="20" step="1" placeholder="Ej. 1">
                        <span class="mensaje errorBanos"></span><br>
                        <label for="estado">Selecciona el estado del inmueble:</label><br>
                        <select name="estado" id="estado">
                            <?php if (!$modo_edicion):?>
                                <option value="" selected disabled>- Estado del inmueble -</option> 
                            <?php endif ?>
                            <option value="" <?php if($estado === 'null') echo 'selected'?>>Es un terreno vacío, sin construcciones</option>
                            <option value="listo para entrar" <?php if($estado === 'listo para entrar') echo 'selected'?>>Listo para entrar a vivir</option>
                            <option value="necesita reformas" <?php if($estado === 'necesita reformas') echo 'selected'?>>Necesita reformas para poder habitarlo</option>
                            <option value="ruina" <?php if($estado === 'ruina') echo 'selected'?>>En ruinas</option>
                        </select><br>
                        <label for="latitud">Escribe la latitud en la que se ubica el inmueble:</label><br>
                        <input type="text" name="latitud" id="latitud" value="<?php echo htmlspecialchars($latitud ?? ''); ?>" placeholder="Ej. 41.40338" required>
                        <span class="mensaje errorLatitud"></span><br>
                        <label for="longitud">Escribe la longtitud en la que se ubica el inmueble:</label><br>
                        <input type="text" name="longitud" id="longitud" value="<?php echo htmlspecialchars($longitud ?? ''); ?>" placeholder="Ej. 41.40338" required>
                        <span class="mensaje errorLongitud"></span><br>
                        <label for="descripcion">Escribe una descripción para el inmueble:</label><br>
                        <textarea name="descripcion" id="descripcion" maxlength="1000" rows="6" placeholder="Desarrolle aquí la descripción" required><?php echo htmlspecialchars($descripcion ?? ''); ?></textarea><br>     
                        <label for="internet">Selecciona la conexión a internet disponible en el inmueble:</label><br>
                        <select name="internet" id="internet" required>
                            <?php if (!$modo_edicion):?>
                                <option value="" selected disabled>- Conexión a internet -</option> 
                            <?php endif ?>
                            <option value="no" <?php if($internet === 'no') echo 'selected'?>>No hay acceso cercano a internet </option>
                            <option value="basico" <?php if($internet === 'basico') echo 'selected'?>>Llega red básica de internet</option>
                            <option value="alta velocidad" <?php if($internet === 'alta velocidad') echo 'selected'?>>Fibra óptica de internet</option>
                        </select><br>
                        <label for="acceso">Selecciona el acceso que tiene el inmueble:</label><br>
                        <select name="acceso" id="acceso" required>
                            <?php if (!$modo_edicion):?>
                                <option value="" selected disabled>- Tipo de acceso -</option> 
                            <?php endif ?>
                            <option value="directo" <?php if($acceso === 'directo') echo 'selected'?>>Acceso directo en coche</option>
                            <option value="4 por 4" <?php if($acceso === '4 por 4') echo 'selected'?>>Sólo acceso con 4x4</option>
                            <option value="camino" <?php if($acceso === 'camino') echo 'selected'?>>Sólo acceso andando por camino</option>
                        </select>
                        <p>Sólo marcar lo que haya disponible, si no, dejar sin marcar:</p>
                        <input type="checkbox" id="agua" name="agua" value="1" <?php if($agua == '1') echo 'checked'?>>
                        <label for="agua">Dispone de agua</label><br>
                        <input type="checkbox" id="electricidad" name="electricidad" value="1" <?php if($electricidad == '1') echo 'checked'?>>
                        <label for="electricidad">Dispone de electricidad</label><br>
                        <input type="checkbox" id="saneamiento" name="saneamiento" value="1" <?php if($saneamiento == '1') echo 'checked'?> >
                        <label for="saneamiento">Dispone de conexión a alcantarillado o fosa séptica</label>
                        
                        <br><br>

                    <button type="submit">
                        <?php echo $modo_edicion ? 'Guardar cambios' : 'Añadir inmueble'; ?>
                    </button>
                    </form>
                </div>    
                
            </div>
        
        </div>
    </div>
    <?php require_once "footer.php"; ?>    
    <script type="module" src="js/script.js"></script>
</body>
</html>