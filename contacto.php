<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title>Contacta con Sueño Rural</title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorEstandar">
            <div class="fotoContacto">
                <img src="img\fotoContacto.jpg" alt="Paisaje abierto">
            </div>
            <div class="contactoForm">
                <h1>Contacta con nosotros</h1>
                <p>
                    ¿Te ha gustado algún inmueble? Estaremos encantados de concertar una cita contigo para enserñarte todo al detalle. <br>
                    Llámanos al teléfono: 
                </p>
                    <span class="telefono">912 345 678</span><br>
                <p>
                    O contáctanos mediante el siguiente formulario ya sea para obtener información o para informar de alguna incidencia.
                </p>
                    
                <form class="formulario" id="formLogin" action="#" method="POST">
                    <input type="text" name="nombre" id="nombre" required placeholder="Nombre">
                    <div class="mensaje errorNombre"></div><br>
                    <input type="email" name="email" id="email" required placeholder="Email">
                    <input type="text" name="asunto" id="nombre" required placeholder="Asunto del mensaje">
                    <div class="mensaje errorEmail"></div><br>
                    <textarea name="mensajeContacto" rows="6" placeholder="Escriba su mensaje aquí" required></textarea>
                <br><br><br>

                <button type="submit">Enviar</button>
            </form>
            </div>
        </div>
    </div>
    <?php require_once "footer.php"; ?> 
    <script type="module" src="js/script.js"></script>
</body>
</html>