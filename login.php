<?php
    session_start();

    require_once "conexionBD.php";

    if ($_SERVER ["REQUEST_METHOD"] == "POST"){
        
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        /*Error que se mostrará si se envía el formulario vacío*/
        if(empty($email) || empty($password)){
            $error = "Han de rellenarse ambos campos.";
        }

        try{
            
            $sql = "SELECT * FROM usuario WHERE email_usuario = :email";

            //declaración PDO para preparar la consulta, se asocia al email y se ejecuta
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            //se recoge el resultado de la búsqueda
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            //si se verifican usuario y contraseña ingresada, se guardan datos en sesiones.
            if ($usuario && password_verify($password, $usuario["password_usuario"])){
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION ['rol_usuario'] = $usuario ['rol_usuario'];

                //Con el login exitoso, te dirige a la página principal
                header("Location: index.php");
                exit();

            } else {
                echo "Email o contraseña incorrectos.";
            }
        } catch (PDOException $e){
            echo "Error: " .$e->getMessage();
        }

    }
?>