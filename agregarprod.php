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

                        if (!$envio) {
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

                if (!$envio) {
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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="CSScarrito.css" />
</head>

<body>
  <header>
    <a href="inicioadmin.php"><img src="img/logo.png" /></a>
    <a href="modificar_admin.php"><img src="img/datos.png" alt=""></a>
    <a href="usuarios.php"><img src="img/usuarios.png" alt=""></a>
    <a href="productos_vendidos.php"><img src="img/ingresos.png" alt=""></a>
    <a href="mensajeria.php"><img src="img/carta (2).png" alt=""></a>
  </header>
  <form action="agregarprod.php" method="post" class="agregar" enctype="multipart/form-data">
    <label for="">Agregar Producto</label>
    <?php
    // Mostrar la alerta de error si existe
    if (!empty($errorMsg)) {
        echo "<p style='color: red;'>$errorMsg</p>";
    }
    ?>
    <div class="box">
      <label for="nombreprod">Nombre</label>
      <input type="text" name="nombreprod" id="usu" />
    </div>
    <div class="box">
      <label for="descripcion">Descripcion</label>
      <textarea name="desc" id="usu" rows="5"></textarea>
    </div>
    <div class="box">
      <label for="precio">Precio</label>
      <input type="text" name="precio" id="usu" />
    </div>
    <div class="box">
      <label for="cantidad">Cantidad</label>
      <input type="text" name="cantidadprod" id="usu" />
    </div>
    <div class="box">
      <label for="activo">Activo</label>
      <input type="checkbox" name="activo" id="usu" value="1" />
    </div>
    <div class="box">
      <input type="file" accept=".png, .jpg" name="imagen" id="usu" />
    </div>
    <button class="boton" name="agregarprod">Agregar</button>
  </form>
  <nav class="barra-busqueda">
  <form action="productos.php" method="GET">
    <input type="text" placeholder="Buscar" name="buscar" />
    <button type="submit">
      <img class="buscar" src="img/buscar.png" alt="" />
    </button>
  </form>
</nav>
  <h2>PRODUCTOS ACTIVOS</h2>
  <?php include("productosadmin.php"); ?>
  <section id="contenedorprod"></section>
  <h2>PRODUCTOS INACTIVOS</h2>
  <?php include("productosinactivos.php"); ?>
  <section id="contenedorinactivos"></section>
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
