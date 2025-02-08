<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos

class Adjudicacion {
    private $db;

    public function __construct() {
        // Establecer conexión con la base de datos
        $this->db = Database::conectar();
    }

    // Obtener todas las adjudicaciones de un usuario
    public function obtenerAdjudicaciones($id_usuario) {
        $stmt = $this->db->prepare("SELECT * FROM adjudicacion WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar una nueva adjudicación con isla y municipio
    public function agregarAdjudicacion($id_usuario, $isla, $municipio) {
        $stmt = $this->db->prepare("INSERT INTO adjudicacion (id_usuario, isla, municipio) VALUES (?, ?, ?)");
        return $stmt->execute([$id_usuario, $isla, $municipio]);
    }

    // Modificar una adjudicación existente cambiando isla y municipio
    public function modificarAdjudicacion($id_adjudicacion, $nueva_isla, $nuevo_municipio) {
        $stmt = $this->db->prepare("UPDATE adjudicacion SET isla = ?, municipio = ? WHERE id_adjudicacion = ?");
        return $stmt->execute([$nueva_isla, $nuevo_municipio, $id_adjudicacion]);
    }

    // Eliminar adjudicación por ID
    public function eliminarAdjudicacion($id_adjudicacion) {
        $stmt = $this->db->prepare("DELETE FROM adjudicacion WHERE id_adjudicacion = ?");
        return $stmt->execute([$id_adjudicacion]);
    }
}
?>
