<?php
require_once '../models/Noticia.php'; // Incluir el modelo de Noticia

class NoticiaController {
    private $noticiaModel;

    public function __construct() {
        $this->noticiaModel = new Noticia(); // Instancia el modelo de Noticia
    }

    public function mostrarNoticias() {
        return $this->noticiaModel->obtenerNoticias();
    }
}

// Instancia del controlador
$noticiaController = new NoticiaController();
?>
