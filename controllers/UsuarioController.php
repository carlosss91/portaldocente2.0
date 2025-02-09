<?php
session_start();
require_once '../models/Usuario.php';
require_once '../models/Adjudicacion.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        // Inicializa el modelo de usuario para interactuar con la base de datos
        $this->usuarioModel = new Usuario();
    }

    // Obtiene los datos del usuario autenticado
    public function obtenerUsuarioSesion() {
        // Verifica si la sesión del usuario está activa
        if (!isset($_SESSION['id_usuario'])) {
            return null; // Retorna null si el usuario no está autenticado
        }
        
        // Retorna los datos del usuario desde la base de datos
        return $this->usuarioModel->obtenerUsuarioPorId($_SESSION['id_usuario']);
    }

    // Actualiza los datos del usuario en la base de datos
    public function actualizarUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password = null) {
        return $this->usuarioModel->actualizarUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password);
    }
}

// Verifica que la sesión esté activa y contiene el ID de usuario
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit();
}

// Si se recibe una solicitud de actualización de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["email"])) {
    $usuarioController = new UsuarioController();
    
    $id_usuario = $_SESSION["id_usuario"];
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email = trim($_POST["email"]);
    $telefono = isset($_POST["telefono"]) ? trim($_POST["telefono"]) : null;
    $disponibilidad = isset($_POST["disponibilidad"]) ? intval($_POST["disponibilidad"]) : 1;
    $isla = isset($_POST["isla"]) ? trim($_POST["isla"]) : null;
    
    // Verifica si el usuario ha cambiado la contraseña
    $password = isset($_POST["password"]) && !empty($_POST["password"]) ? trim($_POST["password"]) : null;

    
    // Llama al método para actualizar el usuario
    $usuarioController->actualizarUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password);
    
    // Redirige de vuelta a la página de perfil con un mensaje de éxito
    header("Location: ../views/usuario.php?success=1");
    exit();
}

// Obtiene las adjudicaciones del usuario para mostrar en la vista
$adjudicacionModel = new Adjudicacion();
$adjudicaciones = $adjudicacionModel->obtenerAdjudicaciones($_SESSION["id_usuario"]);



?>
