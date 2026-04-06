<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuarios</title>
</head>
<body>
    <form action="registro.php" method="POST">
        <h3>Regístrate: </h3>
        <label for="nombre">Nombre: </label>
        <input type="text" name="nombre" id="nombre" required>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required>

        <label for="password">Contraseña: </label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Registrarse"> <br>
    </form>
</body>
</html>