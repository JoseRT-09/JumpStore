<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $n_usuario = $_POST['n_usuario'];
    $nuevoEstado = $_POST['nuevoEstado'];

    $update_query = "UPDATE r_usuarios SET activo = '$nuevoEstado' WHERE n_usuario = '$n_usuario'";
    $resultado = mysqli_query($conexion, $update_query);

    if ($resultado) {
        echo "OK";
    } else {
        echo "Error al actualizar el estado del usuario";
    }
} else {
    echo "Acceso no autorizado";
}

mysqli_close($conexion);
?>
