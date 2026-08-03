// modal de carga simple, tipo "Escaneando..."

const crearModalEscaneo = () => {
    if(document.getElementById('modal-escaneo')) return;

    const modal = document.createElement('div');
    modal.id = 'modal-escaneo';
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-caja">
            <div class="spinner"></div>
            <p class="modal-texto">Escaneando<span class="puntos-animados"></span></p>
        </div>
    `;
    document.body.appendChild(modal);
}

const mostrarModalEscaneo = () => {
    crearModalEscaneo();
    document.getElementById('modal-escaneo').classList.add('activo');
}

const ocultarModalEscaneo = () => {
    const modal = document.getElementById('modal-escaneo');
    if(modal) modal.classList.remove('activo');
}

// modal de reportes externos (JSONPlaceholder)

const crearModalReportes = () => {
    if(document.getElementById('modal-reportes')) return;

    const modal = document.createElement('div');
    modal.id = 'modal-reportes';
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-caja modal-caja-reportes">
            <button type="button" class="modal-cerrar" aria-label="Cerrar">&times;</button>
            <h2 class="modal-titulo">Reportes de incidentes previos</h2>
            <div id="lista-reportes" class="lista-reportes">
                <p>Cargando reportes...</p>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // cerrar con el boton X o haciendo click fuera de la caja
    modal.querySelector('.modal-cerrar').addEventListener('click', ocultarModalReportes);
    modal.addEventListener('click', (e) => {
        if(e.target === modal) ocultarModalReportes();
    });
}

const mostrarModalReportes = () => {
    crearModalReportes();
    document.getElementById('modal-reportes').classList.add('activo');
}

const ocultarModalReportes = () => {
    const modal = document.getElementById('modal-reportes');
    if(modal) modal.classList.remove('activo');
}

// cerrar cualquier modal abierto con la tecla Escape
document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape'){
        ocultarModalReportes();
    }
});
