<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="CSScarrito.css" />
  <script src="aplicarconf.js"></script>
  <script src="productos.php"></script>
</head>

<body>
  <header>
  <a href="Login.html"><img src="img/logo.png" /></a>
    <a href="carrito.php"><img src="img/carrito-de-compras.png" class="logocar" />
    <a href="Config.php"><img src="img/conf.png" /></a>
    <a href="agregarprod.php"><img src="img/llave.png" alt="" /></a>
  </header>
  <nav class="barra-busqueda">
  <form action="productos.php" method="GET">
    <input type="text" placeholder="Buscar" name="buscar" />
    <button type="submit">
      <img class="buscar" src="img/buscar.png" alt="" />
    </button>
  </form>
</nav>
  <?php include("productos.php"); ?>
  <section id="contenedorprod"></section>
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
    if (
      !document.cookie.includes("usuario") ||
      !document.cookie.includes("contraseña")
    ) {
      window.location.href = "Login.html";
    }
  </script>
  <script src="aplicarconf.js"></script>
</body>

</html>