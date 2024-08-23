<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        $detallesCompra = array();
        $totalGeneral = 0;
        $compraExitosa = true;
        $idUsuario = "'" . $_SESSION['usuario'] . "'";

        foreach ($_SESSION['carrito'] as $producto) {
            $idProducto = $producto['id'];
            $cantidadProducto = $producto['cantidad'];
            $subtotalProducto = $producto['precio'] * $cantidadProducto;
            $detallesCompra[] = array(
                'nombre' => $producto['nombre'],
                'cantidad' => $cantidadProducto,
                'subtotal' => $subtotalProducto
            );
        }

        foreach ($detallesCompra as $detalleCompra) {
            $nombreProducto = $detalleCompra['nombre'];
            $cantidadProducto = $detalleCompra['cantidad'];
            $subtotal = $detalleCompra['subtotal'];

            // Buscar el producto en el carrito para obtener su ID
            $productoEnCarrito = array_filter($_SESSION['carrito'], function($producto) use ($nombreProducto) {
                return $producto['nombre'] === $nombreProducto;
            });

            if (!empty($productoEnCarrito)) {
                $productoEnCarrito = array_shift($productoEnCarrito);
                $idProducto = $productoEnCarrito['id'];

                $consultaExistencia = "SELECT cantidad FROM productos WHERE id = $idProducto";
                $resultadoExistencia = mysqli_query($conexion, $consultaExistencia);
                $filaExistencia = mysqli_fetch_assoc($resultadoExistencia);

                if ($filaExistencia && $filaExistencia['cantidad'] >= $cantidadProducto) {
                    $nuevaExistencia = $filaExistencia['cantidad'] - $cantidadProducto;
                    $consultaActualizarExistencia = "UPDATE productos SET cantidad = $nuevaExistencia WHERE id = $idProducto";
                    mysqli_query($conexion, $consultaActualizarExistencia);

                    $totalGeneral += $subtotal;

                    // Insertamos el producto en la tabla ventas
                    $consultaInsertarVenta = "INSERT INTO ventas (id_usuario, id_producto, cantidad, total, fecha) VALUES ($idUsuario, $idProducto, $cantidadProducto, $subtotal, NOW())";
                    mysqli_query($conexion, $consultaInsertarVenta);
                } else {
                    $compraExitosa = false;
                    break;
                }
            } else {
                $compraExitosa = false;
                break;
            }
        }

        if ($compraExitosa) {
            unset($_SESSION['carrito']);
            echo json_encode(array(
                'success' => true,
                'detallesCompra' => $detallesCompra,
                'totalGeneral' => $totalGeneral
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Algunos productos no están disponibles en la cantidad solicitada.'
            ));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'No hay productos en el carrito.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Acceso no autorizado.'));
}
?>