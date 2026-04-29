<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title>¿Quiénes somos? Sueño Rural</title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorEstandar">
            <div class="infoSomos">
                <h1>Quiénes somos</h1>
                <p>
                    Sueño Rural es una plataforma orientada a facilitar la búsqueda de inmuebles en entornos rurales. Desde la experiencia personal de la desarrolladora de la página, se presta atención a aspectos que suelen ser importantes para quienes desean trasladarse al campo o adquirir una vivienda en una zona tranquila.
                </p>

                <p>
                    El proyecto nace con la idea de ofrecer información útil y clara sobre los inmuebles, incluyendo no solo sus características básicas, sino también datos relevantes sobre el entorno, la disponibilidad de servicios y otros aspectos que influyen en la toma de decisiones.
                </p>

                <p>
                    A diferencia de otros portales generalistas, Sueño Rural busca poner el foco en las necesidades concretas de las personas interesadas en un estilo de vida más cercano a la naturaleza y a los pequeños núcleos de población.
                </p>
                <p>
                    Esta aplicación ha sido desarrollada como proyecto del ciclo de Desarrollo de Aplicaciones Web, integrando base de datos, backend en PHP y una interfaz orientada a la experiencia de usuario.
                </p>
            </div>
            <div class="fotoSomos">
                <img src="img\fotoSomos.jpg" alt="Pueblo desde camino">
            </div>
        </div>
    </div>
    <?php require_once "footer.php"; ?> 
</body>
</html>