<?php
session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['esAdmin'])) {
    $usuario = $_SESSION['usuario'];
    $esAdmin = $_SESSION['esAdmin'];

    if ($esAdmin) {
        if (!strpos($_SERVER['PHP_SELF'], 'inicioadmin.php') !== false && !strpos($_SERVER['PHP_SELF'], 'Config.php') !== false) {
            header("Location: inicioadmin.php");
            exit;
        }
    } else {
        if (!strpos($_SERVER['PHP_SELF'], 'Inicio.php') !== false && !strpos($_SERVER['PHP_SELF'], 'Config.php') !== false) {
            header("Location: Inicio.php");
            exit;
        }
    }
} else {
    header("Location: Login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Configuracion</title>
  </head>
  <body>
    <header>
    <a href="<?php echo ($esAdmin) ? 'inicioadmin.php' : 'Inicio.php'; ?>" id="enlace-inicio"><img src="img/logo.png" alt=""></a>
    </header>
    <div>
      <h2>Fondo de la pagina</h2>
      <h3>Color de fondo</h3>
      <input type="color" id="backcolor" />
    </div>
    <div>
      <h2>Enlaces</h2>
      <h3>Redondeado</h3>
      <input type="radio" id="chico" name="etamaño" value="chico" />
      <label for="chico">Chico</label><br />
      <input type="radio" id="mediano" name="etamaño" value="mediano" />
      <label for="mediano">Mediano</label><br />
      <input type="radio" id="grande" name="etamaño" value="grande" />
      <label for="grande">Grande</label><br />
      <h3>Color de fondo</h3>
      <input type="color" id="fondocolor" />
      <h3>Color de fuente</h3>
      <input type="color" id="fuentecolor" />
    </div>
    <div>
      <h2>Imagenes</h2>
      <h3>Tamaño</h3>
      <input type="radio" id="imgchico" name="itamaño" value="chico" />
      <label for="chico">Chico</label><br />
      <input type="radio" id="imgmediano" name="itamaño" value="mediano" />
      <label for="mediano">Mediano</label><br />
      <input type="radio" id="imggrande" name="itamaño" value="grande" />
      <label for="grande">Grande</label><br />
      <h3>Redondeado</h3>
      <input type="radio" id="imgrchico" name="irtamaño" value="chico" />
      <label for="chico">Chico</label><br />
      <input type="radio" id="imgrmediano" name="irtamaño" value="mediano" />
      <label for="mediano">Mediano</label><br />
      <input type="radio" id="imgrgrande" name="irtamaño" value="grande" />
      <label for="grande">Grande</label><br />
      <h3>Color de borde</h3>
      <input type="color" id="bordecolor" />
      <h3>Grosor del borde</h3>
      <input type="radio" id="borchico" name="btamaño" value="chico" />
      <label for="chico">Chico</label><br />
      <input type="radio" id="bormediano" name="btamaño" value="mediano" />
      <label for="mediano">Mediano</label><br />
      <input type="radio" id="borgrande" name="btamaño" value="grande" />
      <label for="grande">Grande</label><br />
      <h3>Sombra</h3>
      <input type="radio" id="somchico" name="stamaño" value="chico" />
      <label for="chico">Chico</label><br />
      <input type="radio" id="sommediano" name="stamaño" value="mediano" />
      <label for="mediano">Mediano</label><br />
      <input type="radio" id="somgrande" name="stamaño" value="grande" />
      <label for="grande">Grande</label><br />
    </div>
    <div>
      <h2>Parrafos, titulos, subtitulos</h2>
      <h3>Color de fuente</h3>
      <input type="color" id="parrafocolor" />
      <h3>Color de fondo</h3>
      <input type="color" id="parrafobackcolor" />
      <h3>Tamaño</h3>
      <input type="radio" id="pchico" name="ptamaño" value="chico" />
      <label for="chico">Chico</label><br />
      <input type="radio" id="pmediano" name="ptamaño" value="mediano" />
      <label for="mediano">Mediano</label><br />
      <input type="radio" id="pgrande" name="ptamaño" value="grande" />
      <label for="grande">Grande</label><br />
    </div>
    <p>PRUEBA</p>
    <img src="img/descarga.jpg" alt="" />
    <a href="">ENLACE</a>
    <button
      class="cambio"
      id="aplicarcambios"
      onclick="aplicarConfiguraciones()"
    >
      Aplicar
    </button>
    <script></script>
    <button id="confinicial" onclick="restaurarEstilosPredeterminados()">
      Configuracion inicial
    </button>
    <footer>
      <div class="redes-container">
        <a href="https://www.instagram.com/"
          ><img class="redes" src="img/instagram.png"
        /></a>
        <a href="https://web.whatsapp.com/"
          ><img class="redes" src="img/whatsapp.png"
        /></a>
        <a href="https://twitter.com/?lang=es"
          ><img class="redes" src="img/twitter.png"
        /></a>
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
