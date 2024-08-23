<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_producto'])) {
        $idProducto = $_POST['id_producto'];
        $accion = isset($_POST['accion']) ? $_POST['accion'] : 'deshabilita';

        if ($accion === 'habilita') {
            $consulta = "UPDATE productos SET activo = 1 WHERE ID = ?";
        } else if($accion === 'deshabilita'){
            $consulta = "UPDATE productos SET activo = 0 WHERE ID = ?";
        }

        if ($stmt = $conexion->prepare($consulta)) {
            $stmt->bind_param("i", $idProducto);
            if ($stmt->execute()) {
                echo json_encode(['exito' => true]);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al ' . ($accion === 'habilitar' ? 'habilitar' : 'deshabilitar') . ' el producto.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Error al preparar la consulta.']);
        }
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'No se proporcionó id_producto.']);
    }
}

$conexion->close();