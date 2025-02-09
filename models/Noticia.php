<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos
require_once '../models/Usuario.php'; // Incluir el modelo de usuario

class Noticia {
    private $db;

    public function __construct() {
        // Establecer conexión con la base de datos
        $this->db = Database::conectar();
    }

    // Agregar una nueva noticia con autor
    public function agregarNoticia($titulo, $contenido, $imagen_url, $autor_id) {
        try {
            $sql = "INSERT INTO noticia (titulo, contenido, imagen_url, autor_id, fecha) 
                    VALUES (:titulo, :contenido, :imagen_url, :autor_id, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmt->bindParam(":contenido", $contenido, PDO::PARAM_STR);
            $stmt->bindParam(":imagen_url", $imagen_url, PDO::PARAM_STR);
            $stmt->bindParam(":autor_id", $autor_id, PDO::PARAM_INT);
    
            if (!$stmt->execute()) {
                echo "Error al ejecutar la consulta: ";
                print_r($stmt->errorInfo()); // Muestra el error de SQL si la consulta falla
                exit();
            }
    
            return true;
        } catch (PDOException $e) {
            echo "Error en agregarNoticia(): " . $e->getMessage();
            exit();
        }
    }
    

    // Obtener todas las noticias con información del autor
    public function obtenerNoticias() {
        $sql = "SELECT 
                    n.id_noticia, 
                    n.titulo, 
                    n.contenido, 
                    n.imagen_url, 
                    n.fecha, 
                    COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Desconocido') AS autor
                FROM noticia n
                LEFT JOIN usuario u ON n.autor_id = u.id_usuario
                ORDER BY n.fecha DESC"; // Ordenado por fecha más reciente
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una noticia específica por su ID
    public function obtenerNoticiaPorId($id_noticia) {
        $sql = "SELECT 
                    n.id_noticia, 
                    n.titulo, 
                    n.contenido, 
                    n.imagen_url, 
                    n.fecha, 
                    COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Desconocido') AS autor
                FROM noticia n
                LEFT JOIN usuario u ON n.autor_id = u.id_usuario
                WHERE n.id_noticia = :id_noticia";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_noticia", $id_noticia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar una noticia por ID
    public function eliminarNoticia($id_noticia) {
        $sql = "DELETE FROM noticia WHERE id_noticia = :id_noticia";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_noticia", $id_noticia, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
