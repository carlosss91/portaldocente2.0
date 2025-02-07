// ------------------ 🌟 FUNCIONES GENERALES ------------------ 

// 🟢 Alternar colapso de la barra lateral
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
}

// 🟢 Alternar el menú de usuario
function toggleUserMenu() {
    document.getElementById("userDropdown").classList.toggle("show-dropdown");
}

// 🔵 Cierra el menú desplegable si se hace clic fuera
window.onclick = function(event) {
    if (!event.target.matches('.user-icon')) {
        let dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            let openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show-dropdown')) {
                openDropdown.classList.remove('show-dropdown');
            }
        }
    }
}

// ------------------ 📰 DESPLIEGUE DE NOTICIAS ------------------ 
function toggleNews(id) {
    document.querySelectorAll(".news-content").forEach(content => {
        if (content.id !== "news-" + id) {
            content.classList.remove("show-news");
        }
    });

    let content = document.getElementById("news-" + id);
    content.classList.toggle("show-news");
}

// ------------------ 🎨 ICONOS DINÁMICOS (GIF) ------------------
document.addEventListener("DOMContentLoaded", function () {
    let icons = document.querySelectorAll(".sidebar-icon, .user-icon, .toggle-icon"); 

    icons.forEach(function (icon) {
        icon.addEventListener("mouseenter", function () {
            let staticSrc = icon.src.replace("_static.png", ".gif");
            icon.src = staticSrc;
        });

        icon.addEventListener("mouseleave", function () {
            let gifSrc = icon.src.replace(".gif", "_static.png");
            icon.src = gifSrc;
        });
    });
});

// ------------------ 🔍 FUNCIÓN DE BÚSQUEDA ------------------
document.addEventListener("DOMContentLoaded", function () {
    let searchBtn = document.querySelector(".search-btn");
    let searchInput = document.querySelector(".search-bar");

    searchBtn.addEventListener("click", function () {
        let query = searchInput.value.toLowerCase();
        if (query.trim() !== "") {
            alert("🔍 Buscando: " + query); // 📌 Aquí podríamos implementar búsqueda real
        }
    });
});
