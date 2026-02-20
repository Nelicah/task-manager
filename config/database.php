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
    $this->host          = getenv('MYSQL_HOST')     ?: 'localhost';
    $this->port          = getenv('MYSQL_PORT')     ?: '3307';
    $this->database_name = getenv('MYSQL_DATABASE') ?: 'task_manager';
    $this->username      = getenv('MYSQL_USERNAME') ?: 'root';
    $this->password      = getenv('MYSQL_PASSWORD') ?: '';
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
