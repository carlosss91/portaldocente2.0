<?php
require_once '../config/db.php'; // Asegurar que se incluye correctamente la conexión

class Adjudicacion {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    // Obtener todas las adjudicaciones del usuario actual
    public function obtenerAdjudicaciones($id_usuario) {
        $stmt = $this->db->prepare("SELECT * FROM adjudicacion WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar una nueva adjudicación
    public function agregarAdjudicacion($id_usuario, $destino_actual) {
        $stmt = $this->db->prepare("INSERT INTO adjudicacion (id_usuario, destino_actual) VALUES (?, ?)");
        return $stmt->execute([$id_usuario, $destino_actual]);
    }

    // Modificar una adjudicación existente
    public function modificarAdjudicacion($id_adjudicacion, $nuevo_destino) {
        $stmt = $this->db->prepare("UPDATE adjudicacion SET destino_actual = ? WHERE id_adjudicacion = ?");
        return $stmt->execute([$nuevo_destino, $id_adjudicacion]);
    }

    // Eliminar adjudicación
    public function eliminarAdjudicacion($id_adjudicacion) {
        $stmt = $this->db->prepare("DELETE FROM adjudicacion WHERE id_adjudicacion = ?");
        return $stmt->execute([$id_adjudicacion]);
    }
}
?>
