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

// Agregar botón de alternancia
function addToggleButton() {
    const nav = document.querySelector('.nav-wrapper');
    let toggleBtn = document.createElement('button');
    toggleBtn.innerHTML = '🌙'; // Icono de luna para modo oscuro
    toggleBtn.classList.add('toggle-btn');
    toggleBtn.addEventListener('click', toggleDarkMode);

    if (nav) {
        // Agregar al nav si existe
        nav.appendChild(toggleBtn);
    } else {
        // Agregar como botón flotante si no hay nav
        toggleBtn.style.position = 'fixed';
        toggleBtn.style.top = '20px';
        toggleBtn.style.right = '20px';
        toggleBtn.style.zIndex = '1000';
        document.body.appendChild(toggleBtn);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    loadDarkMode();
    addToggleButton();
});