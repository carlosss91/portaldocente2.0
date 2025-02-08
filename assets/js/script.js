// ------------------  FUNCIONES GENERALES ------------------ 

// Alternar colapso de la barra lateral
function toggleSidebar() {
    // Alterna la clase 'collapsed' en el elemento con id 'sidebar'
    document.getElementById("sidebar").classList.toggle("collapsed");
}

// ------------------  BOTÓN DE USUARIO ------------------
// Alternar el botón de usuario
function toggleUserMenu(event) {
    event.stopPropagation(); // 🔹 Evita que el clic se propague al `window.onclick`
    let dropdown = document.getElementById("userDropdown");
    dropdown.classList.toggle("show-dropdown");
}

// Cierra el menú desplegable si se hace clic fuera
document.addEventListener("click", function(event) {
    let userIcon = document.querySelector(".user-icon");
    let dropdown = document.getElementById("userDropdown");

    // 🔹 Si el clic NO es en el icono de usuario ni en el menú desplegable, se oculta
    if (!userIcon.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove("show-dropdown");
    }
});

// ✅ Asegurar que el clic en el icono no cierre el menú inmediatamente
document.querySelector(".user-icon").addEventListener("click", function(event) {
    toggleUserMenu(event);
});

// ------------------  DESPLIEGUE DE NOTICIAS ------------------ 

// Alternar la visualización de una noticia específica
function toggleNews(id) {
    // Obtiene el contenido y la tarjeta de la noticia por id
    let content = document.getElementById("news-" + id);
    let card = document.getElementById("news-card-" + id);

    // Si la noticia ya está abierta, la cerramos
    if (content.classList.contains("show-news")) {
        content.classList.remove("show-news");
        card.classList.remove("expanded-news");
        return;
    }

    // Cerrar todas las demás noticias antes de abrir la seleccionada
    document.querySelectorAll(".news-content").forEach(function (item) {
        item.classList.remove("show-news");
        let otherCard = item.closest(".news-card");
        if (otherCard) otherCard.classList.remove("expanded-news");
    });

    // Abrir solo la noticia seleccionada
    content.classList.add("show-news");
    card.classList.add("expanded-news");
}

  // AJUSTAR TAMAÑO DE IMÁGENES DE NOTICIAS
document.addEventListener("DOMContentLoaded", function() {
    // Selecciona todas las tarjetas de noticias
    const newsCards = document.querySelectorAll('.news-card');
    
    // Recorre cada tarjeta de noticias
    newsCards.forEach(card => {
        // Selecciona la imagen dentro de la tarjeta
        const img = card.querySelector('.news-card-img');
        
        // Si la imagen existe
        if (img) {
            // Obtiene la altura de la tarjeta
            const cardHeight = card.clientHeight;
            // Obtiene la altura de la imagen
            const imgHeight = img.clientHeight;
            
            // Si la altura de la imagen es menor que la altura de la tarjeta
            if (imgHeight < cardHeight) {
                // Ajusta la altura de la imagen para que coincida con la altura de la tarjeta
                img.style.height = cardHeight + 'px';
            }
        }
    });
});

// ------------------  ICONOS DINÁMICOS (GIF) ------------------ 

// Cambiar iconos estáticos a GIFs al pasar el ratón
document.addEventListener("DOMContentLoaded", function () {
    // Selecciona todos los iconos relevantes
    let icons = document.querySelectorAll(".sidebar-icon, .user-icon, .toggle-icon");

    // Itera sobre cada icono y agrega eventos de mouseenter y mouseleave
    icons.forEach(function (icon) {
        icon.addEventListener("mouseenter", function () {
            // Cambia la fuente del icono a GIF al pasar el ratón
            let staticSrc = icon.src.replace("_static.png", ".gif");
            icon.src = staticSrc;
        });

        icon.addEventListener("mouseleave", function () {
            // Cambia la fuente del icono de vuelta a PNG estático al quitar el ratón
            let gifSrc = icon.src.replace(".gif", "_static.png");
            icon.src = gifSrc;
        });
    });
});

