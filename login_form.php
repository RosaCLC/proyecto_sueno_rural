<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\styles.css">
    <title>Inicio de sesión</title>
</head>
<body>
    <?php require_once "header.php"?>
    <div class="pagina">
        <div class="contenedorForm">
            <div class="bloqueInfo">
                <?php if(isset($_GET['registro']) && $_GET['registro'] == 'ok'): ?>
                    <p class="aviso exito">¡Registro realizado con éxito! Ya puedes iniciar sesión.</p>
                <?php elseif(isset($_GET['admin']) && $_GET['admin'] == 'null'): ?>
                    <p class="aviso exito">ACCESO DENEGADO como administrador. Introduzca sus credenciales.</p>
                <?php endif; ?>
                <h2>Accede a tu cuenta</h2>
                <p>
                    Guarda tus favoritos y gestiona tus búsquedas fácilmente.
                </p>
            </div>
            <form class="formulario" id="formLogin" action="login.php" method="POST">
                <h3>Inicia sesión: </h3>
                <input type="email" name="email" id="email" required placeholder="Email">
                <div class="mensaje errorEmail"></div><br>
                <input type="password" name="password" id="password" required placeholder="Contraseña">
                <?php if(isset($error)): ?>
                    <p class="mensaje"><?php echo $error; ?></p>
                <?php endif; ?>
                <br><br><br>

                <input type="submit" value="Iniciar">

                <a class="enlaces" href="registro_form.php">Si no estás registrado, haz click aquí</a>
            </form>
        </div>
    </div>
    <?php require_once "footer.php"; ?> 
    <script type="module" src="js/script.js"></script>
</body>
</html>