<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];
    $consulta = "SELECT * FROM productos WHERE ID = $idProducto";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        echo json_encode(array('exito' => true, 'producto' => $producto));
    } else {
        echo json_encode(array('exito' => false));
    }
} else {
    echo json_encode(array('exito' => false));
}
