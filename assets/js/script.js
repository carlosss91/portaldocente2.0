// Función para colapsar/expandir la barra lateral
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
}

// Función para mostrar el menú desplegable del usuario
function toggleUserMenu() {
    document.getElementById("userDropdown").classList.toggle("show");
}

// Cierra el menú desplegable si se hace clic fuera
window.onclick = function(event) {
    if (!event.target.matches('.user-icon')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

//MOVIMIENTO ICONOS
document.addEventListener("DOMContentLoaded", function () {
    let icons = document.querySelectorAll(".sidebar-icon, .user-icon"); // 🔥 Aplica efecto a ambos tipos de iconos

    icons.forEach(function (icon) {
        // Cambiar a GIF cuando se pasa el ratón
        icon.addEventListener("mouseenter", function () {
            let staticSrc = icon.src.replace("_static.png", ".gif"); // Reemplaza el nombre del archivo
            icon.src = staticSrc;
        });

        // Volver a la imagen estática cuando se quita el ratón
        icon.addEventListener("mouseleave", function () {
            let gifSrc = icon.src.replace(".gif", "_static.png"); // Reemplaza el nombre del archivo
            icon.src = gifSrc;
        });
    });
});


// Mostrar/ocultar el menú al hacer clic en el icono de usuario
document.addEventListener("DOMContentLoaded", function () {
    let userIcon = document.querySelector(".user-icon");
    let dropdown = document.getElementById("userDropdown");

    userIcon.addEventListener("click", function (event) {
        dropdown.classList.toggle("show-dropdown");
        event.stopPropagation(); // Evitar que se cierre inmediatamente
    });

    // Cerrar el menú si se hace clic fuera de él
    document.addEventListener("click", function (event) {
        if (!userIcon.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove("show-dropdown");
        }
    });
});