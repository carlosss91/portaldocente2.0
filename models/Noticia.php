<?php
require_once '../config/db.php'; //  Conexión a la base de datos
require_once '../models/Usuario.php'; //  Incluir modelo de usuario

class Noticia {
    private $db;

    public function __construct() {
        $this->db = Database::conectar(); //  Conectamos a la base de datos
    }

    public function obtenerNoticias() {
        $sql = "SELECT n.id_noticia, n.titulo, n.contenido, n.imagen_url, n.fecha, 
                       COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Desconocido') AS autor
                FROM noticia n
                LEFT JOIN usuario u ON n.autor_id = u.id_usuario
                ORDER BY n.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
