<?php
// ╔═════════════════════════════════════════╗
// ║     CONTROLADOR DE NOTICIAS            ║
// ╚═════════════════════════════════════════╝

session_start(); // Maneja la sesión del usuario

// Mostrar errores para depuración (remover en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Noticia.php'; // Modelo de noticias

class NoticiaController {
    private $noticiaModel;

    // ╔════════════════╗
    // ║   CONSTRUCTOR  ║
    // ╚════════════════╝
    public function __construct() {
        $this->noticiaModel = new Noticia();
    }

    // ╔═════════════════════════════════════════╗
    // ║   MOSTRAR Noticias para la vista       ║
    // ╚═════════════════════════════════════════╝
    public function mostrarNoticias() {
        return $this->noticiaModel->obtenerNoticias();
    }

    // ╔═════════════════════════════════════════╗
    // ║   AGREGAR NUEVA NOTICIA                ║
    // ╚═════════════════════════════════════════╝
    public function agregarNoticia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_SESSION['id_usuario']) ||
            $_SESSION['rol'] !== 'administrador'
        ) {
            header('Location: ../views/admin.php?error=Acceso denegado');
            exit();
        }

        // Recoger y sanitizar campos
        $titulo     = trim($_POST['titulo']      ?? '');
        $contenido  = trim($_POST['contenido']   ?? '');
        $imagenUrl  = trim($_POST['imagen_url']  ?? '');
        $autorId    = intval($_SESSION['id_usuario']);
        if ($imagenUrl === '') {
            $imagenUrl = null;
        }

        // Validar campos obligatorios
        if ($titulo === '' || $contenido === '') {
            header('Location: ../views/admin.php?error=Faltan campos obligatorios');
            exit();
        }

        // Insertar noticia
        $ok = $this->noticiaModel->agregarNoticia(
            $titulo,
            $contenido,
            $imagenUrl,
            $autorId
        );

        if ($ok) {
            header('Location: ../views/admin.php?mensaje=Noticia creada correctamente');
        } else {
            header('Location: ../views/admin.php?error=Error al crear noticias');
        }
        exit();
    }

    // ╔═════════════════════════════════════════╗
    // ║   EDITAR NOTICIA EXISTENTE              ║
    // ╚═════════════════════════════════════════╝
    public function editarNoticia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_SESSION['id_usuario']) ||
            $_SESSION['rol'] !== 'administrador'
        ) {
            header('Location: ../views/admin.php?error=Acceso denegado');
            exit();
        }

        // Recoger y sanitizar campos
        $id          = intval($_POST['id_noticia']  ?? 0);
        $titulo      = trim($_POST['titulo']        ?? '');
        $contenido   = trim($_POST['contenido']     ?? '');
        $imagenUrl   = trim($_POST['imagen_url']    ?? '');
        if ($imagenUrl === '') {
            $imagenUrl = null;
        }

        // Validar datos
        if ($id <= 0 || $titulo === '' || $contenido === '') {
            header('Location: ../views/admin.php?error=Datos inválidos');
            exit();
        }

        // Actualizar noticia
        $ok = $this->noticiaModel->actualizarNoticia(
            $id,
            $titulo,
            $contenido,
            $imagenUrl
        );

        if ($ok) {
            header('Location: ../views/admin.php?mensaje=Noticia actualizada correctamente');
        } else {
            header('Location: ../views/admin.php?error=Error al actualizar noticia');
        }
        exit();
    }

    // ╔═════════════════════════════════════════╗
    // ║   ELIMINAR NOTICIA                      ║
    // ╚═════════════════════════════════════════╝
    public function eliminarNoticia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_SESSION['id_usuario']) ||
            $_SESSION['rol'] !== 'administrador'
        ) {
            header('Location: ../views/admin.php?error=Acceso denegado');
            exit();
        }

        $id = intval($_POST['id_noticia'] ?? 0);
        if ($id <= 0) {
            header('Location: ../views/admin.php?error=ID inválido');
            exit();
        }

        $ok = $this->noticiaModel->eliminarNoticia($id);
        if ($ok) {
            header('Location: ../views/admin.php?mensaje=Noticia eliminada correctamente');
        } else {
            header('Location: ../views/admin.php?error=Error al eliminar noticia');
        }
        exit();
    }
}

// ╔═════════════════════════════════════════╗
// ║       INSTANCIACIÓN Y RUTEO            ║
// ╚═════════════════════════════════════════╝
$noticiaController = new NoticiaController();

// Procesar acciones solo si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'crear':
            $noticiaController->agregarNoticia();
            break;
        case 'editar':
            $noticiaController->editarNoticia();
            break;
        case 'eliminar':
            $noticiaController->eliminarNoticia();
            break;
        // Sin caso por defecto para GET
    }
}
?>
