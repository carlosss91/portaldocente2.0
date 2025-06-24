// *****************************************
//          CONFIGURACIÓN GLOBAL            
// *****************************************

// Alterna el estado colapsado de la barra lateral
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("collapsed");
}


// *****************************************
//         GESTIÓN DEL MENÚ DE USUARIO       
// *****************************************

// Alterna la visibilidad del dropdown de usuario
function toggleUserMenu(event) {
  event.stopPropagation();
  document.getElementById("userDropdown").classList.toggle("show-dropdown");
}

// Configura eventos para el menú de usuario
function setupUserMenu() {
  const icon = document.querySelector(".user-icon");
  const dropdown = document.getElementById("userDropdown");

  icon?.addEventListener("click", toggleUserMenu);
  document.addEventListener("click", event => {
    if (!icon.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.classList.remove("show-dropdown");
    }
  });
}
 //Función para mostrar/ocultar contraseñas 
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.src  = icon.src.replace('eye_closed','eye_open');
        } else {
            field.type = 'password';
            icon.src  = icon.src.replace('eye_open','eye_closed');
        }
    }

// *****************************************
//       LÓGICA DE DESPLIEGUE DE NOTICIAS    
// *****************************************

// Expande o colapsa una tarjeta de noticia específica
function toggleNews(id) {
  const content = document.getElementById(`news-${id}`);
  const card    = document.getElementById(`news-card-${id}`);

  // Cierra todas las noticias antes de abrir la seleccionada
  document.querySelectorAll(".news-content").forEach(item => {
    item.classList.remove("show-news");
    item.closest(".news-card")?.classList.remove("expanded-news");
  });

  if (content && card) {
    content.classList.add("show-news");
    card.classList.add("expanded-news");
  }
}

// Ajusta la altura de las imágenes para que llenen la tarjeta
function setupNewsImages() {
  document.querySelectorAll(".news-card").forEach(card => {
    const img = card.querySelector(".news-card-img");
    if (!img) return;
    if (img.clientHeight < card.clientHeight) img.style.height = `${card.clientHeight}px`;
  });
}


// *****************************************
//       ANIMACIONES DE ICONOS DINÁMICOS     
// *****************************************

function setupDynamicIcons() {
  document.querySelectorAll(".sidebar-icon, .user-icon, .toggle-icon").forEach(icon => {
    icon.addEventListener("mouseenter", () =>
      icon.src = icon.src.replace("_static.png", ".gif")
    );
    icon.addEventListener("mouseleave", () =>
      icon.src = icon.src.replace(".gif", "_static.png")
    );
  });
}


// *****************************************
//               BÚSQUEDA RÁPIDA            
// *****************************************

function setupSearch() {
  const btn   = document.querySelector(".search-btn");
  const input = document.querySelector(".search-bar");
  btn?.addEventListener("click", () => {
    const q = input.value.trim();
    if (q) alert(`Buscando: ${q}`);
  });
}


// *****************************************
//     LÓGICA FORMULARIO ADJUDICACIONES      
// *****************************************
 //MUNICIPIOS POR ISLA
const municipiosPorIsla = {
    "Tenerife": ["Adeje", "Arafo", "Arico", "Arona", "Buenavista del Norte", "Candelaria", "El Rosario", "El Sauzal", "El Tanque", "Fasnia", "Garachico", "Granadilla de Abona", "Guía de Isora", "Güímar", "Icod de los Vinos", "La Guancha", "La Matanza de Acentejo", "La Orotava", "La Victoria de Acentejo", "Los Realejos", "Puerto de la Cruz", "San Cristóbal de La Laguna", "San Juan de la Rambla", "San Miguel de Abona", "Santa Cruz de Tenerife", "Santa Úrsula", "Santiago del Teide", "Tacoronte", "Tegueste", "Vilaflor de Chasna"],
    "Gran Canaria": ["Agaete", "Agüimes", "Artenara", "Arucas", "Firgas", "Gáldar", "Ingenio", "La Aldea de San Nicolás", "Las Palmas de Gran Canaria", "Mogán", "Moya", "San Bartolomé de Tirajana", "Santa Brígida", "Santa Lucía de Tirajana", "Santa María de Guía", "Tejeda", "Telde", "Teror", "Valleseco", "Valsequillo de Gran Canaria", "Vega de San Mateo"],
    "Lanzarote": ["Arrecife", "Haría", "San Bartolomé", "Teguise", "Tías", "Tinajo", "Yaiza"],
    "Fuerteventura": ["Antigua", "Betancuria", "La Oliva", "Pájara", "Puerto del Rosario", "Tuineje"],
    "La Palma": ["Barlovento", "Breña Alta", "Breña Baja", "Fuencaliente", "Garafía", "Los Llanos de Aridane", "El Paso", "Puntagorda", "Puntallana", "San Andrés y Sauces", "Santa Cruz de La Palma", "Tazacorte", "Tijarafe", "Villa de Mazo"],
    "La Gomera": ["Agulo", "Alajeró", "Hermigua", "San Sebastián de La Gomera", "Valle Gran Rey", "Vallehermoso"],
    "El Hierro": ["Frontera", "El Pinar de El Hierro", "Valverde"],
    "La Graciosa": ["Caleta de Sebo"]
};

