<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro</title>
    <link rel="stylesheet" href="cssmenus.css" />
    <script src="aplicarconf.js"></script>
  </head>

  <body>
    <header>
      <a href="inicioadmin.php"><img src="img/logo.png" alt="" /></a>
      <a href="agregarprod.php"><img src="img/llave.png" alt="" /></a>
    </header>
    <form action="modificar_admin.php" method="post" class="registro">
        <div class="contenedor">
            <div class="izq">
                <div class="reg">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="rnombre" id="nombre" value="<?php echo $nombre; ?>" required />
                </div>
                <div class="reg">
                    <label for="edad">Edad</label>
                    <input type="number" name="redad" id="edad" min="0" max="110" value="<?php echo $row['edad']; ?>" required />
                </div>
                <div class="reg">
                    <label for="correo">Correo</label>
                    <input type="email" name="rcorreo" id="correo" value="<?php echo $row['correo']; ?>" required />
                </div>
                <div class="reg">
                    <label for="telefono">Telefono</label>
                    <input type="tel" name="rtelefono" id="telefono" value="<?php echo $row['telefono']; ?>" required />
                </div>
            </div>
            <div class="der">
                <div class="reg">
                    <label for="contraseña">Contraseña</label>
                    <input type="password" name="rcontraseña" id="contraseña" value="<?php echo $row['contraseña']; ?>" />
                </div>
                <div class="reg">
                    <label for="Ccontrasña">Confirmar contraseña</label>
                    <input type="password" name="rccontraseña" id="Ccontrasña" value="<?php echo $row['r_contraseña']; ?>" />
                </div>
            </div>
        </div>
        <button class="boton" name="actualizaradmin">Actualizar</button>
    </form>
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
  </body>
</html>
