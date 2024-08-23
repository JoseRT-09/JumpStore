<?php
include("conexion.php");

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Actualizar el campo 'activo' a 1 para el correo electrónico proporcionado
    $consulta = "UPDATE r_usuarios SET activo = 1 WHERE correo = '$email'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        echo "Cuenta activada correctamente. Ahora puede iniciar sesión.";
    } else {
        echo "Error al activar la cuenta. Por favor, intente nuevamente.";
    }
} else {
    echo "Enlace de activación inválido.";
}
?>