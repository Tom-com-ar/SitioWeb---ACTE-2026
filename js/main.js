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
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.innerHTML = '🌙';
    toggleBtn.classList.add('toggle-btn');
    toggleBtn.setAttribute('aria-label', 'Cambiar entre modo claro y oscuro');
    toggleBtn.addEventListener('click', toggleDarkMode);

    if (sidenav) {
        toggleBtn.classList.add('toggle-btn--sidenav');
        const li = document.createElement('li');
        li.appendChild(toggleBtn);
        sidenav.appendChild(li);
    } else {
        toggleBtn.classList.add('toggle-btn--floating');
        toggleBtn.style.position = 'fixed';
        toggleBtn.style.top = '20px';
        toggleBtn.style.right = '20px';
        toggleBtn.style.zIndex = '1000';
        document.body.appendChild(toggleBtn);
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
        });
}


function initFormulario() {

    const form = document.getElementById("formInscripcion");
    if (!form) return;

    form.addEventListener("submit", e => {
        e.preventDefault();

        const nombre = document.getElementById("ins_nombre").value;
        const email = document.getElementById("ins_email").value;
        const telefono = document.getElementById("ins_telefono").value;

        const talleres = [];
        document.querySelectorAll("#listaTalleres input:checked")
            .forEach(cb => talleres.push(cb.value));

        const data = { nombre, email, telefono, talleres };

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
        .then(res => res.text())
        .then(() => alert("Inscripción exitosa"));
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


document.addEventListener("DOMContentLoaded", () => {
    cargarTalleres();
    initFormulario();

    window.addEventListener("online", sincronizar);
});