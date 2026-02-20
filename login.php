<?php
session_start();

require_once 'config/database.php';
require_once 'models/Usuario.php';

// Si ya está logueado, redirigir
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);

    $usuario->email = $_POST['email'];
    $usuario->password = $_POST['password'];

    if (empty($usuario->email) || empty($usuario->password)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($usuario->login()) {
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['usuario_nombre'] = $usuario->nombre;
            $_SESSION['usuario_email'] = $usuario->email;

            header("Location: index.php");
            exit();
        } else {
            $error = "Email o contraseña incorrectos";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/auth.css">
    <title>Login - Task Manager</title>
</head>

<body>
    <div class="auth-container">
        <h1>🔐 Iniciar Sesión</h1>

        <div class="demo-info">
            <strong>👤 Usuario Demo:</strong>
            Email: demo@taskmanager.com<br>
            Contraseña: 123456
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>

        <div class="link-container">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
    </div>
</body>

</html>
