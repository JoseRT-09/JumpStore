<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["mensaje"])) {
        $nombre = $_POST["cnombre"];
        $correo = $_POST["cCorreo"];
        $mensaje = $_POST["mensajeenvio"];

        $consultaInsertar = "INSERT INTO mensajes (usuario, correo, mensaje, leido) VALUES ('$nombre', '$correo', '$mensaje', 0)";
        if (mysqli_query($conexion, $consultaInsertar)) {
            echo "<script>alert('Mensaje enviado correctamente.');</script>";
        } else {
            echo "<script>alert('Error al enviar el mensaje.');</script>";
        }
    } elseif (isset($_POST["eliminar"])) {
        // Código para limpiar los campos del formulario
    }
}
?>