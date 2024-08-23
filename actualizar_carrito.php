<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['carrito'])) {
        $idProducto = $_POST['id'];
        $nuevaCantidad = $_POST['cantidad'];

        foreach ($_SESSION['carrito'] as &$producto) {
            if ($producto['id'] == $idProducto) {
                $producto['cantidad'] = $nuevaCantidad;
                break;
            }
        }

        echo json_encode(array('success' => true, 'carrito' => $_SESSION['carrito']));
    } else {
        echo json_encode(array('success' => false, 'message' => 'No hay productos en el carrito.'));
    }
}
?>