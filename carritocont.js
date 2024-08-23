function eliminarDelCarrito(idProductos) {
  const productosAEliminar = Array.isArray(idProductos) ? idProductos : [idProductos];
  const carritoActual = JSON.parse(localStorage.getItem('carrito')) || [];
  const nuevoCarrito = carritoActual.filter(producto => !productosAEliminar.includes(producto.id));

  localStorage.setItem('carrito', JSON.stringify(nuevoCarrito));

  fetch('agregar_carrito.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `accion=eliminar&ids=${productosAEliminar.join(',')}`
  })
  .then(response => response.json())
  .then(data => {
    mostrarProductosCarrito(data.carrito);
    calcularTotales(data.carrito);
  })
  .catch(error => {
    console.error('Error al eliminar del carrito:', error);
  });
}

// Función para actualizar el contador del carrito
function actualizarContadorCarrito(cantidad) {
  const contadorCarrito = document.getElementById('cuenta-carrito');
  contadorCarrito.textContent = cantidad;
}

// Función para obtener los productos del carrito
function obtenerProductosCarrito() {
  fetch('agregar_carrito.php')
    .then(response => response.json())
    .then(data => {
      mostrarProductosCarrito(data);
      calcularTotales(data);
    })
    .catch(error => {
      console.error('Error al obtener los productos del carrito:', error);
    });
}

function mostrarProductosCarrito(productos) {
  const contenedorCarrito = document.getElementById('contenedorprod');
  contenedorCarrito.innerHTML = '';

  if (productos.length === 0) {
    const mensajeVacio = document.createElement('p');
    mensajeVacio.textContent = 'No hay productos en el carrito.';
    contenedorCarrito.appendChild(mensajeVacio);
  } else {
    const tabla = document.createElement('table');
    const thead = document.createElement('thead');
    const trHead = document.createElement('tr');
    const thProducto = document.createElement('th');
    const thPrecio = document.createElement('th');
    const thCantidad = document.createElement('th');
    const thSubtotal = document.createElement('th');
    const thAcciones = document.createElement('th');

    thProducto.textContent = 'Producto';
    thPrecio.textContent = 'Precio';
    thCantidad.textContent = 'Cantidad';
    thSubtotal.textContent = 'Subtotal';
    thAcciones.textContent = 'Acciones';

    trHead.appendChild(thProducto);
    trHead.appendChild(thPrecio);
    trHead.appendChild(thCantidad);
    trHead.appendChild(thSubtotal);
    trHead.appendChild(thAcciones);
    thead.appendChild(trHead);
    tabla.appendChild(thead);

    const tbody = document.createElement('tbody');

    productos.forEach(producto => {
      const tr = document.createElement('tr');
      const tdProducto = document.createElement('td');
      const tdPrecio = document.createElement('td');
      const tdCantidad = document.createElement('td');
      const tdSubtotal = document.createElement('td');
      const tdAcciones = document.createElement('td');

      const imgProducto = document.createElement('img');
      imgProducto.src = producto.ruta_imagen;
      imgProducto.alt = producto.nombre;
      tdProducto.appendChild(imgProducto);

      const nombreProducto = document.createElement('h3');
      nombreProducto.textContent = producto.nombre;
      tdProducto.appendChild(nombreProducto);

      const descripcionProducto = document.createElement('p');
      descripcionProducto.textContent = producto.descripcion;
      tdProducto.appendChild(descripcionProducto);

      tdPrecio.textContent = `$${producto.precio}`;

      const inputCantidad = document.createElement('input');
      inputCantidad.type = 'number';
      inputCantidad.id = `input-cantidad-${producto.id}`;
      inputCantidad.value = producto.cantidad;
      inputCantidad.min = '1';
      inputCantidad.setAttribute('data-id', producto.id);
      tdCantidad.appendChild(inputCantidad);

      const spanCantidad = document.createElement('span');
      spanCantidad.id = `cantidad-${producto.id}`;
      spanCantidad.textContent = producto.cantidad;
      tdCantidad.appendChild(spanCantidad);

      const subtotalProducto = producto.precio * producto.cantidad;
      const spanSubtotal = document.createElement('span');
      spanSubtotal.id = `subtotal-${producto.id}`;
      spanSubtotal.textContent = subtotalProducto.toFixed(2);
      tdSubtotal.appendChild(spanSubtotal);

      const botonEliminar = document.createElement('button');
      botonEliminar.classList.add('eliminar-carrito');
      botonEliminar.setAttribute('data-id', producto.id);
      const imgEliminar = document.createElement('img');
      imgEliminar.src = 'img/eliminar.png';
      imgEliminar.classList.add('eliminar-carrito');
      botonEliminar.appendChild(imgEliminar);
      tdAcciones.appendChild(botonEliminar);

      tr.appendChild(tdProducto);
      tr.appendChild(tdPrecio);
      tr.appendChild(tdCantidad);
      tr.appendChild(tdSubtotal);
      tr.appendChild(tdAcciones);
      tbody.appendChild(tr);
    });

    tabla.appendChild(tbody);
    contenedorCarrito.appendChild(tabla);

    // Agregar eventos para los input de cantidad y botones de eliminar
    const inputsCantidad = document.querySelectorAll('input[type="number"]');
    inputsCantidad.forEach(inputCantidad => {
    inputCantidad.addEventListener('input', () => {
    const nuevaCantidad = parseInt(inputCantidad.value);
    const idProducto = inputCantidad.getAttribute('data-id');
    const producto = productos.find(p => p.id === parseInt(idProducto));
    producto.cantidad = nuevaCantidad;
    const subtotalProducto = producto.precio * producto.cantidad;
    document.getElementById(`cantidad-${idProducto}`).textContent = producto.cantidad;
    document.getElementById(`subtotal-${idProducto}`).textContent = subtotalProducto.toFixed(2);

    // Crear una copia de la lista de productos con las cantidades actualizadas
    const productosCopia = productos.map(p => ({...p}));
    productosCopia.find(p => p.id === parseInt(idProducto)).cantidad = nuevaCantidad;

    // Pasar la copia de productos a calcularTotales
    calcularTotales(productosCopia);
  });


      inputCantidad.addEventListener('change', () => {
        const nuevaCantidad = parseInt(inputCantidad.value);
        const idProducto = inputCantidad.getAttribute('data-id');

        // Actualizar la cantidad en la sesión
        fetch('actualizar_carrito.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `id=${idProducto}&cantidad=${nuevaCantidad}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const producto = productos.find(p => p.id === parseInt(idProducto));
            producto.cantidad = nuevaCantidad;
            const subtotalProducto = producto.precio * producto.cantidad;
            document.getElementById(`cantidad-${idProducto}`).textContent = producto.cantidad;
            document.getElementById(`subtotal-${idProducto}`).textContent = subtotalProducto.toFixed(2);
            calcularTotales(data.carrito);
          }
        })
        .catch(error => {
          console.error('Error al actualizar el carrito:', error);
        });
      });
    });

    const botonesEliminar = document.querySelectorAll('.eliminar-carrito');
    botonesEliminar.forEach(botonEliminar => {
      botonEliminar.addEventListener('click', () => {
        const idProducto = botonEliminar.getAttribute('data-id');
        eliminarDelCarrito(idProducto);
      });
    });
  }
}

