<?php
session_start();
include("conexion.php");

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];

    // Obtener el nombre y el correo del usuario a partir de la base de datos
    $consultaUsuario = "SELECT n_usuario, correo FROM r_usuarios WHERE n_usuario = '$usuario'";
    $resultadoUsuario = mysqli_query($conexion, $consultaUsuario);

    if (mysqli_num_rows($resultadoUsuario) == 1) {
        $filaUsuario = mysqli_fetch_assoc($resultadoUsuario);
        $nombreUsuario = $filaUsuario['n_usuario'];
        $correoUsuario = $filaUsuario['correo'];
    } else {
        // Manejar el caso en el que no se encuentre el usuario en la base de datos
        $nombreUsuario = '';
        $correoUsuario = '';
    }
} else {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: Login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contacto</title>
    <link rel="stylesheet" href="cssmenus.css" />
    <script src="aplicarconf.js"></script>
</head>
<body>
    <header>
        <a href="Inicio.php"><img src="img/logo.png" alt="" /></a>
        <h1>CONTACTANOS</h1>
    </header>
    <form action="enviar.php" method="post" class="registro">
        <div class="cont">
            <label for="nombrec">Nombre</label>
            <input type="text" name="cnombre" id="nombrec" value="<?php echo $nombreUsuario; ?>" required />
        </div>
        <div class="cont">
            <label for="correoc">Correo</label>
            <input type="email" name="cCorreo" id="correoc" value="<?php echo $correoUsuario; ?>" required />
        </div>
        <div class="cont">
            <label for="mensajec">Mensaje</label>
            <textarea name="mensajeenvio" id="usu" rows="5" required></textarea>
        </div>
        <button class="boton" name="mensaje">Enviar</button>
        <button class="boton" name="eliminar">Limpiar</button>
    </form>
    <footer>
        <div class="redes-container">
            <a href="https://www.instagram.com/"><img class="redes" src="img/instagram.png" /></a>
            <a href="https://web.whatsapp.com/"><img class="redes" src="img/whatsapp.png" /></a>
            <a href="https://twitter.com/?lang=es"><img class="redes" src="img/twitter.png" /></a>
        </div>
    </footer>
</body>
</html>