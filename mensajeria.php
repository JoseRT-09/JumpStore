<!DOCTYPE html>
<html>
<head>
    <title>Mensajería</title>
    <link rel="stylesheet" href="CSScarrito.css">
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
  </header>
<body>
<?php
include("conexion.php");

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Función para obtener los mensajes con paginación
function obtenerMensajes($conexion, $leido, $pagina = 1, $registros_por_pagina = 10)
{
    $inicio = ($pagina > 1) ? ($pagina * $registros_por_pagina - $registros_por_pagina) : 0;

    $consulta = "SELECT * FROM mensajes WHERE leido = $leido ORDER BY id DESC LIMIT $inicio, $registros_por_pagina";
    $resultado = mysqli_query($conexion, $consulta);
    $mensajes = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $mensajes[] = $fila;
    }
    return $mensajes;
}

// Función para obtener el número total de mensajes
function obtenerTotalMensajes($conexion, $leido)
{
    $consulta = "SELECT COUNT(*) AS total_mensajes FROM mensajes WHERE leido = $leido";
    $resultado = mysqli_query($conexion, $consulta);
    $fila = mysqli_fetch_assoc($resultado);
    return $fila['total_mensajes'];
}

// Verificar si el usuario es administrador
if (isset($_SESSION["esAdmin"]) && $_SESSION["esAdmin"]) {
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $registros_por_pagina = 10;

    // Obtener los mensajes no leídos de la base de datos
    $mensajesNoLeidos = obtenerMensajes($conexion, 0, $pagina, $registros_por_pagina);
    $totalMensajesNoLeidos = obtenerTotalMensajes($conexion, 0);

    echo "<h2>Mensajes No Leídos</h2>";
    if (count($mensajesNoLeidos) > 0) {
        echo "<table>";
        echo "<tr><th>Usuario</th><th>Correo</th><th>Mensaje</th><th>Acciones</th></tr>";
        foreach ($mensajesNoLeidos as $fila) {
            echo "<tr>";
            echo "<td>" . $fila["usuario"] . "</td>";
            echo "<td>" . $fila["correo"] . "</td>";
            echo "<td>" . $fila["mensaje"] . "</td>";
            echo "<td>";
            echo "<a href='mensajeria.php?accion=eliminar&id=" . $fila['id'] . "'>Eliminar</a>";
            echo " | ";
            echo "<a href='mensajeria.php?accion=responder&id=" . $fila['id'] . "&usuario=" . $fila['usuario'] . "&correo=" . $fila['correo'] . "'>Contestar</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Paginación
        $total_paginas = ceil($totalMensajesNoLeidos / $registros_por_pagina);
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_paginas; $i++) {
            $class = ($pagina == $i) ? 'active' : '';
            echo "<a href='?pagina=$i' class='$class'>$i</a> ";
        }
        echo "</div>";
    } else {
        echo "No hay mensajes no leídos.";
    }

    // Obtener los mensajes leídos de la base de datos
    $mensajesLeidos = obtenerMensajes($conexion, 1, $pagina, $registros_por_pagina);
    $totalMensajesLeidos = obtenerTotalMensajes($conexion, 1);

    echo "<h2>Mensajes Leídos</h2>";
    if (count($mensajesLeidos) > 0) {
        echo "<table>";
        echo "<tr><th>Usuario</th><th>Correo</th><th>Mensaje</th><th>Acción</th></tr>";
        foreach ($mensajesLeidos as $fila) {
            echo "<tr>";
            echo "<td>" . $fila["usuario"] . "</td>";
            echo "<td>" . $fila["correo"] . "</td>";
            echo "<td>" . $fila["mensaje"] . "</td>";
            echo "<td><a href='mensajeria.php?accion=eliminar&id=" . $fila['id'] . "'>Eliminar</a></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Paginación
        $total_paginas = ceil($totalMensajesLeidos / $registros_por_pagina);
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_paginas; $i++) {
            $class = ($pagina == $i) ? 'active' : '';
            echo "<a href='?pagina=$i' class='$class'>$i</a> ";
        }
        echo "</div>";
    } else {
        echo "No hay mensajes leídos.";
    }

    // Procesar acciones de eliminar y responder
    if (isset($_GET["accion"])) {
        $id = $_GET["id"];
        $accion = $_GET["accion"];
        if ($accion == "eliminar") {
            $consultaEliminar = "DELETE FROM mensajes WHERE id = '$id'";
            mysqli_query($conexion, $consultaEliminar);
            echo "<script>alert('Mensaje eliminado correctamente.');</script>";
            echo "<script>window.location.href = 'mensajeria.php';</script>";
        } elseif ($accion == "responder") {
            $usuario = $_GET["usuario"];
            $correoUsuario = $_GET["correo"];
            mostrarFormularioRespuesta($id, $usuario, $correoUsuario, $conexion);
        }
    }
} else {
    echo "Acceso denegado. Solo los administradores pueden acceder a esta página.";
}
    // Función para enviar el correo electrónico
    function enviarCorreo($correoUsuario, $respuesta, $conexion)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor de correo
            $mail->isSMTP();
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'jumpstoreinc@gmail.com'; // Tu correo electrónico
            $mail->Password = 'nvaijcbxleywlxsu'; // Tu contraseña
            $mail->SMTPSecure = 'tls'; // Protocolo de seguridad (tls o ssl)
            $mail->Port = 587; // Puerto SMTP (587 para TLS o 465 para SSL)

            // Configuración del correo electrónico
            $mail->setFrom('jumpstoreinc@gmail.com', 'Soporte de JumpStore'); // Remitente
            $mail->addAddress($correoUsuario); // Destinatario
            $mail->isHTML(true);
            $mail->Subject = 'Soporte de JumpStore';
            $mail->Body = $respuesta;

            // Enviar el correo electrónico
            $mail->send();
            echo "<script>alert('Correo enviado correctamente.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error al enviar el correo: " . $mail->ErrorInfo . "');</script>";
            echo "Error al enviar el correo: " . $mail->ErrorInfo . "<br>";
        }
    }

    // Función para mostrar el formulario de respuesta
    function mostrarFormularioRespuesta($id, $usuario, $correoUsuario, $conexion)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["responder"])) {
                $respuesta = $_POST["respuesta"];
                enviarCorreo($correoUsuario, $respuesta, $conexion);

                // Marcar mensaje como leído
                $consultaActualizar = "UPDATE mensajes SET leido = 1 WHERE id = '$id'";
                mysqli_query($conexion, $consultaActualizar);
                echo "<script>alert('Mensaje de $usuario marcado como leído.');</script>";
                echo "<script>window.location.href = 'mensajeria.php';</script>";
            }
        }

        echo "<h2>Responder a $usuario ($correoUsuario)</h2>";
        echo "<form method='post'>";
        echo "<textarea name='respuesta' rows='6' required></textarea>";
        echo "<br>";
        echo "<input type='submit' name='responder' value='Enviar Respuesta'>";
        echo "</form>";
    }
    ?>
</body>
</html>