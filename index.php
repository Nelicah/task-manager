<?php
// Si piden el root, mostrar index.html
if ($_SERVER['REQUEST_URI'] === '/') {
  readfile('index.html');
  exit;
}

// Para otros archivos, dejar que PHP los maneje
return false;
