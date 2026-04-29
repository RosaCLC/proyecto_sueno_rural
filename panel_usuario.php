<?php
    session_start();
    require_once "conexionBD.php";

    if(!isset($_SESSION['id_usuario'])){
    header("Location: login_form.php");
    exit();
    }

    $id_usuario = $_SESSION['id_usuario'];
    $rol_usuario = $_SESSION['rol_usuario']
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css\styles.css">
        <title>Panel de <?php echo htmlspecialchars ($_SESSION['rol_usuario']).': '.htmlspecialchars ($_SESSION['nombre_usuario']) ?></title>
    </head>
    <body>
        <?php require_once "header.php"?>
        <div class="pagina">
            <div class="contenedorUsuarios">
                <div class="fotoPanel">
                    <img src="" alt="">
                </div>
                <div >
                    <h1>¡Hola, <?php echo htmlspecialchars ($_SESSION['nombre_usuario']) ?>!</h1>
                    <p class="aviso">Este es tu panel de <?php echo htmlspecialchars ($_SESSION['rol_usuario'])?>:</p>
                    <?php if($_SESSION['rol_usuario'] === 'usuario'):?>
                        <p class="aviso">Desde aquí podrás acceder a tus favoritos y gestionar los inmuebles que te interesan. Poco a poco se irán añadiendo nuevas funcionalidades para mejorar tu experiencia.</p>
                    <?php else: ?> 
                        <p class="aviso">Desde este panel de administración podrás gestionar los inmuebles publicados, así como crear, editar o eliminar registros. Este espacio se irá ampliando con nuevas herramientas para facilitar la gestión del sistema.</p>
                    <?php endif;?>
                </div>
                <div class="panelUsuarios">
                    <?php if($_SESSION['rol_usuario'] === 'usuario'):?>
                        <div >
                            <a class="botonSimulado" href="lista_favoritos.php">Gestionar favoritos</a>
                        </div>
                    <?php else: ?>   
                        <div>
                            <a class="botonSimulado" href="inmueble_form.php">Añadir nuevo inmueble</a>
                        </div>
                        <div>
                            <a class="botonSimulado" href="gestion_inmuebles.php">Gestionar inmuebles</a>
                        </div>
                    <?php endif;?>
                </div>

            </div>

        </div>
        <?php require_once "footer.php"; ?>    
    </body>
    </html>