<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos

class Usuario {
    private $db;

    public function __construct() {
        // Establecer conexión con la base de datos
        $this->db = Database::conectar();
    }

    // Obtener todos los usuarios 
    public function obtenerUsuarios() {
        $sql = "SELECT id_usuario, nombre, apellido, dni, email, disponibilidad, isla, puntuacion FROM usuario"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Obtener un usuario por su ID con todos los datos relevantes
    public function obtenerUsuarioPorId($id) {
        $sql = "SELECT id_usuario, nombre, apellido, email, telefono, disponibilidad, isla, puntuacion, password 
                FROM usuario 
                WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Calcular la posición de un usuario en la lista de puntuaciones
    public function obtenerPuestoEnLista($id_usuario) {
        // Consulta para obtener los usuarios ordenados por puntuación DESCENDENTE
        $sql = "SELECT id_usuario FROM usuario ORDER BY puntuacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Buscar el puesto del usuario en la lista
        foreach ($usuarios as $index => $usuario) {
            if ($usuario['id_usuario'] == $id_usuario) {
                return $index + 1; // Retorna la posición en la lista (empezando desde 1)
            }
        }
    
        return "No encontrado"; // Si por alguna razón no se encuentra en la lista
    }
    

    // Actualizar datos del usuario (excepto la contraseña)
    public function actualizarUsuario($id_usuario, $nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password = null) {
        if ($password) {
            // Si el usuario ingresó una nueva contraseña, la actualiza
            $sql = "UPDATE usuario SET nombre = ?, apellido = ?, email = ?, telefono = ?, disponibilidad = ?, isla = ?, password = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $password, $id_usuario]);
        } else {
            // Si no ingresó una contraseña, actualiza solo los otros datos
            $sql = "UPDATE usuario SET nombre = ?, apellido = ?, email = ?, telefono = ?, disponibilidad = ?, isla = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $apellido, $email, $telefono, $disponibilidad, $isla, $id_usuario]);
        }
    }

    // Crear un nuevo usuario
    public function crearUsuario($nombre, $apellido, $dni, $email, $telefono, $password, $rol, $isla, $disponibilidad, $puntuacion) {
        // Verificar que el rol sea válido antes de insertarlo
        $roles_validos = ['docente', 'administrador'];
        if (!in_array($rol, $roles_validos)) {
            $rol = 'docente'; // Valor por defecto si el rol no es válido
        }
    
        $sql = "INSERT INTO usuario (nombre, apellido, dni, email, telefono, password, rol, isla, disponibilidad, puntuacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $apellido, $dni, $email, $telefono, $password, $rol, $isla, $disponibilidad, $puntuacion]);
    }

    // Eliminar un usuario por su ID
    public function eliminarUsuario($id_usuario) {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }
  
}
?>
