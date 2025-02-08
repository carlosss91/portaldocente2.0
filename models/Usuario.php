<?php
require_once '../config/db.php'; // 📌 Usamos db.php en lugar de database.php

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::conectar(); // 📌 Nos aseguramos de que usa la clase Database
    }

    // Obtener todos los usuarios
    public function obtenerUsuarios() {
        $sql = "SELECT id_usuario, nombre, apellido, email FROM usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por su ID
    public function obtenerUsuarioPorId($id) {
        $sql = "SELECT id_usuario, nombre, apellido, email FROM usuario WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
