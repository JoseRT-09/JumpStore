<?php
require_once 'config/database.php';

// Inicializamos una variable para almacenar el resultado del registro
$registro_exitoso = false;
$noregistro_exitoso = false;
$errors = [];
$database = new Database();
$conexion = $database->conectar();

function mostrarerrores($errors){
    if (count($errors) > 0){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
       
        echo '<ul>';
        foreach($errors as $error){
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

// Verificamos si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos los valores del formulario
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['password'];
    $contraseña2 = $_POST['password2']; // Recuerda mejorar la seguridad almacenando contraseñas de manera segura
    $edad = $_POST['edad'];
    $correo = $_POST['correo'];

    // Función para verificar si todos los campos están llenos
    function camposLlenos($nombre, $usuario, $contraseña, $contraseña2, $edad, $correo) {
        if (empty($nombre) || empty($usuario) || empty($contraseña) || empty($contraseña2) || empty($edad) || empty($correo)) {
            return false;
        }
        return true;
    }

    if (!camposLlenos($nombre, $usuario, $contraseña, $contraseña2, $edad, $correo)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    function esEmail($correo){
        if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }

    if (!esEmail($correo)){
        $errors[] = "El correo no es válido";
    }

    function validarPassword($contraseña, $contraseña2){
        if(strcmp($contraseña, $contraseña2) !== 0){
            return true; // Cambiado a true si las contraseñas no coinciden
        }
        return false; // Retornar false solo si las contraseñas coinciden
    }

    if(validarPassword($contraseña, $contraseña2)){
        $errors[] = "Las contraseñas no coinciden";
    }

    function usuarioExiste($usuario, $conexion){
        $sql = $conexion->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1 ");
        $sql->execute([$usuario]);
        if($sql->fetchColumn() > 0){
            return true;
        }
        return false;
    }

    function emailExiste($correo, $conexion){
        $sql = $conexion->prepare("SELECT id FROM usuarios WHERE correo LIKE ? LIMIT 1 ");
        $sql->execute([$correo]);
        if($sql->fetchColumn() > 0){
            return true;
        }
        return false;
    }

    if(usuarioExiste($usuario, $conexion)){
        $errors[] = "El usuario $usuario ya existe";
    }

    if(emailExiste($correo, $conexion)){
        $errors[] = "El correo $correo ya está registrado";
    }

    if(count($errors) == 0){
        try {
            require 'clases/Mailer.php';
            $mailer = new Mailer();
            // Ejecutamos la consulta con los valores pasados como parámetros
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, contraseña, edad, correo, estado) VALUES (?, ?, ?, ?, ?, 0)"); // Estado siempre 0
            $stmt->execute([$nombre, $usuario, $contraseña, $edad, $correo]);

            // Obtener el ID del usuario recién registrado
            $id = $conexion->lastInsertId();

            $url = 'http://legendarios13.byethost24.com/final/activar_cliente2.php?id=' . $id;
            $asunto = "Activar Cuenta - Legendarios13";
            $cuerpo = "Estimado $nombre: con el usuario $usuario, <br> Para Activa su cuenta presione el siguiente link <a href='$url'>Activar Cuenta</a>";

            if($stmt->rowCount() > 0){
                if($mailer->enviarEmail($correo, $cuerpo, $nombre, $usuario, $url)){
                    $registro_exitoso = true;
                }
            }
            
        } catch (PDOException $e) {
            $noregistro_exitoso = true;
            $errors[] = $e->getMessage(); // Agrega el mensaje de error de la excepción a $errors
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarte</title>
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <nav class="center">
        <!-- Add the logo -->
        <img src="Imagenes/logolegendario13.jpg" class="logo" >
        <!-- Add the nav links -->
    </nav>

    <section>
        <article id="iniciosesion2">
            <?php mostrarerrores($errors); ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="formulario" method="post">
                <!-- Grupo: Usuario -->
                <div class="formulario__grupo" id="grupo__usuario">
                    <label for="usuario" class="formulario__label">Usuario</label>
                    <div class="formulario__grupo-input">
                        <input type="text" class="formulario__input" name="usuario" id="usuario" placeholder="daniel123">
                        <i class="formulario__validacion-estado fas fa-times-circle"></i>
                    </div>
                    <span id="validarUsuario" class="text-danger"></span>
                    <p class="formulario__input-error">El usuario tiene que ser de 4 a 16 dígitos y solo puede contener numeros, letras y guion bajo.</p>
                </div>

                <!-- Grupo: Nombre -->
                <div class="formulario__grupo" id="grupo__nombre">
                    <label for="nombre" class="formulario__label">Nombre</label>
                    <div class="formulario__grupo-input">
                        <input type="text" class="formulario__input" name="nombre" id="nombre" placeholder="Daniel Galaviz">
                        <i class="formulario__validacion-estado fas fa-times-circle"></i>
                    </div>
                    <p class="formulario__input-error">El nombre tiene que ser de 4 a 16 dígitos y solo puede contener numeros, letras y guion bajo.</p>
                </div>

                <!-- Grupo: password -->
                <div class="formulario__grupo" id="grupo__password">
                    <label for="password" class="formulario__label">Contraseña</label>
                    <div class="formulario__grupo-input">
                        <input type="text" class="formulario__input" name="password" id="password" >
                        <i class="formulario__validacion-estado fas fa-times-circle"></i>
                    </div>
                    <p class="formulario__input-error">La contraseña tiene que ser de 8 a 14 digitos, incluyendo mayusculas y numeros y un simbolo especial.</p>
                </div>

                <!-- Grupo: password 2 -->
                <div class="formulario__grupo" id="grupo__password2">
                    <label for="password2" class="formulario__label">Repetir Contraseña</label>
                    <div class="formulario__grupo-input">
                        <input type="text" class="formulario__input" name="password2" id="password2" >
                        <i class="formulario__validacion-estado fas fa-times-circle"></i>
                    </div>
                    <p class="formulario__input-error">Ambas contraseñas tienen que ser iguales.</p>
                </div>

                <!-- Grupo: Edad -->
                <div class="formulario__grupo" id="grupo__edad">
                    <label for="edad" class="formulario__label">Edad</label>
                    <div class="formulario__grupo-input">
                        <input type="text" class="formulario__input" name="edad" id="edad" placeholder="20">
                        <i class="formulario__validacion-estado fas fa-times-circle"></i>
                    </div>
                    <p class="formulario__input-error">La edad solo puede contener numeros.</p>
                </div>

                <!-- Grupo: Correo Electronico -->
                <div class="formulario__grupo" id="grupo__correo">
                    <label for="correo" class="formulario__label">Correo Electrónico</label>
                    <div class="formulario__grupo-input">
                        <input type="email" class="formulario__input" name="correo" id="correo" placeholder="correo@correo.com">
                        <i class="formulario__validacion-estado fas fa-times-circle"></i>
                    </div>
                    <span id="validarEmail" class="text-danger"></span>
                    <p class="formulario__input-error">El correo solo puede contener letras, numeros, puntos, guiones y guion bajo.</p>
                </div>

                <div class="formulario__mensaje" id="formulario__mensaje">
                    <p><i class="fas fa-exclamation-triangle"></i> <b>Error:</b> Por favor rellena el formulario correctamente. </p>
                </div>

                <div class="formulario__grupo formulario__grupo-btn-enviar">
                    <button type="submit" class="formulario__btn">Enviar</button>
                    <p class="formulario__mensaje-exito" id="formulario__mensaje-exito">Formulario enviado Correctamente</p>
                </div>

                <div class="formulario__grupo formulario__grupo-btn-reset3">
                    <button type="button" class="formulario__btn_login3" onclick="limpiarcampos()">Limpiar Campos</button>
                    <p class="formulario__mensaje-exito" id="formulario__mensaje-exito">Campos limpios</p>
                </div>
            </form>

            <button type="submit" class="boton-rojo" onclick="redirectToLogin()">
                <img src="Imagenes/productos/0/atras.jpg" class="imagenesmenu">
            </button>
        </article>
    </section>

    <script src="formulario2.js"></script>
    <script src="formulario.js"></script>
    <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/df00b7509d.js" crossorigin="anonymous"></script>

    <script>
        // Verificar si el registro fue exitoso y mostrar una alerta
        <?php if ($registro_exitoso): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registro exitoso',
                text: '¡Tu registro se ha completado con éxito!',
                showConfirmButton: false,
                timer: 2000  // La alerta se cerrará automáticamente después de 2 segundos
            }).then(() => {
                window.location.href = 'inicio_sesion.php'; // Redireccionar a la página de inicio de sesión
            });
        <?php endif; ?>

        <?php if ($noregistro_exitoso): ?>
            Swal.fire({
                icon: 'success',
                title: 'Error en el Registro',
                text: '¡Tu registro no se ha completado !',
                showConfirmButton: false,
                timer: 2000  // La alerta se cerrará automáticamente después de 2 segundos
            });
        <?php endif; ?>
    </script>
</body>
</html>

