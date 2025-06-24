<?php
// ╔═════════════════════════════════════════╗
// ║      MODELO USUARIO (Usuario.php)       ║
// ╚═════════════════════════════════════════╝
require_once '../config/db.php'; // Conexión a la base de datos

class Usuario {
    private $db;

    // ╔═════════════════════════════════════════╗
    // ║     CONSTRUCTOR Y CONEXIÓN BD          ║
    // ╚═════════════════════════════════════════╝
    public function __construct() {
        $this->db = Database::conectar();
        // Forzar excepciones PDO para capturar errores en SQL
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // ╔═════════════════════════════════════════╗
    // ║   OBTENER TODOS LOS USUARIOS           ║
    // ╚═════════════════════════════════════════╝
    public function obtenerUsuarios() {
        $sql = "
            SELECT
                id_usuario,
                nombre,
                apellido,
                dni,              -- <=== incluimos DNI
                email,
                telefono,         -- <=== incluimos Teléfono
                rol,              -- <=== incluimos Rol
                disponibilidad,
                isla,
                puntuacion,       -- <=== incluimos Puntuación
                fecha_creacion
            FROM usuario
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ╔═════════════════════════════════════════╗
    // ║   OBTENER USUARIOS ORDENADOS           ║
    // ╚═════════════════════════════════════════╝
    public function obtenerUsuariosOrdenados($campo, $orden) {
        $allowedFields = ['nombre', 'puntuacion', 'fecha_creacion'];
        $allowedOrder  = ['asc', 'desc'];

        if (!in_array($campo, $allowedFields)) {
            $campo = 'nombre';
        }
        $orden = strtolower($orden);
        if (!in_array($orden, $allowedOrder)) {
            $orden = 'asc';
        }

        $sql = "
            SELECT
                id_usuario,
                nombre,
                apellido,
                dni,              -- <=== incluimos DNI
                email,
                telefono,         -- <=== incluimos Teléfono
                rol,              -- <=== incluimos Rol
                disponibilidad,
                isla,
                puntuacion,       -- <=== incluimos Puntuación
                fecha_creacion
            FROM usuario
            ORDER BY {$campo} {$orden}
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ╔═════════════════════════════════════════╗
    // ║   OBTENER USUARIO POR ID                ║
    // ╚═════════════════════════════════════════╝
    public function obtenerUsuarioPorId($id) {
        $sql = "
            SELECT
                id_usuario,
                nombre,
                apellido,
                dni,              -- <=== incluimos DNI
                email,
                telefono,
                rol,              -- <=== incluimos Rol
                disponibilidad,
                isla,
                puntuacion,
                password
            FROM usuario
            WHERE id_usuario = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ╔═════════════════════════════════════════╗
    // ║   CALCULAR PUESTO EN LISTA             ║
    // ╚═════════════════════════════════════════╝
    // Calcular el puesto en cada isla
    public function obtenerPuestoEnLista($id_usuario) {
        $sql = "SELECT id_usuario FROM usuario ORDER BY puntuacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $puesto = array_search($id_usuario, $usuarios);
        return ($puesto !== false) ? $puesto + 1 : 'No encontrado';
    }
    // ╔═════════════════════════════════════════╗
    // ║   OBTENER PUESTO EN ISLA                ║
    // ╚═════════════════════════════════════════╝
    public function obtenerPuestoEnIsla($id_usuario, $isla) {
    $sql = "SELECT id_usuario FROM usuario WHERE isla = ? AND disponibilidad = 1 ORDER BY puntuacion DESC, id_usuario ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$isla]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $puesto = array_search($id_usuario, $usuarios);
    return ($puesto !== false) ? $puesto + 1 : '-';
}

    // ╔═════════════════════════════════════════╗
    // ║   ACTUALIZAR USUARIO                   ║
    // ║   (incluye DNI, Rol y Puntuación)      ║
    // ╚═════════════════════════════════════════╝
    public function actualizarUsuario(
        $id_usuario,
        $nombre,
        $apellido,
        $dni,               // <=== nuevo parámetro
        $email,
        $telefono,
        $rol,               // <=== nuevo parámetro
        $disponibilidad,
        $isla,
        $puntuacion,        // <=== nuevo parámetro
        $password = null
    ) {
        if ($password) {
            $sql = "
                UPDATE usuario
                SET nombre = ?, apellido = ?, dni = ?, email = ?, telefono = ?,
                    rol = ?, disponibilidad = ?, isla = ?, puntuacion = ?, password = ?
                WHERE id_usuario = ?
            ";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $nombre,
                $apellido,
                $dni,
                $email,
                $telefono,
                $rol,
                $disponibilidad,
                $isla,
                $puntuacion,
                password_hash($password, PASSWORD_DEFAULT),
                $id_usuario
            ]);
        } else {
            $sql = "
                UPDATE usuario
                SET nombre = ?, apellido = ?, dni = ?, email = ?, telefono = ?,
                    rol = ?, disponibilidad = ?, isla = ?, puntuacion = ?
                WHERE id_usuario = ?
            ";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $nombre,
                $apellido,
                $dni,
                $email,
                $telefono,
                $rol,
                $disponibilidad,
                $isla,
                $puntuacion,
                $id_usuario
            ]);
        }
    }

    // ╔═════════════════════════════════════════╗
    // ║   CREAR NUEVO USUARIO                   ║
    // ╚═════════════════════════════════════════╝
    public function crearUsuario(
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
    ) {
        $roles_validos = ['docente', 'administrador'];
        if (!in_array($rol, $roles_validos)) {
            $rol = 'docente';
        }

        $sql = "
            INSERT INTO usuario
                (nombre, apellido, dni, email, telefono,
                 password, rol, isla, disponibilidad,
                 puntuacion, fecha_creacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $nombre,
            $apellido,
            $dni,
            $email,
            $telefono,
            password_hash($password, PASSWORD_DEFAULT),
            $rol,
            $isla,
            $disponibilidad,
            $puntuacion
        ]);
    }

    // ╔═════════════════════════════════════════╗
    // ║   ELIMINAR USUARIO                     ║
    // ╚═════════════════════════════════════════╝
    public function eliminarUsuario($id_usuario) {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }
}
?>
