<?php
include("conexion.php");

$consulta = "SELECT n_usuario, contraseña FROM r_usuarios";
$resultado = mysqli_query($conexion, $consulta);

$usuarios = array();
if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $usuarios[] = "Usuario: " . $fila['n_usuario'] . ", Contraseña: " . $fila['contraseña'];
    }
} else {
    $usuarios[] = "No hay usuarios registrados.";
}

$usuariosJSON = json_encode($usuarios);
echo $usuariosJSON;

mysqli_close($conexion);