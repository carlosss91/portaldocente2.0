<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos

class Solicitud {
    private $db;

    public function __construct() {
        // Establecer conexión con la base de datos
        $this->db = Database::conectar();
    }

    // Obtener todas las solicitudes de un usuario
    public function obtenerSolicitudes($id_usuario) {
        $stmt = $this->db->prepare("SELECT * FROM solicitud WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar una nueva solicitud
    public function agregarSolicitud($id_usuario, $tipo_solicitud, $detalles_destino) {
        $sql = "INSERT INTO solicitud (id_usuario, tipo_solicitud, detalles_destino_solicitado) 
                VALUES (:id_usuario, :tipo_solicitud, :detalles_destino)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":tipo_solicitud", $tipo_solicitud, PDO::PARAM_STR);
        $stmt->bindParam(":detalles_destino", $detalles_destino, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Eliminar una solicitud por ID
    public function eliminarSolicitud($id_solicitud) {
        $stmt = $this->db->prepare("DELETE FROM solicitud WHERE id_solicitud = ?");
        return $stmt->execute([$id_solicitud]);
    }
}
