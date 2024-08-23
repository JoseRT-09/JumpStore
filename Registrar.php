<?php
include("conexion.php");

// Importa la clase PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function cambiarcolor($campo)
{
    echo "<script>document.getElementById('$campo').style.borderColor = 'red';</script>";
}

if (isset($_POST['registrar']) || isset($_POST['registraradmin'])) {
    $regusuario = $_POST["rusuario"];
    $regnombre = $_POST["rnombre"];
    $regedad = $_POST["redad"];
    $regemail = $_POST["rcorreo"];
    $regtelefono = $_POST["rtelefono"];
    $regcontraseña = $_POST["rcontraseña"];
    $regcontraseña2 = $_POST["rccontraseña"];

    // Expresiones regulares
    $expnombre = "/^[a-zA-Z\s]+$/";
    $expedad = "/^(?:[1-9][0-9]?|100)$/";
    $expcorreo = "/^\w+@[a-zA-Z]+\.\w+$/";
    $expusuario = "/^\w{5,}$/";
    $expcontra = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&()+{}\\\"\\:;'\\[\\]]).{8,}$/";

    $validado = true;
    $mensajes = [];

    if (!preg_match($expnombre, $regnombre)) {
        cambiarcolor("nombre");
        $validado = false;
        $mensajes[] = "El nombre solo debe contener letras y espacios.";
    }

    if (!preg_match($expedad, $regedad)) {
        cambiarcolor("edad");
        $validado = false;
        $mensajes[] = "La edad debe ser un número entre 1 y 100.";
    }

    if (!preg_match($expcorreo, $regemail)) {
        cambiarcolor("correo");
        $validado = false;
        $mensajes[] = "El correo debe tener un formato válido.";
    }

    if (!preg_match($expusuario, $regusuario)) {
        cambiarcolor("usuarior");
        $validado = false;
        $mensajes[] = "El usuario debe tener al menos 5 caracteres y no debe contener espacios.";
    }

    if (!preg_match($expcontra, $regcontraseña)) {
        cambiarcolor("contraseña");
        $validado = false;
        $mensajes[] = "La contraseña debe tener al menos 8 caracteres y contener al menos una letra, un número y un carácter especial.";
    }

    if ($regcontraseña !== $regcontraseña2) {
        cambiarcolor("contraseña");
        cambiarcolor("Ccontrasña");
        $validado = false;
        $mensajes[] = "La contraseña y la confirmación deben ser iguales.";
    }

    if ($validado) {
        // Inicializar la instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            //-----------------------------------------------------
            $mail->isSMTP();
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //-----------------------------------------------------
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'jumpstoreinc@gmail.com'; // Tu correo electrónico
            $mail->Password = 'nvaijcbxleywlxsu'; // Tu contraseña
            $mail->SMTPSecure = 'tls'; // Protocolo de seguridad (tls o ssl)
            $mail->Port = 587; // Puerto SMTP (587 para TLS o 465 para SSL)

            //-----------------------------------------------------
            // Destinatario
            $mail->setFrom('jumpstoreinc@gmail.com', 'Soporte de JumpStore'); // Remitente
            $mail->addAddress($regemail); // Destinatario (correo ingresado por el usuario)
            //-----------------------------------------------------

            //-----------------------------------------------------
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Activación de cuenta';
            //-----------------------------------------------------
            $activationLink = 'http://localhost:3000/activar.php?email=' . urlencode($regemail); // Reemplaza con tu enlace de activación
            $mail->Body = 'Haga clic en el siguiente enlace para activar su cuenta: <a href="' . $activationLink . '">Activar cuenta</a>';

            // Enviar el correo
            $mail->send();

            // Insertar registro con campo 'activo' en 0
            $consulta = "INSERT INTO r_usuarios (n_usuario, nombre, edad, correo, telefono, contraseña, r_contraseña, activo) VALUES ('$regusuario', '$regnombre', $regedad, '$regemail', '$regtelefono', '$regcontraseña', '$regcontraseña2', 0)";
            $envio = mysqli_query($conexion, $consulta);

            if ($envio) {
                echo "<script>alert('Se ha registrado el usuario. Por favor, revise su correo electrónico para activar su cuenta.');</script>";
                echo "<script>window.location.href = 'Login.html';</script>";
                exit;
            } else {
                echo "<script>alert('Ha ocurrido un error al registrar el usuario');</script>";
                echo "<script>window.location.href = 'registro.html';</script>";
                exit;
            }
        } catch (Exception $e) {
            echo "<script>alert('Error al enviar el correo de activación: " . $mail->ErrorInfo . "');</script>";
            echo "<script>window.location.href = 'registro.html';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Se encontraron los siguientes errores:\\n\\n" . implode("\\n", $mensajes) . "');</script>";
        echo "<script>window.location.href = 'registro.html';</script>";
        exit;
    }
}