// Función para calcular los totales
function calcularTotales(productos) {
  let precioTotal = 0;

  productos.forEach(producto => {
    const subtotalProducto = producto.precio * producto.cantidad;
    precioTotal += subtotalProducto;
  });

  const precioProductos = document.getElementById('preciot');
  precioProductos.textContent = precioTotal.toFixed(2);
}

function actualizarCarrito(data) {
  if (data.accion === 'actualizar') {
    mostrarProductosCarrito(data.carrito);
    calcularTotales(data.carrito);
  }
}

var eventSource = new EventSource('agregar_carrito.php');
eventSource.onmessage = function(event) {
  var data = JSON.parse(event.data);
  actualizarCarrito(data);
};

window.addEventListener('DOMContentLoaded', obtenerProductosCarrito);

document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('.comprar').addEventListener('click', () => {
    const productosCarrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const detallesCompra = productosCarrito.map(producto => ({
      nombre: producto.nombre,
      cantidad: producto.cantidad,
      subtotal: producto.precio * producto.cantidad
    }));

    fetch('comprar_productos.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ detallesCompra })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const detallesCompraEncoded = JSON.stringify(data.detallesCompra);
        const totalGeneral = `&totalGeneral=${data.totalGeneral}`;
        const url = `resumen_compra.html?success=true&detallesCompra=${encodeURIComponent(detallesCompraEncoded)}${totalGeneral}`;
        window.location.href = url;
      } else {
        alert(data.message);
      }
    })
    .catch(error => {
      console.error('Error al comprar productos:', error);
    });
  });
});