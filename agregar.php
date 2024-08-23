<?php
include("conexion.php");

$errorMsg = ""; // Variable para almacenar los mensajes de error

if (isset($_POST['agregarprod'])) {
    // Validar que los campos no estén vacíos
    if (!empty($_POST['nombreprod']) && !empty($_POST['desc']) && !empty($_POST['precio']) && !empty($_POST['cantidadprod'])) {
        // Validar que los campos numéricos contengan valores numéricos válidos
        if (is_numeric($_POST['precio']) && is_numeric($_POST['cantidadprod'])) {
            $nombreprod = $_POST["nombreprod"];
            $descripcion = $_POST["desc"];
            $precio = $_POST["precio"];
            $cantidad = $_POST["cantidadprod"];
            $activo = isset($_POST["activo"]) ? "1" : "0";

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $_FILES['imagen'];
                $nombreImagen = $imagen['name'];
                $rutaTemporal = $imagen['tmp_name'];
                $rutaDestino = 'productos/' . $nombreImagen;

                // Validar que el archivo subido sea una imagen
                $tipoImagen = exif_imagetype($rutaTemporal);
                if ($tipoImagen === IMAGETYPE_JPEG || $tipoImagen === IMAGETYPE_PNG) {
                    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                        $consulta = "INSERT INTO productos (nombre, descripcion, precio, cantidad, activo, ruta_imagen) VALUES ('$nombreprod', '$descripcion', $precio, $cantidad, $activo, '$rutaDestino')";
                        $envio = mysqli_query($conexion, $consulta);

                        if ($envio) {
                            // Redireccionar a agregarprod.php solo si la inserción es exitosa
                            header("Location: agregarprod.php");
                            exit;
                        } else {
                            $errorMsg = "Error al agregar el producto.";
                        }
                    } else {
                        $errorMsg = "Error al subir la imagen.";
                    }
                } else {
                    $errorMsg = "El archivo subido no es una imagen válida.";
                }
            } else {
                $consulta = "INSERT INTO productos (nombre, descripcion, precio, cantidad, activo) VALUES ('$nombreprod', '$descripcion', $precio, $cantidad, $activo)";
                $envio = mysqli_query($conexion, $consulta);

                if ($envio) {
                    // Redireccionar a agregarprod.php solo si la inserción es exitosa
                    header("Location: agregarprod.php");
                    exit;
                } else {
                    $errorMsg = "Error al agregar el producto.";
                }
            }
        } else {
            $errorMsg = "Los campos 'precio' y 'cantidad' deben ser valores numéricos.";
        }
    } else {
        $errorMsg = "Todos los campos son obligatorios.";
    }
}
?>
