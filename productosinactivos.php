<?php
include("conexion.php");

$productosPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $productosPorPagina;

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

if (!empty($buscar)) {
    $consulta = "SELECT * FROM productos WHERE activo = 0 AND nombre LIKE '%$buscar%' LIMIT $offset, $productosPorPagina";
} else {
    $consulta = "SELECT * FROM productos WHERE activo = 0 LIMIT $offset, $productosPorPagina";
}

$envio = $conexion->query($consulta);

if ($envio->num_rows > 0) {
    echo "<section id='contenedorinactivos'>";
    while ($row = $envio->fetch_assoc()) {
        echo "<div class='producto' data-id='" . $row["ID"] . "'>";
        echo "<img src='" . $row["ruta_imagen"] . "' alt='" . $row["nombre"] . "'>";
        echo "<h3>" . $row["nombre"] . "</h3>";
        echo "<h2>" . $row["descripcion"] . "</h2>";
        echo "<p>$" . $row["precio"] . "</p>";
        echo "<button class='habilitar-producto' data-id='" . $row["ID"] . "' onclick='habilitarProducto(this, " . $row["ID"] . ")'><img class='eliminar-carrito' src='img/suma.png'></button>";
        echo "<div class='modal' id='modal-" . $row["ID"] . "'>
                <div class='modal-contenido'>
                    <h2>Modificar Producto</h2>
                    <form id='form-modificar-producto-" . $row["ID"] . "' onsubmit='guardarCambios(event, " . $row["ID"] . ")'>
                        <input type='hidden' name='id_producto' value='" . $row["ID"] . "'>
                        <label for='nombre'>Nombre:</label>
                        <input type='text' name='nombre' value='" . $row["nombre"] . "' required>
                        <label for='descripcion'>Descripción:</label>
                        <input type='text' name='descripcion' value='" . $row["descripcion"] . "' required>
                        <label for='precio'>Precio:</label>
                        <input type='number' name='precio' value='" . $row["precio"] . "' step='0.10' required>
                        <label for='cantidad'>Cantidad:</label>
                        <input type='number' name='cantidad' value='" . $row["cantidad"] . "' required>
                        <button type='submit'>Guardar Cambios</button>
                    </form>
                </div>
            </div>";
        echo "</div>";
    }
    echo "</section>";

    $totalConsulta = "SELECT COUNT(*) as total FROM productos WHERE activo = 0";
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
function habilitarProducto(boton, idProducto) {
    fetch(`habilitar_producto.php?id_producto=${idProducto}`, {
        method: 'POST',
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            console.log('Producto habilitado correctamente');
            const producto = boton.closest('.producto');
            producto.remove();
        } else {
            console.error('Error al habilitar el producto:', data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error de red:', error);
    });
}

function guardarCambios(event, idProducto) {
    event.preventDefault();
    
    const form = document.getElementById('form-modificar-producto-' + idProducto);
    const formData = new FormData(form);

    fetch('modificar_producto.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            console.log('Cambios guardados correctamente');
        } else {
            console.error('Error al guardar los cambios:', data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error de red:', error);
    });
}

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