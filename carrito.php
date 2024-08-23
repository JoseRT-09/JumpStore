<?php
include("conexion.php");
session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['esAdmin'])) {
    $usuario = $_SESSION['usuario'];
    $esAdmin = $_SESSION['esAdmin'];

    if ($esAdmin) {
        if (strpos($_SERVER['PHP_SELF'], 'inicioadmin.php') === false && strpos($_SERVER['PHP_SELF'], 'carrito.php') === false) {
            header("Location: inicioadmin.php");
            exit;
        }
    } else {
        if (strpos($_SERVER['PHP_SELF'], 'Inicio.php') === false && strpos($_SERVER['PHP_SELF'], 'carrito.php') === false) {
            header("Location: Inicio.php");
            exit;
        }
    }
} else {
    header("Location: Login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'eliminar' && isset($_POST['id_producto'])) {
            $idProducto = $_POST['id_producto'];
            foreach ($_SESSION['carrito'] as $key => $producto) {
                if ($producto['id'] == $idProducto) {
                    unset($_SESSION['carrito'][$key]);
                    break;
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
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="carrito.css">
    <script src="carritocont.js"></script>
</head>
<body>
    <header>
        <a href="<?php echo ($esAdmin) ? 'inicioadmin.php' : 'Inicio.php'; ?>" id="enlace-inicio"><img src="img/logo.png" alt=""></a>
    </header>

    <section class="carritob" id="contenedorprod"></section>
    <section id="totalesp">
        <p class="totales">Total: $ <span id="preciot">0</span></p>
    </section>
    <button class="comprar">Comprar</button>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cargarCarrito();

            document.querySelector('.comprar').addEventListener('click', () => {
                fetch('./comprar_productos.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let detallesCompra = '';
                        data.detallesCompra.forEach(detalle => {
                            detallesCompra += `&detalle[]=${encodeURIComponent(`${detalle.nombre}: ${detalle.cantidad}`)}`;
                        });
                        const totalGeneral = `&totalGeneral=${data.totalGeneral}`;
                        const url = `resumen_compra.html?success=true${detallesCompra}${totalGeneral}`;
                        window.location.href = url;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al comprar productos:', error);
                });
            });
        });

        function cargarCarrito() {
            fetch('tu_archivo_php.php')
                .then(response => response.json())
                .then(data => {
                    const carrito = document.getElementById("contenedorprod");
                    carrito.innerHTML = '';
                    data.forEach(producto => {
                        const fila = document.createElement("div");
                        fila.classList.add("fila-producto");
                        fila.innerHTML = `
                            <div>${producto.nombre}</div>
                            <div class="precio">$${producto.precio}</div>
                            <div class="cantidad"><input type="number" value="${producto.cantidad}" min="1" data-id="${producto.id}" onchange="actualizarCantidad(this)"></div>
                            <div class="subtotal">$${(producto.precio * producto.cantidad).toFixed(2)}</div>
                            <div><button onclick="eliminarProducto(${producto.id})">Eliminar</button></div>
                        `;
                        carrito.appendChild(fila);
                    });
                    actualizarTotales();
                });
        }

        function actualizarCantidad(input) {
            const idProducto = input.getAttribute("data-id");
            const nuevaCantidad = input.value;
            
            fetch('tu_archivo_php.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `accion=actualizar&id_producto=${idProducto}&cantidad=${nuevaCantidad}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.accion === 'actualizar') {
                    cargarCarrito();
                }
            });
        }

        function actualizarTotales() {
            const filas = document.querySelectorAll(".fila-producto");
            let total = 0;

            filas.forEach(fila => {
                const precio = parseFloat(fila.querySelector(".precio").innerText.replace('$', ''));
                const cantidad = parseInt(fila.querySelector(".cantidad input").value);
                const subtotal = precio * cantidad;
                fila.querySelector(".subtotal").innerText = `$${subtotal.toFixed(2)}`;
                total += subtotal;
            });

            document.querySelector(".totales #preciot").innerText = total.toFixed(2);
        }

        function eliminarProducto(idProducto) {
            fetch('tu_archivo_php.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `accion=eliminar&id_producto=${idProducto}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.accion === 'actualizar') {
                    cargarCarrito();
                }
            });
        }
    </script>
    <script>
        if (!document.cookie.includes("usuario") || !document.cookie.includes("contraseña")) {
            window.location.href = "Login.html";
        }
    </script>
</body>
</html>
