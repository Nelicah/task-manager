<?php
require_once 'api/config.php';

echo "<h1>Setup Database</h1>";

try {
  $conn = conectarDB();
  echo "<p>✅ Conexión exitosa</p>";

  $sql = "CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        priority ENUM('baja', 'media', 'alta') NOT NULL DEFAULT 'media',
        due_date DATE NULL,
        completed TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_priority (priority),
        INDEX idx_completed (completed),
        INDEX idx_due_date (due_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

  $conn->exec($sql);
  echo "<p>✅ Tabla 'tasks' creada correctamente</p>";

  // Añadir columna usuario_id si no existe (error 1060 = columna duplicada, se ignora)
  try {
    $conn->exec("ALTER TABLE tasks ADD COLUMN usuario_id INT NOT NULL DEFAULT 0 AFTER id");
    echo "<p>✅ Columna 'usuario_id' añadida a 'tasks'</p>";
  } catch (PDOException $e) {
    if ($e->errorInfo[1] == 1060) {
      echo "<p>ℹ️ Columna 'usuario_id' ya existe en 'tasks'</p>";
    } else {
      throw $e;
    }
  }

  $sql2 = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

  $conn->exec($sql2);
  echo "<p>✅ Tabla 'usuarios' creada correctamente</p>";

  echo "<p><strong>Base de datos configurada correctamente!</strong></p>";
  echo "<p><a href='index.php'>Ir a la aplicación →</a></p>";
} catch (PDOException $e) {
  echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
