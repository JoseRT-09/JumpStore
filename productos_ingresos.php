<?php
include("conexion.php");

// Función para obtener los productos con más ingresos
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

// Función para obtener los productos con menos ingresos
function getProductosMenosIngresos($conexion, $limite = 10)
{
    $consulta = "SELECT p.nombre, SUM(v.cantidad * p.precio) AS total_ingresos FROM ventas v INNER JOIN productos p ON v.id_producto = p.id GROUP BY v.id_producto ORDER BY total_ingresos ASC LIMIT $limite";
    $resultado = mysqli_query($conexion, $consulta);
    $productos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $productos[] = $fila;
    }
    return $productos;
}

// Obtener los productos con más y menos ingresos
$productosMasIngresos = getProductosMasIngresos($conexion, 10);
$productosMenosIngresos = getProductosMenosIngresos($conexion, 10);

// Obtener los 5 productos con más ingresos para la gráfica
$top5MasIngresos = array_slice(getProductosMasIngresos($conexion, 5), 0, 5);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CSScarrito.css" />
    <title>Productos con Más y Menos Ingresos</title>
    <style>
        .table-container {
            width: 50%;
            margin: 0 auto;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }
        th, td {
            text-align: left;
            padding: 4px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #1F1F3A;
            text-align: center;
        }
        canvas {
            max-width: 2000px;
            height: 400px;
            margin: 0 auto;
            background-color: #fff;
            border: 2px solid #333;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<header>
    <a href="inicioadmin.php"><img src="img/logo.png" /></a>
    <a href="fecha.php"><img src="img/calendario1.png" alt=""></a>
    <a href="clientes_ingresos.php"><img src="img/ingresosc.png" alt=""></a>
    <a href="clientes_cantidad.php"><img src="img/clientescantidad.png" alt=""></a>
    <a href="productos_vendidos.php"><img src="img/productoscantidad.png" alt=""></a>
  </header>
    <div class="table-container">
        <h2 style="text-align: center;">Productos con Más Ingresos</h2>
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
    </div>

    <div class="table-container">
        <h2 style="text-align: center;">Productos con Menos Ingresos</h2>
        <table>
            <tr>
                <th>Producto</th>
                <th>Ingresos</th>
            </tr>
            <?php foreach ($productosMenosIngresos as $producto): ?>
                <tr>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo number_format($producto['total_ingresos'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <canvas id="chart"></canvas>
    <script>
        var ctx = document.getElementById('chart').getContext('2d');
        var colores = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)'
        ];
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($top5MasIngresos, 'nombre')); ?>,
                datasets: [{
                    label: 'Total Ingresos',
                    data: <?php echo json_encode(array_column($top5MasIngresos, 'total_ingresos')); ?>,
                    backgroundColor: colores,
                    borderColor: 'rgba(0, 0, 0, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
      <script>
    if (
      !document.cookie.includes("usuario") ||
      !document.cookie.includes("contraseña")
    ) {
      window.location.href = "Login.html";
    }
  </script>
</body>
</html>