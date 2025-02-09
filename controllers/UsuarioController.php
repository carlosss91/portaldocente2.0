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

    // Método público para acceder al modelo de usuario
    public function getUsuarioModel() {
        return $this->usuarioModel;
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

    // Crea un nuevo usuario en la base de datos
    public function crearUsuario($nombre, $apellido, $dni, $email, $telefono, $password, $rol, $isla, $disponibilidad, $puntuacion) {
        return $this->usuarioModel->crearUsuario($nombre, $apellido, $dni, $email, $telefono, $password, $rol, $isla, $disponibilidad, $puntuacion);
    }
}

// Verifica si la sesión está activa y contiene el ID de usuario
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit();
}

$usuarioController = new UsuarioController();

// Verifica si la solicitud es POST y si se ha enviado una acción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST["action"]) ? $_POST["action"] : "";

    if ($action === "crear") {
        // Crear un nuevo usuario
        $nombre = trim($_POST["nombre"]);
        $apellido = trim($_POST["apellido"]);
        $dni = trim($_POST["dni"]); // Se agregó DNI
        $email = trim($_POST["email"]);
        $telefono = trim($_POST["telefono"]);
        $password = trim($_POST["password"]);
        $rol = trim($_POST["rol"]);
        $isla = trim($_POST["isla"]);
        $disponibilidad = isset($_POST["disponibilidad"]) ? intval($_POST["disponibilidad"]) : 1;
        $puntuacion = isset($_POST["puntuacion"]) ? floatval($_POST["puntuacion"]) : 0.0;

        // Llamar al método para crear un nuevo usuario
        $usuarioController->crearUsuario($nombre, $apellido, $dni, $email, $telefono, $password, $rol, $isla, $disponibilidad, $puntuacion);

        // Redirigir de vuelta al panel de administración
        header("Location: ../views/admin.php?success=usuario_creado");
        exit();
    } elseif ($action === "eliminar") {
        
        // Verificar si el ID del usuario a eliminar fue enviado
        if (isset($_POST["id_usuario"]) && !empty($_POST["id_usuario"])) {
            $id_usuario = intval($_POST["id_usuario"]);

            // Obtener los datos del usuario a eliminar
            $usuarioAEliminar = $usuarioController->getUsuarioModel()->obtenerUsuarioPorId($id_usuario);

            // Verificar si el usuario existe antes de eliminarlo
            if (!$usuarioAEliminar) {
                header("Location: ../views/admin.php?error=usuario_no_existe");
                exit();
            }

            // Evitar que un administrador elimine a otro administrador o a sí mismo
            if ($usuarioAEliminar["rol"] === "administrador") {
                header("Location: ../views/admin.php?error=no_puedes_borrar_admin");
                exit();
            }

            // Llamar a la función para eliminar el usuario
            $usuarioController->getUsuarioModel()->eliminarUsuario($id_usuario);

            // Redirigir con éxito
            header("Location: ../views/admin.php?success=usuario_eliminado");
            exit();
        } else {
            header("Location: ../views/admin.php?error=id_usuario_no_valido");
            exit();
        }
    } else {
        // Editar usuario existente
        $id_usuario = $_SESSION["id_usuario"];
        $nombre = trim($_POST["nombre"]);
        $apellido = trim($_POST["apellido"]);
        $email = trim($_POST["email"]);
        $telefono = isset($_POST["telefono"]) ? trim($_POST["telefono"]) : null;
        $disponibilidad = isset($_POST["disponibilidad"]) ? intval($_POST["disponibilidad"]) : 1;
        $isla = isset($_POST["isla"]) ? trim($_POST["isla"]) : null;
        $password = isset($_POST["password"]) && !empty($_POST["password"]) ? trim($_POST["password"]) : null;

        // Llama al método para actualizar el usuario
        $usuarioController->actualizarUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password);

        // Redirigir al perfil del usuario después de la actualización
        header("Location: ../views/usuario.php?success=actualizado");
        exit();
    }
}

// Obtiene las adjudicaciones del usuario para mostrar en la vista
$adjudicacionModel = new Adjudicacion();
$adjudicaciones = $adjudicacionModel->obtenerAdjudicaciones($_SESSION["id_usuario"]);
