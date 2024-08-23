<?php
include("conexion.php");

if (isset($_POST['inicio'])) {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    $consulta = "SELECT * FROM r_usuarios WHERE n_usuario = '$usuario' AND contraseña = '$contraseña'";
    $datos = mysqli_query($conexion, $consulta);

    if (mysqli_num_rows($datos) == 1) {
        $fila = mysqli_fetch_assoc($datos);
        
        if ($fila['activo'] == 1) {
            session_start();
            $_SESSION['usuario'] = $fila['n_usuario'];
            $_SESSION['esAdmin'] = ($fila['n_usuario'] === 'admin'); 
            setcookie("usuario", $usuario, time() + (86400 * 30), "/");
            setcookie("contraseña", $contraseña, time() + (86400 * 30), "/");

            if ($_SESSION['esAdmin']) {
                header("Location: inicioadmin.php");
            } else {
                header("Location: Inicio.php");
            }
            exit();
        } else {
            $error = "El usuario está deshabilitado en esta página. Por favor, contacte al administrador.";
            echo "<script>alert('" . $error . "');</script>"; 
            echo "<script>window.location.href = 'Login.html';</script>";
        }
    } else {
        $error = "Credenciales incorrectas. Por favor, intente nuevamente.";
        echo "<script>alert('" . $error . "');</script>"; 
        echo "<script>window.location.href = 'Login.html';</script>"; 
        exit();
    }
}
?>