<?php
require_once 'includes/auth_check.php';

// Si piden el root, mostrar index.html
if ($_SERVER['REQUEST_URI'] === '/' || str_ends_with($_SERVER['REQUEST_URI'], 'index.php') || str_ends_with($_SERVER['REQUEST_URI'], '/')) {
  $html = file_get_contents('index.html');

  $userHeader = '<div class="user-header">
    <div class="user-info">
      <span>👤</span>
      <span class="user-name">Bienvenido/a, ' . htmlspecialchars($_SESSION['usuario_nombre']) . '</span>
    </div>
    <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
  </div>';

  $html = str_replace('<body>', '<body>' . $userHeader, $html);

  echo $html;
  exit;
}

// Para otros archivos, dejar que PHP los maneje
return false;
