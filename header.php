<?php 

?>
<header>
    <div class="logo">
        <a href="index.php">
            <img src="img\LogoSR2-sf tamaño.png" alt="Logotipo Sueño Rural">
        </a>
    </div>
    <div class="menuSesion">
        <nav class="menu">
          <ul>
              <li><a href="index.php">INICIO</a></li>
              <li><a href="quienes_somos.php">QUIÉNES SOMOS</a></li>
              <li><a href="<?php echo (isset($_SESSION['rol_usuario']) && $_SESSION['rol_usuario'] === 'administrador') 
              ? 'gestion_inmuebles.php'
              :'lista_inmuebles.php'?>">INMUEBLES</a>
              <li><a href="">CALCULA REFORMA</a></li>
              <li><a href="contacto.php">CONTACTO</a></li>
              </li>
          </ul>
        </nav>
    </div>
    <div class="sesion">
        <?php if(isset($_SESSION['id_usuario'])):?>
            <p>
                <?php if($_SESSION['rol_usuario']!=='administrador'):?>
                    ¡Hola, <a class="enlaces" href="panel_usuario.php"><?php echo htmlspecialchars($_SESSION['nombre_usuario']);?></a>!<br>
                <?php else:?>
                    <a class="enlaces" href="panel_usuario.php">MODO ADMIN</a><br>
                <?php endif;?>    
                <a class= "salirSesion" href="logout.php">Cerrar sesión</a>
            </p>
        <?php else:?>
            <a class="botonSimulado" href="login_form.php">Inicia sesión</a>
        <?php endif; ?>   
    </div>
    
</header>