<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Variables de entorno de Zeabur (o valores locales para desarrollo)
define('DB_HOST', getenv('MYSQL_HOST') ?: 'localhost');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'task_manager');
define('DB_USER', getenv('MYSQL_USERNAME') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: '');
define('DB_PORT', getenv('MYSQL_PORT') ?: '3307');

function conectarDB()
{
    try {
        $dsn = "mysql:host=" . DB_HOST;
        if (DB_PORT) {
            $dsn .= ";port=" . DB_PORT;
        }
        $dsn .= ";dbname=" . DB_NAME . ";charset=utf8mb4";

        $conexion = new PDO($dsn, DB_USER, DB_PASS);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error de conexión a la base de datos',
            'details' => $e->getMessage() // Para debug, quita esto en producción
        ]);
        exit;
    }
}