// Actualiza la lista de municipios según la isla seleccionada
function actualizarMunicipios(select) {
  const muniSelect = document.getElementById("municipio");
  muniSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
  (municipiosPorIsla[select.value] || []).forEach(m => {
    const opt = document.createElement("option");
    opt.value = m;
    opt.textContent = m;
    muniSelect.appendChild(opt);
  });
}

// Alterna la visibilidad del formulario de adjudicaciones
function toggleFormularioAdjudicaciones() {
  const f = document.getElementById("formularioAdjudicacion");
  f.style.display = (f.style.display === "block") ? "none" : "block";
}

// Elimina una adjudicación tras confirmación
function eliminarAdjudicacion(btn) {
  if (!confirm("¿Seguro que deseas eliminar esta adjudicación?")) return;
  const id = btn.closest("tr").dataset.id;
  const f  = document.createElement("form");
  f.method = "POST";
  f.action = "../controllers/AdjudicacionController.php";
  f.innerHTML = `
    <input type="hidden" name="id_adjudicacion" value="${id}">
    <input type="hidden" name="accion" value="eliminar">
  `;
  document.body.appendChild(f);
  f.submit();
}
document.addEventListener("DOMContentLoaded", function() {
  const tbody = document.getElementById("adjudicaciones-tbody");
  if (!tbody) return;

  // Devuelve el valor de la celda en la columna idx
  const getCellValue = (tr, idx) => tr.children[idx].textContent.trim();

  // Guarda el sentido de orden para cada columna
  let sortDirection = {};

  // Asocia evento a cada botón de ordenación
  document.querySelectorAll(".sort-btn").forEach(btn => {
    btn.addEventListener("click", function() {
      const sortKey = btn.dataset.sort;
      let idx;
      if (sortKey === "orden") idx = 0;
      else if (sortKey === "isla") idx = 1;
      else if (sortKey === "municipio") idx = 2;
      else if (sortKey === "fecha") idx = 3;

      const rows = Array.from(tbody.querySelectorAll("tr"));
      sortDirection[sortKey] = !sortDirection[sortKey]; // Alterna asc/desc

      rows.sort((a, b) => {
        let valA = getCellValue(a, idx);
        let valB = getCellValue(b, idx);

        if (sortKey === "orden") {
          // Ordenar por número (descendente por defecto)
          return sortDirection[sortKey]
            ? Number(valA) - Number(valB)
            : Number(valB) - Number(valA);
        } else if (sortKey === "fecha") {
          // Ordenar por fecha (más reciente primero)
          return sortDirection[sortKey]
            ? valA.localeCompare(valB)
            : valB.localeCompare(valA);
        } else {
          // Ordenar alfabéticamente
          return sortDirection[sortKey]
            ? valA.localeCompare(valB)
            : valB.localeCompare(valA);
        }
      });

      // Vuelve a añadir las filas ordenadas al tbody
      rows.forEach(tr => tbody.appendChild(tr));
    });
  });
});


// *****************************************
//      LÓGICA FORMULARIO SOLICITUDES        
// *****************************************

