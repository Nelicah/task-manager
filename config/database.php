<?php
// Configuración de conexión a base de datos

class Database
{
  private $host;
  private $port;
  private $database_name;
  private $username;
  private $password;
  public $conn;

  public function __construct()
  {
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
      $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$name, $value] = explode('=', $line, 2);
        if (!getenv(trim($name))) putenv(trim($name) . '=' . trim($value));
      }
    }

    $this->host          = getenv('DB_HOST')     ?: 'localhost';
    $this->port          = getenv('DB_PORT')     ?: '3306';
    $this->database_name = getenv('DB_NAME')     ?: 'task_manager';
    $this->username      = getenv('DB_USER')     ?: 'root';
    $this->password      = getenv('DB_PASS')     ?: '';
  }

  public function getConnection()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO(
        "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->database_name,
        $this->username,
        $this->password
      );
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->exec("set names utf8");
    } catch (PDOException $exception) {
      echo "Error de conexión: " . $exception->getMessage();
    }

    return $this->conn;
  }
}
