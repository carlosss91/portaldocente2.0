<?php
class Database {
    private static $host = "localhost";
    private static $dbname = "portal_docente";
    private static $usuario = "root"; // Usuario
    private static $password = "root"; // Contraseña
    private static $pdo = null;

    public static function conectar() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$usuario, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

