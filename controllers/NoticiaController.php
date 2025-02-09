<?php
// Iniciar sesión para manejar el usuario logueado
session_start();

// Mostrar errores en pantalla para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el modelo de Noticia
require_once '../models/Noticia.php';

class NoticiaController {
    private $noticiaModel;

    public function __construct() {
        $this->noticiaModel = new Noticia(); // Instanciar el modelo de Noticia
    }

    // Obtener todas las noticias desde la base de datos
    public function mostrarNoticias() {
        return $this->noticiaModel->obtenerNoticias();
    }

    // Agregar una nueva noticia
    public function agregarNoticia() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Verificar que el usuario esté logueado y sea administrador
            if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
                header("Location: ../index.php?error=Acceso denegado");
                exit();
            }

            // Recoger datos del formulario y sanitizarlos
            $titulo = trim($_POST['titulo'] ?? '');
            $contenido = trim($_POST['contenido'] ?? '');
            $imagen_url = trim($_POST['imagen_url'] ?? ''); // Puede estar vacío
            $autor_id = $_SESSION['id_usuario']; // ID del usuario logueado

            // Validar que título y contenido no estén vacíos
            if (empty($titulo) || empty($contenido)) {
                header("Location: ../views/admin.php?error=Todos los campos obligatorios excepto la imagen");
                exit();
            }

            // Si no hay imagen, insertar como NULL en la base de datos
            $imagen_url = !empty($imagen_url) ? $imagen_url : null;

            // Intentar agregar la noticia a la base de datos
            if ($this->noticiaModel->agregarNoticia($titulo, $contenido, $imagen_url, $autor_id)) {
                header("Location: ../views/admin.php?mensaje=Noticia creada correctamente");
                exit();
            } else {
                header("Location: ../views/admin.php?error=Error al guardar la noticia");
                exit();
            }
        }
    }

    // Eliminar una noticia por su ID
    public function eliminarNoticia() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_noticia'])) {
            // Verificar si el usuario tiene permisos de administrador
            if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
                header("Location: ../index.php?error=Acceso denegado");
                exit();
            }

            $id_noticia = intval($_POST['id_noticia']); // Asegurar que sea un número válido

            // Validar que el ID de la noticia es correcto
            if ($id_noticia <= 0) {
                header("Location: ../views/admin.php?error=ID de noticia inválido");
                exit();
            }

            // Intentar eliminar la noticia
            if ($this->noticiaModel->eliminarNoticia($id_noticia)) {
                header("Location: ../views/admin.php?mensaje=Noticia eliminada correctamente");
                exit();
            } else {
                header("Location: ../views/admin.php?error=No se pudo eliminar la noticia");
                exit();
            }
        }
    }
}

// Instanciar el controlador
$noticiaController = new NoticiaController();

// Ejecutar acciones si se accede directamente a este script
if (isset($_POST['accion'])) {
    if ($_POST['accion'] === 'agregar') {
        $noticiaController->agregarNoticia();
    } elseif ($_POST['accion'] === 'eliminar') {
        $noticiaController->eliminarNoticia();
    }
}
?>
