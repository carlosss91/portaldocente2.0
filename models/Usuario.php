<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    // Obtener todos los usuarios (ahora incluyendo fecha_creacion)
    public function obtenerUsuarios() {
        $sql = "
            SELECT 
                id_usuario,
                nombre,
                apellido,
                dni,
                email,
                disponibilidad,
                isla,
                puntuacion,
                fecha_creacion
            FROM usuario
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nuevo: Obtener usuarios ordenados por un campo y orden dados
    public function obtenerUsuariosOrdenados($campo, $orden) {
        // Lista blanca de campos y orden
        $allowedFields = ['nombre', 'puntuacion', 'fecha_creacion'];
        $allowedOrder  = ['asc', 'desc'];

        // Validar campo
        if (!in_array($campo, $allowedFields)) {
            $campo = 'nombre';
        }
        // Validar orden
        $orden = strtolower($orden);
        if (!in_array($orden, $allowedOrder)) {
            $orden = 'asc';
        }

        $sql = "
            SELECT 
                id_usuario,
                nombre,
                apellido,
                dni,
                email,
                disponibilidad,
                isla,
                puntuacion,
                fecha_creacion
            FROM usuario
            ORDER BY {$campo} {$orden}
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por su ID con todos los datos relevantes
    public function obtenerUsuarioPorId($id) {
        $sql = "
            SELECT 
                id_usuario,
                nombre,
                apellido,
                email,
                telefono,
                disponibilidad,
                isla,
                puntuacion,
                password 
            FROM usuario 
            WHERE id_usuario = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Calcular la posición de un usuario en la lista de puntuaciones
    public function obtenerPuestoEnLista($id_usuario) {
        $sql = "SELECT id_usuario FROM usuario ORDER BY puntuacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($usuarios as $index => $usuario) {
            if ($usuario['id_usuario'] == $id_usuario) {
                return $index + 1;
            }
        }
    
        return "No encontrado";
    }

    // Actualizar datos del usuario (excepto la contraseña)
    public function actualizarUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password = null) {
        if ($password) {
            $sql = "
                UPDATE usuario 
                SET nombre = ?, apellido = ?, email = ?, telefono = ?, disponibilidad = ?, isla = ?, password = ? 
                WHERE id_usuario = ?
            ";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $nombre, $apellido, $email, $telefono, 
                $disponibilidad, $isla, $password, $id_usuario
            ]);
        } else {
            $sql = "
                UPDATE usuario 
                SET nombre = ?, apellido = ?, email = ?, telefono = ?, disponibilidad = ?, isla = ? 
                WHERE id_usuario = ?
            ";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $nombre, $apellido, $email, $telefono, 
                $disponibilidad, $isla, $id_usuario
            ]);
        }
    }

    // Crear un nuevo usuario
    public function crearUsuario($nombre, $apellido, $dni, $email, $telefono, $password, $rol, $isla, $disponibilidad, $puntuacion) {
        $roles_validos = ['docente', 'administrador'];
        if (!in_array($rol, $roles_validos)) {
            $rol = 'docente';
        }
    
        $sql = "
            INSERT INTO usuario 
                (nombre, apellido, dni, email, telefono, password, rol, isla, disponibilidad, puntuacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $nombre, $apellido, $dni, $email, 
            $telefono, $password, $rol, $isla, 
            $disponibilidad, $puntuacion
        ]);
    }

    // Eliminar un usuario por su ID
    public function eliminarUsuario($id_usuario) {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }
}
?>
