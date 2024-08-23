<?php
include("conexion.php");

function getClientesMayoresIngresos($conexion, $limite = 10)
{
    $consulta = "SELECT u.nombre, SUM(v.total) AS total_ingresos FROM ventas v INNER JOIN r_usuarios u ON v.id_usuario = u.n_usuario GROUP BY v.id_usuario ORDER BY total_ingresos DESC LIMIT $limite";
    $resultado = mysqli_query($conexion, $consulta);
    $clientes = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $clientes[] = $fila;
    }
    return $clientes;
}

$clientesMayoresIngresos = getClientesMayoresIngresos($conexion, 10);
$top5IngresosPorCliente = array_slice(getClientesMayoresIngresos($conexion, 5), 0, 5);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CSScarrito.css" />
    <title>Clientes con Mayores Ingresos por Venta</title>
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
    <a href="productos_vendidos.php"><img src="img/productoscantidad.png" alt=""></a>
    <a href="clientes_cantidad.php"><img src="img/clientescantidad.png" alt=""></a>
    <a href="productos_ingresos.php"><img src="img/productosingresos.png" alt=""></a>
  </header>
    <div class="table-container">
        <h2 style="text-align: center;">Clientes con Mayores Ingresos por Venta</h2>
        <table>
            <tr>
                <th>Cliente</th>
                <th>Total Ingresos</th>
            </tr>
            <?php foreach ($clientesMayoresIngresos as $cliente): ?>
                <tr>
                    <td><?php echo $cliente['nombre']; ?></td>
                    <td><?php echo $cliente['total_ingresos']; ?></td>
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
                labels: <?php echo json_encode(array_column($top5IngresosPorCliente, 'nombre')); ?>,
                datasets: [{
                    label: 'Total Ingresos',
                    data: <?php echo json_encode(array_column($top5IngresosPorCliente, 'total_ingresos')); ?>,
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