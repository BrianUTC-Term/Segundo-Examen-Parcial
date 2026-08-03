// referencias al dom
const btnEscanear    = document.getElementById('btn-escanear');
const btnReportes    = document.getElementById('btn-reportes');
const cuerpoTabla    = document.getElementById('cuerpo-tabla');
const cuentaRegresiva = document.getElementById('cuenta-regresiva');

// ================================
// Escaneo de archivos
// ================================

// llama al PHP que genera archivos falsos y los guarda en la BD
const ejecutarEscaneo = () => {
    return fetch('escanear.php')
        .then(res => res.json())
        .then(data => {
            if(data.success){
                renderizarArchivos(data.archivos);
            } else {
                alert('Error al escanear');
            }
        })
        .catch(err => console.error('Error en escaneo:', err));
}

// escaneo manual: muestra el modal de carga durante unos segundos aleatorios (5-15s)
const escanear = () => {
    btnEscanear.disabled = true;
    mostrarModalEscaneo();

    const duracion = Math.floor(Math.random() * (15000 - 5000 + 1)) + 5000;

    setTimeout(() => {
        ejecutarEscaneo().finally(() => {
            ocultarModalEscaneo();
            btnEscanear.disabled = false;
        });
    }, duracion);
}

// agrega las filas nuevas al inicio de la tabla
const renderizarArchivos = (archivos) => {
    // quitar el mensaje de "no hay archivos" si existe
    const sinDatos = cuerpoTabla.querySelector('.sin-datos');
    if(sinDatos) sinDatos.parentElement.remove();

    archivos.forEach(archivo => {
        const fila = document.createElement('tr');
        fila.dataset.id = archivo.id;
        fila.innerHTML = `
            <td>${archivo.nombre}</td>
            <td>${archivo.tamanio}</td>
            <td>${archivo.fecha_detectado}</td>
            <td>SOC-Analyst</td>
            <td><span class="badge badge-normal">Normal</span></td>
            <td><button class="btn btn-marcar" onclick="togglePeligroso(${archivo.id}, this)">Marcar peligroso</button></td>
        `;
        // insertar al inicio de la tabla
        cuerpoTabla.insertBefore(fila, cuerpoTabla.firstChild);
    });
}

btnEscanear.addEventListener('click', escanear);

// ================================
// Marcar archivo como peligroso
// ================================

// envia el id al PHP y actualiza la fila visualmente
const togglePeligroso = (id, boton) => {
    const formData = new FormData();
    formData.append('id', id);

    fetch('peligroso.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            const fila  = boton.closest('tr');
            const badge = fila.querySelector('.badge');

            // alternar clase y texto segun el estado actual
            if(fila.classList.contains('fila-peligrosa')){
                fila.classList.remove('fila-peligrosa');
                badge.className  = 'badge badge-normal';
                badge.textContent = 'Normal';
                boton.textContent = 'Marcar peligroso';
            } else {
                fila.classList.add('fila-peligrosa');
                badge.className  = 'badge badge-peligroso';
                badge.textContent = 'Peligroso';
                boton.textContent = 'Desmarcar';
            }
        }
    })
    .catch(err => console.error('Error al marcar:', err));
}

// ================================
// Reportes externos (JSONPlaceholder)
// ================================

const cargarReportes = () => {
    mostrarModalReportes();

    const listaReportes = document.getElementById('lista-reportes');
    listaReportes.innerHTML = '<p>Cargando reportes...</p>';

    // el PHP consulta JSONPlaceholder por su cuenta usando ApiClient (cURL)
    fetch('reportes.php')
        .then(res => res.json())
        .then(data => {
            if(!data.success){
                listaReportes.innerHTML = '<p>Error al cargar reportes.</p>';
                return;
            }

            listaReportes.innerHTML = '';

            data.reportes.forEach(post => {
                const item = document.createElement('div');
                item.classList.add('reporte-item');
                item.innerHTML = `
                    <h4>Incidente #${post.id}: ${post.title}</h4>
                    <p>${post.body}</p>
                `;
                listaReportes.appendChild(item);
            });
        })
        .catch(err => {
            listaReportes.innerHTML = '<p>Error al cargar reportes.</p>';
            console.error('Error al cargar reportes:', err);
        });
}

btnReportes.addEventListener('click', cargarReportes);

// ================================
// Auto-escaneo cada 60 segundos
// ================================

let segundos = 60;

// cuenta regresiva visual
const intervaloContador = setInterval(() => {
    segundos--;
    cuentaRegresiva.textContent = segundos;

    if(segundos <= 0){
        segundos = 60;
        ejecutarEscaneo(); // disparar escaneo automatico, sin modal
    }
}, 1000);
