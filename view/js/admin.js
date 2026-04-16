document.addEventListener('DOMContentLoaded', () => {

    // --- 1. PREVISUALIZACIÓN DE MEDIOS (BANNER Y FOTO DE PERFIL) ---
    function activarPreview(idInput, isVideo = false) {
        const input = document.getElementById(idInput);
        if (!input) return;

        input.addEventListener('change', (event) => {
            const archivo = event.target.files[0];
            const reader = new FileReader();
            
            // Busca el contenedor circular hermano
            const previewCircle = input.parentElement.parentElement.querySelector('.banner-preview-circle, .avatar-preview-container');
            
            if(!previewCircle) return;

            reader.onload = function(e) {
                previewCircle.innerHTML = ''; // Limpia el emoji placeholder
                let medioElemento = document.createElement(isVideo ? 'video' : 'img');
                
                if(isVideo){
                    medioElemento.autoplay = true;
                    medioElemento.muted = true;
                    medioElemento.loop = true;
                }
                
                medioElemento.src = e.target.result;
                previewCircle.appendChild(medioElemento);
            }
            if(archivo) reader.readAsDataURL(archivo);
        });
    }

    // Activar inputs
    activarPreview('banner_imagen', false);
    activarPreview('banner_video', true);
    activarPreview('foto_perfil', false);

    // --- 1.1 MOSTRAR NOMBRE DE ARCHIVO SELECCIONADO EN DISEÑO ---
    function activarNombreArchivoEnDiseno() {
        const vistaDiseno = document.getElementById('vista-diseno');
        if (!vistaDiseno) return;

        const fileInputs = vistaDiseno.querySelectorAll('input[type="file"]');
        fileInputs.forEach((input) => {
            input.addEventListener('change', () => {
                const nombre = input.files && input.files.length > 0
                    ? (input.files.length > 1
                        ? input.files.length + ' archivos seleccionados'
                        : input.files[0].name)
                    : 'Ningun archivo seleccionado';

                let indicador = input.parentElement.querySelector('.file-name-selected');
                if (!indicador) {
                    indicador = document.createElement('div');
                    indicador.className = 'file-name-selected';
                    input.parentElement.appendChild(indicador);
                }

                indicador.textContent = 'Archivo: ' + nombre;
            });
        });
    }

    activarNombreArchivoEnDiseno();

    // Mostrar vista inicial segun query (?vista=mensajes), o inicio por defecto
    const vistaParam = new URLSearchParams(window.location.search).get('vista');
    const vistasPermitidas = ['inicio', 'diseno', 'mensajes', 'tienda', 'usuarios', 'accesos'];
    const vistaInicial = vistasPermitidas.includes(vistaParam) ? vistaParam : 'inicio';
    cambiarVista(vistaInicial);

});

// --- 2. MOTOR DE CAMBIO DE VISTAS ---
// Esta función va FUERA del DOMContentLoaded para que el HTML la pueda llamar
function cambiarVista(vistaDestino) {
    const todasLasVistas = ['inicio', 'diseno', 'mensajes', 'tienda', 'usuarios', 'accesos'];
    
    // Apagar todas
    todasLasVistas.forEach(vista => {
        let divVista = document.getElementById('vista-' + vista);
        let btnMenu = document.getElementById('btn-' + vista);
        
        if (divVista) divVista.style.display = 'none';
        if (btnMenu) btnMenu.classList.remove('active');
    });

    // Prender solo la elegida
    let vistaMostrar = document.getElementById('vista-' + vistaDestino);
    let btnActivar = document.getElementById('btn-' + vistaDestino);

    if (vistaMostrar) {
        vistaMostrar.style.display = 'block';
    } else {
        console.warn("La vista " + vistaDestino + " no existe.");
    }

    if (btnActivar) {
        btnActivar.classList.add('active');
    }

    // Mantener la vista activa en la URL para que al refrescar no salte a otra sección.
    if (vistasPermitidasURL().includes(vistaDestino)) {
        const url = new URL(window.location.href);
        url.searchParams.set('vista', vistaDestino);
        window.history.replaceState({}, '', url.toString());
    }
}

function vistasPermitidasURL() {
    return ['inicio', 'diseno', 'mensajes', 'tienda', 'usuarios', 'accesos'];
}