<?php
include("conexion.php");

$productosPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $productosPorPagina;

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

if (!empty($buscar)) {
    $consulta = "SELECT * FROM productos WHERE activo = 1 AND nombre LIKE '%$buscar%' LIMIT $offset, $productosPorPagina";
} else {
    $consulta = "SELECT * FROM productos WHERE activo = 1 LIMIT $offset, $productosPorPagina";
}

$envio = $conexion->query($consulta);

if ($envio->num_rows > 0) {
    echo "<section id='contenedorprod'>";
    while ($row = $envio->fetch_assoc()) {
        echo "<div class='producto'>";
        echo "<img src='" . $row["ruta_imagen"] . "' alt='" . $row["nombre"] . "'>";
        echo "<h3>" . $row["nombre"] . "</h3>";
        echo "<h2>" . $row["descripcion"] . "</h2>";
        echo "<p>$" . $row["precio"] . "</p>";
        echo "<button class='agregar-carrito' data-id='" . $row["ID"] . "' data-precio='" . $row["precio"] . "'><img src='img\\agregar.png'></button>";
        echo "</div>";
    }
    echo "</section>";

    $totalConsulta = "SELECT COUNT(*) as total FROM productos WHERE activo = 1";
    if (!empty($buscar)) {
        $totalConsulta .= " AND nombre LIKE '%$buscar%'";
    }
    $resultado = $conexion->query($totalConsulta);
    $fila = $resultado->fetch_assoc();
    $totalProductos = $fila['total'];
    $totalPaginas = ceil($totalProductos / $productosPorPagina);

    echo "<div class='paginacion'>";
    for ($i = 1; $i <= $totalPaginas; $i++) {
        echo "<a href='?pagina=$i&buscar=$buscar'>$i</a>";
    }
    echo "</div>";
} else {
    echo "No se encontraron productos.";
}
$conexion->close();
?>

<script>
const botonesAgregarCarrito = document.querySelectorAll('.agregar-carrito');
botonesAgregarCarrito.forEach(boton => {
    boton.addEventListener('click', () => {
        const idProducto = boton.dataset.id;
        const precioProducto = boton.dataset.precio;
        fetch('agregar_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id_producto=${idProducto}&precio=${precioProducto}`
        })
        .then(response => response.json())
        .then(data => {
            const cuentaCarrito = document.getElementById('cuenta-carrito');
            cuentaCarrito.textContent = data.cantidad;
        })
        .catch(error => {
            console.error('Error al agregar el producto al carrito:', error);
        });
    });
});

function filtrarProductos() {
    const input = document.querySelector('.barra-busqueda input');
    const term = input.value.toLowerCase();
    const productos = document.querySelectorAll('.producto');

    productos.forEach(producto => {
        const nombre = producto.querySelector('h3').textContent.toLowerCase();
        if (nombre.includes(term)) {
            producto.style.display = 'block';
        } else {
            producto.style.display = 'none';
        }
    });
}

const inputBusqueda = document.querySelector('.barra-busqueda input');
inputBusqueda.addEventListener('input', filtrarProductos);
</script>
