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

function getProductosMenosVendidos($conexion, $limite = 10)
{
    $consulta = "SELECT p.nombre, SUM(v.cantidad) AS total_vendido FROM ventas v INNER JOIN productos p ON v.id_producto = p.id GROUP BY v.id_producto ORDER BY total_vendido ASC LIMIT $limite";
    $resultado = mysqli_query($conexion, $consulta);
    $productos = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $productos[] = $fila;
    }
    return $productos;
}

$productosMasVendidos = getProductosMasVendidos($conexion, 10);
$productosMenosVendidos = getProductosMenosVendidos($conexion, 10);

$top5MasVendidos = array_slice(getProductosMasVendidos($conexion, 5), 0, 5);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CSScarrito.css" />
    <title>Productos Más y Menos Vendidos</title>
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
    <a href="productos_ingresos.php"><img src="img/productosingresos.png" alt=""></a>
  </header>
    <div class="table-container">
        <h2 style="text-align: center;">Productos Más Vendidos por Cantidad</h2>
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
    </div>

    <div class="table-container">
        <h2 style="text-align: center;">Productos Menos Vendidos por cantidad</h2>
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
            <?php foreach ($productosMenosVendidos as $producto): ?>
                <tr>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['total_vendido']; ?></td>
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
                labels: <?php echo json_encode(array_column($top5MasVendidos, 'nombre')); ?>,
                datasets: [{
                    label: 'Total Vendido',
                    data: <?php echo json_encode(array_column($top5MasVendidos, 'total_vendido')); ?>,
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