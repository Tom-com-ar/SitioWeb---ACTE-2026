// Función para alternar modo oscuro
function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle('dark-mode');
    const isDark = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark);
    updateToggleIcon();
}

// Función para actualizar el icono del botón
function updateToggleIcon() {
    const toggleBtn = document.querySelector('.toggle-btn');
    if (toggleBtn) {
        toggleBtn.innerHTML = document.body.classList.contains('dark-mode') ? '☀️' : '🌙';
    }
}

// Función para cargar el modo desde localStorage
function loadDarkMode() {
    const isDark = localStorage.getItem('darkMode') === 'true';
    if (isDark) {
        document.body.classList.add('dark-mode');
    }
    updateToggleIcon();
}

// Agregar botón de alternancia (menú hamburguesa si existe; si no, flotante)
function addToggleButton() {
    const sidenav = document.querySelector('.sidenav');
    const desktopMenu = document.querySelector('nav .nav-wrapper .container > ul.right.hide-on-med-and-down');

    const crearBoton = () => {
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.innerHTML = '🌙';
        toggleBtn.classList.add('toggle-btn');
        toggleBtn.setAttribute('aria-label', 'Cambiar entre modo claro y oscuro');
        toggleBtn.addEventListener('click', toggleDarkMode);
        return toggleBtn;
    };

    if (desktopMenu) {
        const toggleBtnDesktop = crearBoton();
        toggleBtnDesktop.classList.add('toggle-btn--desktop');
        const liDesktop = document.createElement('li');
        liDesktop.appendChild(toggleBtnDesktop);
        desktopMenu.appendChild(liDesktop);
    }

    if (sidenav) {
        const toggleBtnSidenav = crearBoton();
        toggleBtnSidenav.classList.add('toggle-btn--sidenav');
        const liSidenav = document.createElement('li');
        liSidenav.appendChild(toggleBtnSidenav);
        sidenav.appendChild(liSidenav);
    } else if (!desktopMenu) {
        const toggleBtnFloating = crearBoton();
        toggleBtnFloating.classList.add('toggle-btn--floating');
        toggleBtnFloating.style.position = 'fixed';
        toggleBtnFloating.style.top = '20px';
        toggleBtnFloating.style.right = '20px';
        toggleBtnFloating.style.zIndex = '1000';
        document.body.appendChild(toggleBtnFloating);
    }
}

// Validación del formulario de registro
function validarRegistro() {
    const registroForm = document.querySelector('form[action="../php/registro.php"]');

    if (!registroForm) return;

    registroForm.addEventListener('submit', (e) => {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return;
        }

        if (password.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
        }
    });
}

// Mostrar mensajes según parámetros en URL
function mostrarErroresURL() {
    const params = new URLSearchParams(window.location.search);

    if (params.get('error') === 'email') {
        alert('Ese correo ya está registrado');
    }

    if (params.get('error') === 'credenciales') {
        alert('Correo o contraseña incorrectos');
    }

    if (params.get('registro') === 'ok') {
        alert('Registro exitoso. Ahora podés iniciar sesión');
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    loadDarkMode();
    addToggleButton();
    validarRegistro();
    mostrarErroresURL();
    cargarTalleres();
    cargarActividades();
    initFormulario();
    cargarTalleresCards();
    cargarActividadesCards();
    window.addEventListener('online', sincronizar);
});


let db;

const request = indexedDB.open("InscripcionesDB", 1);

request.onupgradeneeded = function (e) {
    db = e.target.result;
    db.createObjectStore("pendientes", { autoIncrement: true });
};

request.onsuccess = function (e) {
    db = e.target.result;
    sincronizar();
};

function guardarLocal(data) {
    const tx = db.transaction("pendientes", "readwrite");
    tx.objectStore("pendientes").add(data);
}


function cargarTalleres() {
    fetch("php/obtener_talleres.php")
        .then(res => res.json())
        .then(data => {
            const cont = document.getElementById("listaTalleres");
            cont.innerHTML = "";

            data.forEach(t => {
                cont.innerHTML += `
                    <p>
                        <label>
                            <input type="checkbox" value="${t.id}" />
                            <span>${t.nombre}</span>
                        </label>
                    </p>
                `;
            });

            document.querySelectorAll("#listaTalleres input[type=checkbox]").forEach(cb => {
                cb.addEventListener('change', updateActividadCheckboxes);
            });
            updateActividadCheckboxes();
        });
}

