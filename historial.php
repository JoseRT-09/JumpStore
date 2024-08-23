<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Compras</title>
  <link rel="stylesheet" href="CSScarrito.css" />
  <style>
    table {
      width: 80%;
      margin: 0 auto;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    th {
      background-color: #1F1F3A;
      color: #fff;
      text-align: center;
      padding: 12px;
    }
    td {
      padding: 10px;
      background-color: #171823;
      text-align: center;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #e6e6e6;
    }
  </style>
</head>
<body>
  <header>
    <a href="Inicio.php"><img src="img/logo.png" /></a>
  </header>

  <h2 style="text-align: center; margin-top: 30px;">Historial de Compras</h2>

  <?php
  include("conexion.php");
  session_start();

  // Verificar si el usuario ha iniciado sesión
  if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];

    // Consulta SQL para obtener el historial de compras del usuario
    $consulta = "SELECT v.id_venta, u.n_usuario, u.nombre, v.id_producto, v.cantidad, v.total, v.fecha
                 FROM ventas v
                 INNER JOIN r_usuarios u ON v.id_usuario = u.n_usuario
                 WHERE u.n_usuario = '$usuario'
                 ORDER BY v.fecha DESC";
    $resultado = mysqli_query($conexion, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
      echo "<table>
              <tr>
                <th>Venta</th>
                <th>Nombre de Usuario</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Fecha</th>
              </tr>";
      while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>
                <td>" . $fila['id_venta'] . "</td>
                <td>" . $fila['nombre'] . "</td>
                <td>" . $fila['id_producto'] . "</td>
                <td>" . $fila['cantidad'] . "</td>
                <td>" . $fila['total'] . "</td>
                <td>" . $fila['fecha'] . "</td>
              </tr>";
      }
      echo "</table>";
    } else {
      echo "<p style='text-align: center;'>No hay compras registradas.</p>";
    }
  } else {
    echo "<p style='text-align: center;'>Debes iniciar sesión para ver tu historial de compras.</p>";
  }
  ?>
</body>
</html>