// Alterna la visibilidad del formulario de solicitudes
function toggleFormularioSolicitudes() {
  const f = document.getElementById("formularioSolicitud");
  f.style.display = (f.style.display === "block") ? "none" : "block";
}

// Elimina una solicitud tras confirmación
function eliminarSolicitud(btn) {
  if (!confirm("¿Seguro que deseas eliminar esta solicitud?")) return;
  const id = btn.closest("tr").dataset.id;
  const f  = document.createElement("form");
  f.method = "POST";
  f.action = "../controllers/SolicitudController.php";
  f.innerHTML = `
    <input type="hidden" name="id_solicitud" value="${id}">
    <input type="hidden" name="accion" value="eliminar">
  `;
  document.body.appendChild(f);
  f.submit();
}


// *****************************************
//       PANEL ADMINISTRACIÓN - USUARIOS     
// *****************************************

function setupUserPanel() {
  const btnNuevo  = document.getElementById("btnNuevoUsuario");
  const formDiv   = document.getElementById("formularioUsuario");
  const form      = document.getElementById("formUsuario");
  const btnCancel = document.getElementById("formCancel");

  // Campos del formulario de usuario
  const campos = ["Action","IdUsuario","Nombre","Apellido","Dni","Email","Telefono","Password","Rol","Isla","Disponibilidad","Puntuacion"];
  const f = {};
  campos.forEach(c => f[c] = document.getElementById(`form${c}`));

  // Nuevo usuario
  btnNuevo?.addEventListener("click", () => {
    form.reset();
    f.Action.value        = "crear";
    f.IdUsuario.value     = "";
    f.Password.required   = true;
    document.getElementById("formSubmit").textContent = "Crear";
    formDiv.style.display = "block";
    f.Nombre.focus();
  });

  // Editar usuario
  document.querySelectorAll("button.btn-editar").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      const row = btn.closest("tr"); if (!row) return;
      form.reset();
      f.Action.value      = "editar";
      campos.slice(1).forEach((c, i) => f[c].value = row.dataset[c.toLowerCase()] || '');
      f.Password.required = false;
      document.getElementById("formSubmit").textContent = "Actualizar";
      formDiv.style.display = "block";
      f.Nombre.focus();
    });
  });

  // Cancelar formulario usuario
  btnCancel?.addEventListener("click", () => formDiv.style.display = "none");
}


// *****************************************
//       PANEL ADMINISTRACIÓN - NOTICIAS     
// *****************************************

function setupNewsPanel() {
  const btnNueva     = document.getElementById("btnNuevaNoticia");
  const divForm      = document.getElementById("formularioNoticia");
  const form         = document.getElementById("formNoticia");

  // Campos del formulario de noticias
  const fAction        = document.getElementById("formNoticiaAction");
  const fId            = document.getElementById("formIdNoticia");
  const fTitulo        = document.getElementById("formTitulo");
  const fContenido     = document.getElementById("formContenido");
  const fImagen        = document.getElementById("formImagen");
  const fSubmitNoticia = document.getElementById("formSubmitNoticia");
  const btnCancelNoticia = document.getElementById("formCancelNoticia");

  // Nueva noticia
  btnNueva?.addEventListener("click", () => {
    form.reset();
    fAction.value = "crear";
    fId.value     = "";
    fSubmitNoticia.textContent = "Crear";
    divForm.style.display = "block";
    fTitulo.focus();
  });

  // Editar noticia
  document.querySelectorAll(".btn-editar-noticia").forEach(btn => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr"); if (!row) return;
      form.reset();
      fAction.value        = "editar";
      fId.value            = row.dataset.id;
      fTitulo.value        = row.dataset.titulo;
      fContenido.value     = row.dataset.contenido;
      fImagen.value        = row.dataset.imagen_url || '';
      fSubmitNoticia.textContent = "Actualizar";
      divForm.style.display = "block";
      fTitulo.focus();
    });
  });

  // Cancelar formulario noticias
  btnCancelNoticia?.addEventListener("click", () => divForm.style.display = "none");
}


// *****************************************
//             INICIALIZACIÓN              
// *****************************************

document.addEventListener("DOMContentLoaded", () => {
  setupUserMenu();
  setupNewsImages();
  setupDynamicIcons();
  setupSearch();
  setupUserPanel();
  setupNewsPanel();
});
