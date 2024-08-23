function validar() {
    var nombre, edad, correo, usuario, contraseña, confirmar;
    var mensajes = [];

    nombre = document.getElementById("Nombre");
    edad = document.getElementById("Edad");
    correo = document.getElementById("Correo");
    usuario = document.getElementById("Usuario");
    contraseña = document.getElementById("Contraseña");
    confirmar = document.getElementById("Confirmar");

    expnombre = /^[a-zA-Z\s]+$/;
    expedad = /^(?:[1-9][0-9]?|100)$/;
    expcorreo = /^\w+@[a-zA-Z]+\.\w+$/;
    expusuario = /^\w{5,}$/;
    expcontra = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!.@#$%^&()+}{":;'\[\]]).{8,}$/;

    function cambiarcolor(campo) {
        campo.style.borderColor = "red";
    }

    var validado = true;

    var validado = true;

    if (!expnombre.test(nombre.value)) {
        cambiarcolor(nombre);
        validado = false;
        mensajes.push("El nombre solo debe contener letras y espacios.");
    }

    if (!expedad.test(edad.value)) {
        cambiarcolor(edad);
        validado = false;
        mensajes.push("La edad debe ser un número entre 1 y 100.");
    }

    if (!expcorreo.test(correo.value)) {
        cambiarcolor(correo);
        validado = false;
        mensajes.push("El correo debe tener un formato válido.");
    }

    if (!expusuario.test(usuario.value)) {
        cambiarcolor(usuario);
        validado = false;
        mensajes.push("El usuario debe tener al menos 5 caracteres y no debe contener espacios.");
    }

    if (!expcontra.test(contraseña.value)) {
        cambiarcolor(contraseña);
        validado = false;
        mensajes.push("La contraseña debe tener al menos 8 caracteres y contener al menos una letra, un número y un carácter especial.");
    }

    if (contraseña.value !== confirmar.value) {
        cambiarcolor(contraseña);
        cambiarcolor(confirmar);
        validado = false;
        mensajes.push("La contraseña y la confirmación deben ser iguales.");
    }

    if (!validado && mensajes.length > 0) {
        alert("Se encontraron los siguientes errores:\n\n" + mensajes.join("\n"));
        return false;
    }
    return validado;
}
