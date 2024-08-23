// Colores de fondo de la página
function colorfondo() {
    var color = document.getElementById("backcolor").value;
    var elementos = document.querySelectorAll("body, header, nav");

    elementos.forEach(function(elemento) {
        if (color) {
            elemento.style.backgroundColor = color;
        } else {
            elemento.style.backgroundColor = "";
        }
    });

    localStorage.setItem("colorFondo", color);
}

function modienlace() {
    var redondeado = document.querySelector('input[name="etamaño"]:checked').value;
    var fondoColor = document.getElementById("fondocolor").value;
    var fuenteColor = document.getElementById("fuentecolor").value;

    localStorage.setItem('redondeado', redondeado);
    localStorage.setItem('fondoColor', fondoColor);
    localStorage.setItem('fuenteColor', fuenteColor);

    aplicarEstilos(redondeado, fondoColor, fuenteColor);
}

function aplicarEstilos(redondeado, fondoColor, fuenteColor) {
    var enlaces = document.querySelectorAll("a");
    enlaces.forEach(function(enlace) {
        switch (redondeado) {
            case "chico":
                enlace.style.borderRadius = "5px";
                break;
            case "mediano":
                enlace.style.borderRadius = "10px";
                break;
            case "grande":
                enlace.style.borderRadius = "20px";
                break;
        }
        enlace.style.backgroundColor = fondoColor || "";
        enlace.style.color = fuenteColor || "";
    });
}

function modimagen() {
    var tamañoImagen = document.querySelector('input[name="itamaño"]:checked');
    if (!tamañoImagen) return; // Salir si no se ha seleccionado ningún tamaño

    var redondeadoi = document.querySelector('input[name="irtamaño"]:checked');
    if (!redondeadoi) return; // Salir si no se ha seleccionado ningún redondeado

    var colorBorde = document.getElementById("bordecolor").value;
    var grosorBorde = document.querySelector('input[name="btamaño"]:checked');
    if (!grosorBorde) return; // Salir si no se ha seleccionado ningún grosor de borde

    var sombra = document.querySelector('input[name="stamaño"]:checked');
    if (!sombra) return; // Salir si no se ha seleccionado ningún tamaño de sombra

    localStorage.setItem('tamañoImagen', tamañoImagen.value);
    localStorage.setItem('redondeadoi', redondeadoi.value);
    localStorage.setItem('colorBorde', colorBorde);
    localStorage.setItem('grosorBorde', grosorBorde.value);
    localStorage.setItem('sombra', sombra.value);

    aplicarEstilosImagen(tamañoImagen.value, redondeadoi.value, colorBorde, grosorBorde.value, sombra.value);
}

function aplicarEstilosImagen(tamañoImagen, redondeadoi, colorBorde, grosorBorde, sombra) {
    var imagenes = document.querySelectorAll("img");
    imagenes.forEach(function(imagen) {
        switch (tamañoImagen) {
            case "chico":
                imagen.style.width = "100px";
                break;
            case "mediano":
                imagen.style.width = "200px";
                break;
            case "grande":
                imagen.style.width = "400px";
                break;
        }

        switch (redondeadoi) {
            case "chico":
                imagen.style.borderRadius = "5px";
                break;
            case "mediano":
                imagen.style.borderRadius = "10px";
                break;
            case "grande":
                imagen.style.borderRadius = "20px";
                break;
        }

        imagen.style.borderColor = colorBorde || "";

        switch (grosorBorde) {
            case "chico":
                imagen.style.borderWidth = "1px";
                break;
            case "mediano":
                imagen.style.borderWidth = "3px";
                break;
            case "grande":
                imagen.style.borderWidth = "5px";
                break;
        }

        if (sombra === "grande") {
            imagen.style.boxShadow = "5px 5px 5px rgba(0, 0, 0, 0.5)";
        } else if (sombra === "mediano") {
            imagen.style.boxShadow = "3px 3px 3px rgba(0, 0, 0, 0.5)";
        } else if (sombra === "chico") {
            imagen.style.boxShadow = "1px 1px 1px rgba(0, 0, 0, 0.5)";
        } else {
            imagen.style.boxShadow = "none";
        }
    });
}

function configfuente() {
    var colorFuente = document.getElementById("parrafocolor").value;
    var fondoFuente = document.getElementById("parrafobackcolor").value;
    var tamañoFuente = document.querySelector('input[name="ptamaño"]:checked');

    if (!tamañoFuente) return; // Salir si no se ha seleccionado ningún tamaño de fuente

    localStorage.setItem('colorFuente', colorFuente);
    localStorage.setItem('fondoFuente', fondoFuente);
    localStorage.setItem('tamañoFuente', tamañoFuente.value);

    aplicarEstilosFuente(colorFuente, fondoFuente, tamañoFuente.value);
}

