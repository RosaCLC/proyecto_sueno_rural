<?php
    session_start();
    require_once "conexionBD.php";

    $sql_region = "SELECT DISTINCT m.region_municipio
    FROM municipio m JOIN inmueble i
    WHERE m.id_municipio = i.id_municipio
    ";

    $consulta_region = $conexion->query($sql_region);
    $regiones = $consulta_region->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title>SUEÑO RURAL</title>
</head>
<body>
    <?php require_once "header.php"?>
    <section class="hero">
        <div class="sobrehero">
            <h1 class="tituloHero">Encuentra el hogar de tus sueños</h1>
            <div class="contenedorBuscador">
                <div class="buscador">
                    <h2>Busca un inmueble:</h2>
                    <form id="buscador"  action="<?php echo (isset($_SESSION['rol_usuario']) && $_SESSION['rol_usuario'] === 'administrador') 
                    ? 'gestion_inmuebles.php'
                    :'lista_inmuebles.php'?>" method="GET">
                        <select name="region" id="region">
                            <option value="" selected disabled>Selecciona una región</option>
                            <option value="">Todas las regiones</option>
                            <?php foreach ($regiones as $fila_region):?>
                                <option value="<?php echo $fila_region['region_municipio']; ?>">
                                    <?php echo $fila_region['region_municipio']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select> <br>

                        <select name="tipo" id="tipo">
                            <option value="" selected disabled>Selecciona el tipo de inmueble</option>
                            <option value="">Todos los tipos</option>
                            <option value="casa">Casa</option>
                            <option value="piso">Piso</option>
                            <option value="cabana">Cabaña</option>
                            <option value="terreno">Terreno</option>
                        </select> <br>

                        <input type="number" id="precioMin" name="min" min="0" step="1" placeholder="Precio mínimo">                    
                        <input type="number" id="precioMax" name="max" min="0" step="1" placeholder="Precio máximo"><br>
                        <span class="mensaje errorPrecioMin errorPrecioMax"></span>
                        <br><br>

                        <button type="submit" name="buscar" value="1">Buscar</button>
                        <a class="enlaces" href="<?php echo (isset($_SESSION['rol_usuario']) && $_SESSION['rol_usuario'] === 'administrador') 
                        ? 'gestion_inmuebles.php'
                        :'lista_inmuebles.php'?>?buscar=1">Ver todos los inmuebles</a>
                    </form>
                </div>
                
            </div>
            
        </div>
    </section>
    <script type="module" src="js/script.js"></script>
</body>
</html>