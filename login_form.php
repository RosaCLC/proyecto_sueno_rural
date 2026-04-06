<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>
    <form action="login.php" method="POST">
        <h3>Inicia sesión: </h3>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required>

        <label for="password">Contraseña: </label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Iniciar"> <br>

        <a href="registro_form.php">Si no estás registrado, haz click aquí.</a>
    </form>
</body>
</html>