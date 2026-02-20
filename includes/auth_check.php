<?php
// Middleware para proteger páginas que requieren autenticación

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // No está logueado, redirigir al login
    header("Location: login.php");
    exit();
}

// Si llega aquí, el usuario está autenticado
// Las variables de sesión están disponibles:
// $_SESSION['usuario_id']
// $_SESSION['usuario_nombre']
// $_SESSION['usuario_email']
