<?php
include("conexion.php");

$query = "SELECT nombre, edad, correo, telefono, contraseña, r_contraseña FROM r_usuarios WHERE n_usuario = 'admin'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nombre = $row['nombre'];
    $edad = $row['edad'];
    $correo = $row['correo'];
    $telefono = $row['telefono'];
    $contraseña = $row['contraseña'];
    $r_contraseña = $row['r_contraseña'];
} else {
    echo "Error: No se encontró el usuario administrador.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rnombre = $_POST['rnombre'];
    $redad = $_POST['redad'];
    $rcorreo = $_POST['rcorreo'];
    $rtelefono = $_POST['rtelefono'];
    $rcontraseña = $_POST['rcontraseña'];
    $rccontraseña = $_POST['rccontraseña'];

    if ($rcontraseña != $rccontraseña) {
        echo "Error: Las contraseñas no coinciden.";
    } else {
        $update_query = "UPDATE r_usuarios SET nombre = '$rnombre', edad = $redad, correo = '$rcorreo', telefono = '$rtelefono', contraseña = '$rcontraseña', r_contraseña = '$rccontraseña' WHERE n_usuario = 'admin'";
        if (mysqli_query($conexion, $update_query)) {
            echo "<script>alert('Datos actualizados'); setTimeout(function() { window.location.href = 'Login.html'; }, 500);</script>";
            header("Location: modificar_admin.php");
            exit;
        } else {
            echo "Error al actualizar los datos: " . mysqli_error($conexion);
        }
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modificar Administrador</title>
    <link rel="stylesheet" href="cssmenus.css" />
    <script src="aplicarconf.js"></script>
    <style>
        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <a href="inicioadmin.php"><img src="img/logo.png" alt="" /></a>
    </header>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="registro">
        <div class="contenedor">
            <div class="izq">
                <div class="reg">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="rnombre" id="nombre" value="<?php echo $nombre; ?>" required />
                </div>
                <div class="reg">
                    <label for="edad">Edad</label>
                    <input type="number" name="redad" id="edad" min="0" max="110" value="<?php echo $edad; ?>" required />
                </div>
                <div class="reg">
                    <label for="correo">Correo</label>
                    <input type="email" name="rcorreo" id="correo" value="<?php echo $correo; ?>" required />
                </div>
                <div class="reg">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" name="rtelefono" id="telefono" value="<?php echo $telefono; ?>" required />
                </div>
            </div>
            <div class="der">
                <div class="reg password-container">
                    <label for="contraseña">Contraseña</label>
                    <input type="password" name="rcontraseña" id="contraseña" value="<?php echo $contraseña; ?>" required />
                    <span class="toggle-password" onclick="togglePasswordVisibility('contraseña')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div class="reg password-container">
                    <label for="Ccontrasña">Confirmar contraseña</label>
                    <input type="password" name="rccontraseña" id="Ccontrasña" value="<?php echo $r_contraseña; ?>" required />
                    <span class="toggle-password" onclick="togglePasswordVisibility('Ccontrasña')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
        </div>
        <button class="boton" name="actualizaradmin">Actualizar</button>
    </form>
    <footer>
        <div class="redes-container">
            <a href="https://www.instagram.com/"><img class="redes" src="img/instagram.png" /></a>
            <a href="https://web.whatsapp.com/"><img class="redes" src="img/whatsapp.png" /></a>
            <a href="https://twitter.com/?lang=es"><img class="redes" src="img/twitter.png" /></a>
        </div>
        <div class="soporte-container">
            <a href="Contacto.html">
                <span class="soporte-texto">Soporte Técnico</span>
                <img class="redes" src="img/apoyo.png" />
            </a>
        </div>
    </footer>

    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);
            var toggleIcon = passwordInput.nextElementSibling.querySelector('i');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>