function aplicarEstilosFuente(colorFuente, fondoFuente, tamañoFuente) {
    var elementos = document.querySelectorAll("p, h1, h2, h3, h4, h5, h6, a, span");
    elementos.forEach(function(elemento) {
        elemento.style.color = colorFuente || "";
        elemento.style.backgroundColor = fondoFuente || "";
        switch (tamañoFuente) {
            case "chico":
                elemento.style.fontSize = "12px";
                break;
            case "mediano":
                elemento.style.fontSize = "16px";
                break;
            case "grande":
                switch (elemento.tagName.toLowerCase()) {
                    case "p":
                        elemento.style.fontSize = "20px";
                        break;
                    case "h1":
                    case "h2":
                    case "h3":
                        elemento.style.fontSize = "24px";
                        break;
                    case "h4":
                    case "h5":
                    case "h6":
                    case "a":
                    case "span":
                        elemento.style.fontSize = "18px";
                        break;
                }
                break;
        }
    });
}

window.onload = function() {
    var colorGuardado = localStorage.getItem("colorFondo");
    if (colorGuardado) {
        var elementos = document.querySelectorAll("body, header, footer, nav");
        elementos.forEach(function(elemento) {
            elemento.style.backgroundColor = colorGuardado;
        });
        document.getElementById("backcolor").value = colorGuardado;
    }

    var redondeado = localStorage.getItem('redondeado');
    var fondoColor = localStorage.getItem('fondoColor');
    var fuenteColor = localStorage.getItem('fuenteColor');

    aplicarEstilos(redondeado, fondoColor, fuenteColor);
    document.querySelector('input[name="etamaño"][value="' + redondeado + '"]').checked = true;
    document.getElementById("fondocolor").value = fondoColor;
    document.getElementById("fuentecolor").value = fuenteColor;

    var tamañoImagen = localStorage.getItem('tamañoImagen');
    var redondeadoi = localStorage.getItem('redondeadoi');
    var colorBorde = localStorage.getItem('colorBorde');
    var grosorBorde = localStorage.getItem('grosorBorde');
    var sombra = localStorage.getItem('sombra');

    aplicarEstilosImagen(tamañoImagen, redondeadoi, colorBorde, grosorBorde, sombra);
    document.querySelector('input[name="itamaño"][value="' + tamañoImagen + '"]').checked = true;
    document.querySelector('input[name="irtamaño"][value="' + redondeadoi + '"]').checked = true;
    document.getElementById("bordecolor").value = colorBorde;
    document.querySelector('input[name="btamaño"][value="' + grosorBorde + '"]').checked = true;
    document.querySelector('input[name="stamaño"][value="' + sombra + '"]').checked = true;

    var colorFuenteGuardado = localStorage.getItem('colorFuente');
    var fondoFuenteGuardado = localStorage.getItem('fondoFuente');
    var tamañoFuenteGuardado = localStorage.getItem('tamañoFuente');

    aplicarEstilosFuente(colorFuenteGuardado, fondoFuenteGuardado, tamañoFuenteGuardado);
    document.getElementById("parrafocolor").value = colorFuenteGuardado;
    document.getElementById("parrafobackcolor").value = fondoFuenteGuardado;
    document.querySelector('input[name="ptamaño"][value="' + tamañoFuenteGuardado + '"]').checked = true;
};

function aplicarConfiguraciones() {
    colorfondo();
    modienlace();
    modimagen();
    configfuente();
}

function restaurarEstilosPredeterminados() {
    // Restaurar color de fondo
    localStorage.removeItem('colorFondo');
    var elementos = document.querySelectorAll("body, header, footer, nav");
    elementos.forEach(function(elemento) {
        elemento.style.backgroundColor = "";
    });
    document.getElementById("backcolor").value = "";

    // Restaurar estilos de enlaces
    localStorage.removeItem('redondeado');
    localStorage.removeItem('fondoColor');
    localStorage.removeItem('fuenteColor');
    aplicarEstilos("", "", "");
    document.getElementById("fondocolor").value = "";
    document.getElementById("fuentecolor").value = "";

    // Restaurar estilos de imágenes
    localStorage.removeItem('tamañoImagen');
    localStorage.removeItem('redondeadoi');
    localStorage.removeItem('colorBorde');
    localStorage.removeItem('grosorBorde');
    localStorage.removeItem('sombra');
    aplicarEstilosImagen("", "", "", "", "");
    document.getElementById("bordecolor").value = "";

    // Restaurar estilos de fuentes
    localStorage.removeItem('colorFuente');
    localStorage.removeItem('fondoFuente');
    localStorage.removeItem('tamañoFuente');
    aplicarEstilosFuente("", "", "");
    document.getElementById("parrafocolor").value = "";
    document.getElementById("parrafobackcolor").value = "";

    // Aplicar cambios después de restaurar estilos predeterminados
    aplicarConfiguraciones();
}
