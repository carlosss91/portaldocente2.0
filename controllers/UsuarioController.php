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

    // Devuelve la instancia del modelo
    public function getUsuarioModel() {
        return $this->usuarioModel;
    }

    // Obtiene los datos del usuario autenticado
    public function obtenerUsuarioSesion() {
        if (!isset($_SESSION['id_usuario'])) {
            return null;
        }
        return $this->usuarioModel->obtenerUsuarioPorId($_SESSION['id_usuario']);
    }

    // Actualiza los datos del usuario en la base de datos
    public function actualizarUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password = null) {
        return $this->usuarioModel->actualizarUsuario(
            $id_usuario,
            $nombre,
            $apellido,
            $email,
            $telefono,
            $disponibilidad,
            $isla,
            $password
        );
    }

    // Crea un nuevo usuario en la base de datos
    public function crearUsuario($nombre, $apellido, $dni, $email, $telefono, $password, $rol, $isla, $disponibilidad, $puntuacion) {
        return $this->usuarioModel->crearUsuario(
            $nombre,
            $apellido,
            $dni,
            $email,
            $telefono,
            $password,
            $rol,
            $isla,
            $disponibilidad,
            $puntuacion
        );
    }

    // Lista usuarios, permitiendo ordenarlos por un campo y orden dados
    public function listarUsuarios($sort = null, $order = 'asc') {
        $allowed = ['nombre', 'puntuacion', 'fecha_creacion'];
        if (in_array($sort, $allowed)) {
            $order = strtolower($order) === 'desc' ? 'desc' : 'asc';
            return $this->usuarioModel->obtenerUsuariosOrdenados($sort, $order);
        }
        // Fallback: sin orden
        return $this->usuarioModel->obtenerUsuarios();
    }
}

// Redirige al login si no hay sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$usuarioController = new UsuarioController();

// Manejo de formularios (crear, eliminar, editar)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    if ($action === "crear") {
        // Crear usuario
        $nombre        = trim($_POST["nombre"]);
        $apellido      = trim($_POST["apellido"]);
        $dni           = trim($_POST["dni"]);
        $email         = trim($_POST["email"]);
        $telefono      = trim($_POST["telefono"]);
        $password      = trim($_POST["password"]);
        $rol           = trim($_POST["rol"]);
        $isla          = trim($_POST["isla"]);
        $disponibilidad= isset($_POST["disponibilidad"]) ? intval($_POST["disponibilidad"]) : 1;
        $puntuacion    = isset($_POST["puntuacion"])     ? floatval($_POST["puntuacion"])    : 0.0;

        $usuarioController->crearUsuario(
            $nombre, $apellido, $dni, $email, $telefono,
            $password, $rol, $isla, $disponibilidad, $puntuacion
        );

        header("Location: ../views/admin.php?success=usuario_creado");
        exit();

    } elseif ($action === "eliminar") {
        // Eliminar usuario
        if (!empty($_POST["id_usuario"])) {
            $id_usuario = intval($_POST["id_usuario"]);
            $u = $usuarioController->getUsuarioModel()->obtenerUsuarioPorId($id_usuario);

            if (!$u) {
                header("Location: ../views/admin.php?error=usuario_no_existe");
                exit();
            }
            if ($u["rol"] === "administrador") {
                header("Location: ../views/admin.php?error=no_puedes_borrar_admin");
                exit();
            }

            $usuarioController->getUsuarioModel()->eliminarUsuario($id_usuario);
            header("Location: ../views/admin.php?success=usuario_eliminado");
            exit();
        }
        header("Location: ../views/admin.php?error=id_usuario_no_valido");
        exit();

    } else {
        // Editar perfil de usuario
        $id_usuario    = $_SESSION["id_usuario"];
        $nombre        = trim($_POST["nombre"]);
        $apellido      = trim($_POST["apellido"]);
        $email         = trim($_POST["email"]);
        $telefono      = $_POST["telefono"]        ?? null;
        $disponibilidad= isset($_POST["disponibilidad"]) ? intval($_POST["disponibilidad"]) : 1;
        $isla          = $_POST["isla"]            ?? null;
        $password      = !empty($_POST["password"]) ? trim($_POST["password"])  : null;

        $usuarioController->actualizarUsuario(
            $id_usuario, $nombre, $apellido, $email,
            $telefono, $disponibilidad, $isla, $password
        );

        header("Location: ../views/usuario.php?success=actualizado");
        exit();
    }
}

// Si no es POST, preparamos datos para la vista admin
$sort  = $_GET['sort']  ?? null;
$order = $_GET['order'] ?? 'asc';
$usuarios = $usuarioController->listarUsuarios($sort, $order);

// Para la vista de perfil: adjudicaciones del usuario
$adjudicacionModel = new Adjudicacion();
$adjudicaciones    = $adjudicacionModel->obtenerAdjudicaciones($_SESSION["id_usuario"]);
