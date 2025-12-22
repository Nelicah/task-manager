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

  echo "<p><strong>Base de datos configurada correctamente!</strong></p>";
  echo "<p><a href='index.html'>Ir a la aplicación →</a></p>";
} catch (PDOException $e) {
  echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