// ------------------  FUNCIÓN DE BÚSQUEDA ------------------ 

// Realizar búsqueda al hacer clic en el botón de búsqueda
document.addEventListener("DOMContentLoaded", function () {
    // Selecciona el botón de búsqueda y la barra de búsqueda
    let searchBtn = document.querySelector(".search-btn");
    let searchInput = document.querySelector(".search-bar");

    // Agrega un evento de clic al botón de búsqueda
    searchBtn.addEventListener("click", function () {
        // Obtiene el valor de la barra de búsqueda y lo convierte a minúsculas
        let query = searchInput.value.toLowerCase();
        // Si el valor no está vacío, muestra una alerta con el término de búsqueda
        if (query.trim() !== "") {
            alert("🔍 Buscando: " + query);
        }
    });
});

// ------------------  FORMULARIO ADJUDICACIONES ------------------ 

var municipiosPorIsla = {
    "Tenerife": ["Adeje", "Arafo", "Arico", "Arona", "Buenavista del Norte", "Candelaria", "El Rosario", "El Sauzal", "El Tanque", "Fasnia", "Garachico", "Granadilla de Abona", "Guía de Isora", "Güímar", "Icod de los Vinos", "La Guancha", "La Matanza de Acentejo", "La Orotava", "La Victoria de Acentejo", "Los Realejos", "Puerto de la Cruz", "San Cristóbal de La Laguna", "San Juan de la Rambla", "San Miguel de Abona", "Santa Cruz de Tenerife", "Santa Úrsula", "Santiago del Teide", "Tacoronte", "Tegueste", "Vilaflor de Chasna"],
    "Gran Canaria": ["Agaete", "Agüimes", "Artenara", "Arucas", "Firgas", "Gáldar", "Ingenio", "La Aldea de San Nicolás", "Las Palmas de Gran Canaria", "Mogán", "Moya", "San Bartolomé de Tirajana", "Santa Brígida", "Santa Lucía de Tirajana", "Santa María de Guía", "Tejeda", "Telde", "Teror", "Valleseco", "Valsequillo de Gran Canaria", "Vega de San Mateo"],
    "Lanzarote": ["Arrecife", "Haría", "San Bartolomé", "Teguise", "Tías", "Tinajo", "Yaiza"],
    "Fuerteventura": ["Antigua", "Betancuria", "La Oliva", "Pájara", "Puerto del Rosario", "Tuineje"],
    "La Palma": ["Barlovento", "Breña Alta", "Breña Baja", "Fuencaliente", "Garafía", "Los Llanos de Aridane", "El Paso", "Puntagorda", "Puntallana", "San Andrés y Sauces", "Santa Cruz de La Palma", "Tazacorte", "Tijarafe", "Villa de Mazo"],
    "La Gomera": ["Agulo", "Alajeró", "Hermigua", "San Sebastián de La Gomera", "Valle Gran Rey", "Vallehermoso"],
    "El Hierro": ["Frontera", "El Pinar de El Hierro", "Valverde"],
    "La Graciosa": ["Caleta de Sebo"]
};

// Función para actualizar municipios cuando se seleccione una isla
function actualizarMunicipios(select, index) {
    var islaSeleccionada = select.value;
    var municipioSelect = document.getElementsByName(`adjudicaciones[${index}][municipio]`)[0];

    // Limpiar el select de municipios
    municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';

    // Si la isla tiene municipios, agregarlos al select
    if (municipiosPorIsla[islaSeleccionada]) {
        municipiosPorIsla[islaSeleccionada].forEach(municipio => {
            let option = document.createElement("option");
            option.value = municipio;
            option.textContent = municipio;
            municipioSelect.appendChild(option);
        });
    }
}

function toggleFormulario() {
    var formulario = document.getElementById("formularioAdjudicacion");
    if (formulario.style.display === "none" || formulario.style.display === "") {
        formulario.style.display = "block";
    } else {
        formulario.style.display = "none";
    }
}


