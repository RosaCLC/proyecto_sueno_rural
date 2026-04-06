<?php
    session_start();
    require_once "conexionBD.php";

    if ($_SERVER ["REQUEST_METHOD"] == "POST"){

        $nombre = trim($_POST["nombre"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $rol = "usuario"; //por defecto sólo se registran usuarios

        if(empty($nombre) || empty($email) || empty($password)){
            die ("Han de rellenarse todos los campos.");
        }

        //protección de contraseña para la bbdd
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql= "INSERT INTO usuario (nombre_usuario, email_usuario, password_usuario, rol_usuario) VALUES (:nombre, :email, :password, :rol)";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed);
            $stmt->bindParam(":rol",$rol);

            $stmt-> execute();

            //si el registro es exitoso envía al formulario de login
            header("Location: login_form.php");
            exit();

        } catch (PDOException $e){
            echo "Error: " .$e->getMessage();
        }

    }

?>