<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_producto'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['cantidad'])) {
        $idProducto = $_POST['id_producto'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        
        $consulta = "UPDATE productos SET nombre=?, descripcion=?, precio=?, cantidad=? WHERE ID=?";
        if ($stmt = $conexion->prepare($consulta)) {
            $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $cantidad, $idProducto);
            if ($stmt->execute()) {
                echo json_encode(['exito' => true]);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al guardar los cambios del producto.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Error al preparar la consulta.']);
        }
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'No se proporcionaron todos los datos necesarios.']);
    }
}

$conexion->close();

