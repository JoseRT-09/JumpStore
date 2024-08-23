<?php
include("conexion.php");

// Función para obtener las ventas por rango de fechas con paginación
function getVentasPorRango($conexion, $fecha_inicio, $fecha_fin, $pagina = 1, $registros_por_pagina = 10)
{
    $inicio = ($pagina > 1) ? ($pagina * $registros_por_pagina - $registros_por_pagina) : 0;

    $consulta = "SELECT v.id_venta, v.id_usuario, v.id_producto, v.cantidad, v.total, v.fecha
                 FROM ventas v
                 WHERE v.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
                 ORDER BY v.fecha DESC
                 LIMIT $inicio, $registros_por_pagina";

    $resultado = mysqli_query($conexion, $consulta);
    $ventas = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $ventas[] = $fila;
    }
    return $ventas;
}

// Función para obtener el número total de ventas por rango de fechas
function getTotalVentasPorRango($conexion, $fecha_inicio, $fecha_fin)
{
    $consulta = "SELECT COUNT(*) AS total_ventas
                 FROM ventas
                 WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    $resultado = mysqli_query($conexion, $consulta);
    $fila = mysqli_fetch_assoc($resultado);
    return $fila['total_ventas'];
}

// Función para obtener todas las ventas con paginación
function getVentas($conexion, $pagina = 1, $registros_por_pagina = 10)
{
    return getVentasPorRango($conexion, '1900-01-01', '2100-01-01', $pagina, $registros_por_pagina);
}

// Función para obtener el número total de ventas
function getTotalVentas($conexion)
{
    $consulta = "SELECT COUNT(*) AS total_ventas FROM ventas";
    $resultado = mysqli_query($conexion, $consulta);
    $fila = mysqli_fetch_assoc($resultado);
    return $fila['total_ventas'];
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="CSScarrito.css" />
    <title>Ventas</title>
    <style>
        table {
            padding-top: 40px;
            border-collapse: collapse;
            width: 80%;
            margin: 0 auto;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #1F1F3A;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
        form {
            text-align: center;
            margin-top: 40px;
            margin-bottom: 30px;
        }
    </style>
</head>
<header>
    <a href="inicioadmin.php"><img src="img/logo.png" /></a>
    <a href="clientes_ingresos.php"><img src="img/ingresosc.png" alt=""></a>
    <a href="productos_vendidos.php"><img src="img/productoscantidad.png" alt=""></a>
    <a href="clientes_cantidad.php"><img src="img/clientescantidad.png" alt=""></a>
    <a href="productos_ingresos.php"><img src="img/productosingresos.png" alt=""></a>
  </header>
<body>
    <form method="post" action="">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio">
        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin">
        <input type="submit" value="Filtrar">
    </form>

    <?php
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $registros_por_pagina = 10;

    if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $ventas = getVentasPorRango($conexion, $fecha_inicio, $fecha_fin, $pagina, $registros_por_pagina);
        $total_ventas = getTotalVentasPorRango($conexion, $fecha_inicio, $fecha_fin);
    } else {
        $ventas = getVentas($conexion, $pagina, $registros_por_pagina);
        $total_ventas = getTotalVentas($conexion);
    }

    $total_paginas = ceil($total_ventas / $registros_por_pagina);

    echo "<table>
            <tr>
                <th>ID Venta</th>
                <th>ID Usuario</th>
                <th>ID Producto</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Fecha</th>
            </tr>";

    foreach ($ventas as $venta) {
        echo "<tr>
                <td>{$venta['id_venta']}</td>
                <td>{$venta['id_usuario']}</td>
                <td>{$venta['id_producto']}</td>
                <td>{$venta['cantidad']}</td>
                <td>{$venta['total']}</td>
                <td>{$venta['fecha']}</td>
            </tr>";
    }

    echo "</table>";

    echo "<div class='pagination'>";
    if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        for ($i = 1; $i <= $total_paginas; $i++) {
            $class = ($pagina == $i) ? 'active' : '';
            echo "<a href='?pagina=$i&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin' class='$class'>$i</a> ";
        }
    } else {
        for ($i = 1; $i <= $total_paginas; $i++) {
            $class = ($pagina == $i) ? 'active' : '';
            echo "<a href='?pagina=$i' class='$class'>$i</a> ";
        }
    }
    echo "</div>";
    ?>
</body>
</html>