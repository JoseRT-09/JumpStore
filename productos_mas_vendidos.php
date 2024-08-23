<?php
include("conexion.php");

function getProductosMasVendidos($conexion, $limite = 10)
{
    $consulta = "SELECT p.nombre, SUM(v.cantidad) AS total_vendido FROM ventas v INNER JOIN productos p ON v.id_producto = p.id GROUP BY v.id_producto ORDER BY total_vendido DESC LIMIT $limite";
    $resultado = mysqli_query($conexion, $consulta);
    $productos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $productos[] = $fila;
    }
    return $productos;
}

$productosMasVendidos = getProductosMasVendidos($conexion, 10);
?>

<table>
    <tr>
        <th>Producto</th>
        <th>Cantidad</th>
    </tr>
    <?php foreach ($productosMasVendidos as $producto): ?>
        <tr>
            <td><?php echo $producto['nombre']; ?></td>
            <td><?php echo $producto['total_vendido']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
