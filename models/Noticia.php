<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos
require_once '../models/Usuario.php'; // Incluir el modelo de usuario

class Noticia {
    private $db;

    // ╔═════════════════════════════════════════╗
    // ║   CONSTRUCTOR Y CONEXIÓN A BD           ║
    // ╚═════════════════════════════════════════╝
    public function __construct() {
        $this->db = Database::conectar();
    }

    // ╔═════════════════════════════════════════╗
    // ║   AGREGAR NUEVA NOTICIA CON AUTOR       ║
    // ╚═════════════════════════════════════════╝
    public function agregarNoticia($titulo, $contenido, $imagen_url, $autor_id) {
        try {
            $sql = "INSERT INTO noticia (titulo, contenido, imagen_url, autor_id, fecha) 
                    VALUES (:titulo, :contenido, :imagen_url, :autor_id, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':titulo',     $titulo,     PDO::PARAM_STR);
            $stmt->bindParam(':contenido',  $contenido,  PDO::PARAM_STR);
            $stmt->bindParam(':imagen_url', $imagen_url, PDO::PARAM_STR);
            $stmt->bindParam(':autor_id',   $autor_id,   PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en agregarNoticia(): " . $e->getMessage());
            return false;
        }
    }

    // ╔═════════════════════════════════════════╗
    // ║   OBTENER TODAS LAS NOTICIAS            ║
    // ╚═════════════════════════════════════════╝
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
                ORDER BY n.fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ╔═════════════════════════════════════════╗
    // ║   OBTENER NOTICIA POR ID                ║
    // ╚═════════════════════════════════════════╝
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
        $stmt->bindParam(':id_noticia', $id_noticia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ╔═════════════════════════════════════════╗
    // ║   ACTUALIZAR NOTICIA EXISTENTE          ║
    // ╚═════════════════════════════════════════╝
    public function actualizarNoticia($id_noticia, $titulo, $contenido, $imagen_url) {
        try {
            $sql = "UPDATE noticia
                    SET titulo     = :titulo,
                        contenido  = :contenido,
                        imagen_url = :imagen_url
                    WHERE id_noticia = :id_noticia";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':titulo',      $titulo,      PDO::PARAM_STR);
            $stmt->bindParam(':contenido',   $contenido,   PDO::PARAM_STR);
            $stmt->bindParam(':imagen_url',  $imagen_url,  PDO::PARAM_STR);
            $stmt->bindParam(':id_noticia',  $id_noticia,  PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarNoticia(): " . $e->getMessage());
            return false;
        }
    }

    // ╔═════════════════════════════════════════╗
    // ║   ELIMINAR NOTICIA POR ID               ║
    // ╚═════════════════════════════════════════╝
    public function eliminarNoticia($id_noticia) {
        try {
            $sql = "DELETE FROM noticia WHERE id_noticia = :id_noticia";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_noticia', $id_noticia, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminarNoticia(): " . $e->getMessage());
            return false;
        }
    }
}
?>
