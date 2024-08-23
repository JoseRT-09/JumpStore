<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['n_usuario']) && isset($_POST['nuevoEstado'])) {
    $n_usuario = $_POST['n_usuario'];
    $nuevoEstado = $_POST['nuevoEstado'];
    
    if ($n_usuario !== 'admin') {
        $update_query = "UPDATE r_usuarios SET activo = $nuevoEstado WHERE n_usuario = '$n_usuario'";
        if (mysqli_query($conexion, $update_query)) {
            exit;
        } else {
            echo "Error al cambiar el estado del usuario: " . mysqli_error($conexion);
        }
    } else {
        echo "No se puede cambiar el estado del usuario 'admin'.";
    }
}

$query = "SELECT n_usuario, nombre, edad, correo, telefono, activo FROM r_usuarios WHERE n_usuario <> 'admin'";
$result = mysqli_query($conexion, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="cssmenus.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1F1F3A;
        }

        .activo {
            color: green;
            cursor: pointer;
        }

        .inactivo {
            color: red;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <a href="inicioadmin.php"><img src="img/logo.png" alt="" /></a>
    </header>

    <h2>Lista de Usuarios</h2>

    <table>
        <tr>
            <th>Nombre de Usuario</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['n_usuario'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['edad'] . "</td>";
                echo "<td>" . $row['correo'] . "</td>";
                echo "<td>" . $row['telefono'] . "</td>";
                echo "<td class='" . ($row['activo'] == 1 ? 'activo' : 'inactivo') . "' onclick='cambiarEstado(\"" . $row['n_usuario'] . "\", " . ($row['activo'] == 1 ? 0 : 1) . ")'>" . ($row['activo'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
                echo "<td><a href='modificar_usuarios.php?n_usuario=" . $row['n_usuario'] . "'>Modificar</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay usuarios registrados.</td></tr>";
        }
        ?>
    </table>

    <footer>
        <div class="redes-container">
            <a href="https://www.instagram.com/"><img class="redes" src="img/instagram.png" /></a>
            <a href="https://web.whatsapp.com/"><img class="redes" src="img/whatsapp.png" /></a>
            <a href="https://twitter.com/?lang=es"><img class="redes" src="img/twitter.png" /></a>
        </div>
        <div class="soporte-container">
            <a href="Contacto.html">
                <span class="soporte-texto">Soporte Técnico</span>
                <img class="redes" src="img/apoyo.png" />
            </a>
        </div>
    </footer>

    <script>
        function cambiarEstado(n_usuario, nuevoEstado) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "usuarios.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        location.reload();
                    } else {
                        console.error('Error al cambiar el estado del usuario');
                    }
                }
            };
            xhr.send("n_usuario=" + n_usuario + "&nuevoEstado=" + nuevoEstado);
        }
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
