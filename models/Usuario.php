<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos

class Usuario {
    private $db;

    public function __construct() {
        // Establecer conexión con la base de datos
        $this->db = Database::conectar();
    }

    // Obtener todos los usuarios (solo datos esenciales)
    public function obtenerUsuarios() {
        $sql = "SELECT id_usuario, nombre, apellido, email FROM usuario";
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
    
}
?>
