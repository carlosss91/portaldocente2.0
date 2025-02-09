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

    // Obtener todas las adjudicaciones
    public function obtenerTodasAdjudicaciones() {
        $sql = "SELECT 
                    a.id_adjudicacion, 
                    a.id_usuario, 
                    u.nombre AS nombre_usuario, 
                    u.apellido AS apellido_usuario, 
                    a.isla, 
                    a.municipio, 
                    a.fecha_adjudicacion 
                FROM adjudicacion a
                JOIN usuario u ON a.id_usuario = u.id_usuario
                ORDER BY a.fecha_adjudicacion DESC"; // Ordenado por fecha más reciente
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    

    // Agregar una nueva adjudicación con isla y municipio
    public function agregarAdjudicacion($id_usuario, $isla, $municipio) {
        $sql = "INSERT INTO adjudicacion (id_usuario, isla, municipio, fecha_adjudicacion) 
                VALUES (:id_usuario, :isla, :municipio, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":isla", $isla, PDO::PARAM_STR);
        $stmt->bindParam(":municipio", $municipio, PDO::PARAM_STR);
        return $stmt->execute();
    }


    // Eliminar adjudicación por ID
    public function eliminarAdjudicacion($id_adjudicacion) {
        $sql = "DELETE FROM adjudicacion WHERE id_adjudicacion = :id_adjudicacion";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_adjudicacion", $id_adjudicacion, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
?>
