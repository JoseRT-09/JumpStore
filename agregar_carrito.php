<?php
include("conexion.php");
session_start();

if (isset($_POST['agregarprod'])) {
    if (!empty($_POST['nombreprod']) && !empty($_POST['desc']) && !empty($_POST['precio']) && !empty($_POST['cantidadprod'])) {
        $nombreprod = $_POST["nombreprod"];
        $descripcion = $_POST["desc"];
        $precio = $_POST["precio"];
        $cantidad = $_POST["cantidadprod"];
        $activo = isset($_POST["activo"]) ? "1" : "0";
        $consulta = "INSERT INTO productos (nombre, descripcion, precio, cantidad, activo) VALUES ('$nombreprod', '$descripcion', $precio, $cantidad, $activo)";
        $envio = mysqli_query($conexion, $consulta);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'eliminar' && isset($_POST['ids'])) {
            $idsProductos = explode(',', $_POST['ids']);
            foreach ($idsProductos as $idProducto) {
                foreach ($_SESSION['carrito'] as $key => $producto) {
                    if ($producto['id'] == $idProducto) {
                        unset($_SESSION['carrito'][$key]);
                        break;
                    }
                }
            }
            echo json_encode(['accion' => 'actualizar', 'carrito' => array_values($_SESSION['carrito'])]);
            exit;
        } elseif ($_POST['accion'] === 'actualizar' && isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
            $idProducto = $_POST['id_producto'];
            $cantidad = $_POST['cantidad'];
            foreach ($_SESSION['carrito'] as &$producto) {
                if ($producto['id'] == $idProducto) {
                    $producto['cantidad'] = $cantidad;
                    break;
                }
            }
            echo json_encode(['accion' => 'actualizar', 'carrito' => array_values($_SESSION['carrito'])]);
            exit;
        }
    } else {
        $idProducto = $_POST['id_producto'];
        $precioProducto = $_POST['precio'];
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }
        $productoExistente = false;
        foreach ($_SESSION['carrito'] as &$producto) {
            if ($producto['id'] == $idProducto) {
                $producto['cantidad']++;
                $productoExistente = true;
                break;
            }
        }
        if (!$productoExistente) {
            $consulta = "SELECT id, nombre, descripcion, precio, ruta_imagen FROM productos WHERE id = $idProducto";
            $resultado = mysqli_query($conexion, $consulta);
            $fila = mysqli_fetch_assoc($resultado);
            if ($fila) {
                $nuevoProducto = array(
                    'id' => $fila['id'],
                    'nombre' => $fila['nombre'],
                    'descripcion' => $fila['descripcion'],
                    'precio' => $fila['precio'],
                    'cantidad' => 1,
                    'ruta_imagen' => $fila['ruta_imagen']
                );
                $_SESSION['carrito'][] = $nuevoProducto;
            }
        }
        $cantidadProductos = count($_SESSION['carrito']);
        echo json_encode(['cantidad' => $cantidadProductos]);
    }
} else {
    if (isset($_SESSION['carrito'])) {
        echo json_encode($_SESSION['carrito']);
    } else {
        echo json_encode([]);
    }
}
?>
