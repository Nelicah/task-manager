<?php
// Clase para gestionar usuarios y autenticación

class Usuario
{
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Registrar nuevo usuario
    public function registrar()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, email, password) 
                  VALUES (:nombre, :email, :password)";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Hash de contraseña
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind de parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login de usuario
    public function login()
    {
        $query = "SELECT id, nombre, email, password 
                  FROM " . $this->table_name . " 
                  WHERE email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);

        // Limpiar email
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar contraseña
            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->nombre = $row['nombre'];
                return true;
            }
        }
        return false;
    }

    // Verificar si email ya existe
    public function emailExiste()
    {
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
