<?php
include("conexion.php");

function getProductosMasIngresos($conexion, $limite = 10)
{
    $consulta = "SELECT p.nombre, SUM(v.cantidad * p.precio) AS total_ingresos FROM ventas v INNER JOIN productos p ON v.id_producto = p.id GROUP BY v.id_producto ORDER BY total_ingresos DESC LIMIT $limite";
    $resultado = mysqli_query($conexion, $consulta);
    $productos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $productos[] = $fila;
    }
    return $productos;
}

$productosMasIngresos = getProductosMasIngresos($conexion, 10);
?>

<table>
    <tr>
        <th>Producto</th>
        <th>Ingresos</th>
    </tr>
    <?php foreach ($productosMasIngresos as $producto): ?>
        <tr>
            <td><?php echo $producto['nombre']; ?></td>
            <td><?php echo number_format($producto['total_ingresos'], 2); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
