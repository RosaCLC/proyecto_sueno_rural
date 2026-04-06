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
    <title>SUEÑO RURAL</title>
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
    <h1>Busca tu inmueble</h1>
    <form action="resultados.php" method="GET">
        
        <select name="region" id="region">
            <option value="" selected disabled>-Selecciona una región-</option>
            <option value="">Todas las regiones</option>
            <?php foreach ($regiones as $fila_region):?>
                <option value="<?php echo $fila_region['region_municipio']; ?>">
                    <?php echo $fila_region['region_municipio']; ?>
                </option>
            <?php endforeach; ?>
        </select> <br>

        <select name="tipo" id="tipo">
            <option value="" selected disabled>-Selecciona el tipo de inmueble-</option>
            <option value="">Todos los tipos</option>
            <option value="casa">Casa</option>
            <option value="piso">Piso</option>
            <option value="cabana">Cabaña</option>
            <option value="terreno">Terreno</option>
        </select> <br>

        <input type="number" name="min" placeholder="Escriba el precio mínimo">
        <input type="number" name="max" placeholder="Escriba el precio máximo">
        <br><br>

        <button type="submit" name="buscar" value="1">Buscar</button>
        <p> o </p>
        <a href="resultados.php?buscar=1">Ver todos los inmuebles</a>
    </form>
</body>
</html>