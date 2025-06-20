<?php
// ╔═════════════════════════════════════════╗
// ║   INICIO DE SESIÓN Y REQUERIMIENTOS     ║
// ╚═════════════════════════════════════════╝
session_start();
require_once '../models/Usuario.php';
require_once '../models/Adjudicacion.php';

// ╔═════════════════════════════════════════╗
// ║       DEFINICIÓN DEL CONTROLADOR       ║
// ╚═════════════════════════════════════════╝
class UsuarioController {
    private $usuarioModel;

    // ╔════════════════╗
    // ║   CONSTRUCTOR  ║
    // ╚════════════════╝
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // ╔════════════════════════╗
    // ║   OBTENER MODELO USU   ║
    // ╚════════════════════════╝
    public function getUsuarioModel() {
        return $this->usuarioModel;
    }

    // ╔════════════════════════════════════════════════════════════════╗
    // ║   CREAR USUARIO (inserta nuevo registro en la base de datos)  ║
    // ╚════════════════════════════════════════════════════════════════╝
    public function crearUsuario(
        $nombre, $apellido, $dni, $email,
        $telefono, $password, $rol,
        $isla, $disponibilidad, $puntuacion
    ) {
        return $this->usuarioModel->crearUsuario(
            $nombre, $apellido, $dni, $email,
            $telefono, $password, $rol,
            $isla, $disponibilidad, $puntuacion
        );
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║   ACTUALIZAR USUARIO (edita registro existente)         ║
    // ╚══════════════════════════════════════════════════════════╝
    public function actualizarUsuario(
        $id_usuario, $nombre, $apellido,
        $dni, $email, $telefono,
        $rol, $disponibilidad, $isla,
        $puntuacion,
        $password = null
    ) {
        return $this->usuarioModel->actualizarUsuario(
            $id_usuario,
            $nombre,
            $apellido,
            $dni,
            $email,
            $telefono,
            $rol,
            $disponibilidad,
            $isla,
            $puntuacion,
            $password
        );
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║   LISTAR USUARIOS (con opción de orden)                 ║
    // ╚══════════════════════════════════════════════════════════╝
    public function listarUsuarios($sort = null, $order = 'asc') {
        $allowed = ['nombre','puntuacion','fecha_creacion'];
        if (in_array($sort,$allowed)) {
            $order = strtolower($order)==='desc'?'desc':'asc';
            return $this->usuarioModel->obtenerUsuariosOrdenados($sort,$order);
        }
        return $this->usuarioModel->obtenerUsuarios();
    }
}

// ╔═════════════════════════════════════════╗
// ║       INSTANCIAR EL CONTROLADOR        ║
// ╚═════════════════════════════════════════╝
$usuarioController = new UsuarioController();

// ╔═════════════════════════════════════════╗
// ║     MANEJO DE PETICIONES POST          ║
// ╚═════════════════════════════════════════╝
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    // ╔════════════════════════════╗
    // ║   ACCIÓN: CREAR USUARIO    ║
    // ╚════════════════════════════╝
    if ($action === "crear") {
        // Recogemos datos del formulario
        $nombre         = trim($_POST["nombre"]);
        $apellido       = trim($_POST["apellido"]);
        $dni            = trim($_POST["dni"]);
        $email          = trim($_POST["email"]);
        $telefono       = trim($_POST["telefono"]);
        $password       = trim($_POST["password"]);
        $rol            = trim($_POST["rol"]);
        $isla           = trim($_POST["isla"]);
        $disponibilidad = intval($_POST["disponibilidad"] ?? 1);
        $puntuacion     = floatval($_POST["puntuacion"] ?? 0);

        try {
            $usuarioController->crearUsuario(
                $nombre, $apellido, $dni, $email,
                $telefono, $password, $rol,
                $isla, $disponibilidad, $puntuacion
            );
            header("Location: ../views/admin.php?success=usuario_creado");
            exit();
        } catch (PDOException $e) {
            // 23000 = clave duplicada (dni o email)
            if ($e->getCode() === '23000') {
                header("Location: ../views/admin.php?error=usuario_duplicado");
                exit();
            }
            throw $e;
        }
    }

    // ╔════════════════════════════╗
    // ║   ACCIÓN: ELIMINAR USUARIO ║
    // ╚════════════════════════════╝
    if ($action === "eliminar") {
        // Recuperar ID de usuario
        $id = intval($_POST["id_usuario"] ?? 0);
        try {
            $usuarioController->getUsuarioModel()->eliminarUsuario($id);
            header("Location: ../views/admin.php?success=usuario_eliminado");
            exit();
        } catch (PDOException $e) {
            header("Location: ../views/admin.php?error=eliminar_error");
            exit();
        }
    }

// ╔════════════════════════════╗
// ║   ACCIÓN: EDITAR USUARIO   ║
// ╚════════════════════════════╝
if ($action === "editar") {
    // DEBUG: Para ver qué campos llegan
    error_log("POST en editar: ".print_r($_POST, true));
    try {
        // Recoger campos
        $id_usuario     = intval($_POST["id_usuario"]);
        $nombre         = trim($_POST["nombre"]);
        $apellido       = trim($_POST["apellido"]);
        $dni            = trim($_POST["dni"]);
        $email          = trim($_POST["email"]);
        $telefono       = trim($_POST["telefono"] ?? '');
        $rol            = trim($_POST["rol"]);
        $disponibilidad = intval($_POST["disponibilidad"] ?? 1);
        $isla           = trim($_POST["isla"] ?? '');
        $puntuacion     = floatval($_POST["puntuacion"] ?? 0);

        // NUEVO: manejo de cambio de contraseña
        $passNueva    = trim($_POST["password"] ?? '');
        $passConfirm  = trim($_POST["password_confirm"] ?? '');
        if ($passNueva !== '' || $passConfirm !== '') {
            if ($passNueva !== $passConfirm) {
                header("Location: ../views/usuario.php?error=pass_no_coincide");
                exit();
            }
            // Si coinciden, dejamos $password con el texto (el modelo hará el hash)
            $password = $passNueva;
        } else {
            // No cambiar la clave
            $password = null;
        }

        // Llamada al modelo con el parámetro $password (raw si hay, o null)
        $usuarioController->actualizarUsuario(
            $id_usuario,
            $nombre,
            $apellido,
            $dni,
            $email,
            $telefono,
            $rol,
            $disponibilidad,
            $isla,
            $puntuacion,
            $password
        );
        header("Location: ../views/usuario.php?success=perfil_actualizado");
        exit();
    } catch (PDOException $e) {
        // Manejo de duplicados, etc.
        if ($e->getCode() === '23000') {
            header("Location: ../views/usuario.php?error=datos_duplicados");
            exit();
        }
        throw $e;
    }
}


    // ╔════════════════════════════╗
    // ║   ACCIÓN: DESCONOCIDA      ║
    // ╚════════════════════════════╝
    header("Location: ../views/admin.php?error=accion_no_valida");
    exit();
}
?>
