<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title>Registro de usuarios</title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorForm">
            <div class="bloqueInfo">
                <h2>Únete a Sueño Rural</h2>
                <p>
                    Crea una cuenta para guardar tus inmuebles favoritos.<br>
                    Próximamente se añadirán nuevas funcionalidades.
                </p>
            </div>
            <form class="formulario" id="formRegistro" action="registro.php" method="POST">
                <h3>Regístrate: </h3>
                <input type="text" name="nombre" id="nombre" required placeholder="Nombre">
                <div class="mensaje errorNombre"></div><br>
                <input type="email" name="email" id="email" required placeholder="Correo">
                <div class="mensaje errorEmail"></div><br>
                <input type="password" name="password" id="password" required placeholder="Contraseña">
                <div class="mensaje errorPassword"></div><br>
                <input type="password" name="repetirPassword" id="repetirPassword" required placeholder="Repita contraseña">
                <div class="mensaje errorRepetir"></div><br>
                <?php if(isset($error)): ?>
                    <p class="mensaje"><?php echo $error; ?></p>
                <?php endif; ?>
                <br>

                <input class="botonRegistro" type="submit" value="Registrarse">
            </form>
        </div>
    </div>
    <?php require_once "footer.php"; ?>         
    <script type="module" src="js/script.js"></script>
</body>
</html>