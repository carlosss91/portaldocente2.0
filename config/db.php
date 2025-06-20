<?php
class Database {
    // Parámetros de conexión
    private static $host     = "localhost";
    private static $dbname   = "portaldocente";
    private static $usuario  = "root";
    private static $password = "";
    private static $pdo      = null;

    /**
     * Devuelve una instancia PDO singleton
     * - charset utf8mb4 para soportar todos los caracteres
     * - ERRMODE_EXCEPTION para lanzar excepciones en errores
     * - DEFAULT_FETCH_MODE en FETCH_ASSOC por defecto
     * - EMULATE_PREPARES en false para usar prepared statements nativos
     */
    public static function conectar() {
        if (self::$pdo === null) {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                self::$host,
                self::$dbname
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                // PDO::ATTR_PERSISTENT       => true, // opcional si quieres persistencia
            ];

            try {
                self::$pdo = new PDO(
                    $dsn,
                    self::$usuario,
                    self::$password,
                    $options
                );
            } catch (PDOException $e) {
                // En desarrollo, devolvemos 500 y mensaje
                http_response_code(500);
                echo "Error de conexión a la base de datos: " . $e->getMessage();
                exit();
            }
        }

        return self::$pdo;
    }
}