function cargarActividades() {
    fetch("php/obtener_actividades.php")
        .then(res => res.json())
        .then(data => {
            const cont = document.getElementById("listaActividades");
            if (!cont) return;
            cont.innerHTML = "";

            const actividadesPorTaller = data.reduce((acc, actividad) => {
                if (!acc[actividad.taller_id]) {
                    acc[actividad.taller_id] = {
                        taller_nombre: actividad.taller_nombre,
                        items: []
                    };
                }
                acc[actividad.taller_id].items.push(actividad);
                return acc;
            }, {});

            Object.entries(actividadesPorTaller).forEach(([tallerId, grupo]) => {
                cont.innerHTML += `<p><strong>${grupo.taller_nombre}</strong></p>`;
                grupo.items.forEach(actividad => {
                    cont.innerHTML += `
                        <p>
                            <label>
                                <input type="checkbox" class="actividad-checkbox" data-taller-id="${tallerId}" value="${actividad.id}" disabled />
                                <span>${actividad.nombre}</span>
                            </label>
                        </p>
                    `;
                });
            });

            updateActividadCheckboxes();
        });
}

function updateActividadCheckboxes() {
    const selectedTalleres = Array.from(document.querySelectorAll("#listaTalleres input:checked")).map(cb => cb.value);
    document.querySelectorAll("#listaActividades input.actividad-checkbox").forEach(cb => {
        const tallerId = cb.dataset.tallerId;
        if (selectedTalleres.includes(tallerId)) {
            cb.disabled = false;
        } else {
            cb.checked = false;
            cb.disabled = true;
        }
    });
}


function initFormulario() {

    const form = document.getElementById("formInscripcion");
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form) return;

    if (typeof usuarioLogueado !== 'undefined' && !usuarioLogueado) {
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Inicia sesión para inscribirte';
        }
        form.addEventListener("submit", e => {
            e.preventDefault();
            alert("Debes iniciar sesión para inscribirte a los talleres.");
            window.location.href = 'pages/login.html';
        });
        return;
    }

    form.addEventListener("submit", e => {
        e.preventDefault();

        const nombre = document.getElementById("ins_nombre").value;
        const email = document.getElementById("ins_email").value;
        const telefono = document.getElementById("ins_telefono").value;

        const talleres = [];
        document.querySelectorAll("#listaTalleres input:checked")
            .forEach(cb => talleres.push(cb.value));

        const actividades = [];
        document.querySelectorAll("#listaActividades input:checked")
            .forEach(cb => actividades.push(cb.value));

        const data = { nombre, email, telefono, talleres, actividades };

        if (navigator.onLine) {
            enviarServidor(data);
        } else {
            guardarLocal(data);
            alert("Guardado sin conexión");
        }
    });
}


function enviarServidor(data) {

    fetch("php/guardar_inscripcion.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
        .then(res => res.text().then(text => {
            if (!res.ok) {
                throw new Error(text || 'Error al enviar la inscripción');
            }
            return text;
        }))
        .then(() => {
            alert("Inscripción exitosa");
            document.getElementById("formInscripcion").reset();
        })
        .catch(error => {
            alert(error.message || "Error al inscribir");
        });
}


function sincronizar() {

    if (!navigator.onLine) return;

    const tx = db.transaction("pendientes", "readwrite");
    const store = tx.objectStore("pendientes");

    store.openCursor().onsuccess = function (e) {
        const cursor = e.target.result;

        if (cursor) {
            enviarServidor(cursor.value);
            store.delete(cursor.key);
            cursor.continue();
        }
    };
}


function cargarTalleresCards() {

    fetch("php/obtener_talleres.php")
        .then(res => res.json())
        .then(data => {

            const cont = document.getElementById("contenedorTalleres");
            if (!cont) return;

            cont.innerHTML = "";

            data.forEach(t => {

                const imagen = t.imagen 
                    ? t.imagen 
                    : "https://via.placeholder.com/300x200?text=" + t.nombre;

                cont.innerHTML += `
                    <div class="col s12 m6 l4">
                        <div class="card hoverable">

                            <div class="card-image">
                                <img src="${imagen}" alt="${t.nombre}" loading="lazy" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text=Sin+imagen';">
                            </div>

                            <div class="card-content">
                                <span class="card-title">${t.nombre}</span>
                                <p>${t.descripcion}</p>
                            </div>

                        </div>
                    </div>
                `;
            });
        });
}

function cargarActividadesCards() {

    fetch("php/obtener_actividades.php")
        .then(res => res.json())
        .then(data => {

            const cont = document.getElementById("contenedorActividades");
            if (!cont) return;

            cont.innerHTML = "";

            data.forEach(a => {

                const imagen = a.imagen 
                    ? a.imagen 
                    : "https://via.placeholder.com/300x200?text=" + a.nombre;

                cont.innerHTML += `
                    <div class="col s12 m6 l4">
                        <div class="card hoverable">

                            <div class="card-image">
                                <img src="${imagen}" alt="${a.nombre}" loading="lazy" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text=Sin+imagen';">
                            </div>

                            <div class="card-content">
                                <span class="card-title">${a.nombre}</span>
                                <p>${a.descripcion}</p>
                            </div>

                        </div>
                    </div>
                `;
            });
        });
}