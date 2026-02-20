<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Task Manager</title>
    <link rel="stylesheet" href="./styles/auth.css">
</head>

<body>
    <div class="auth-container">
        <h1>📝 Registro</h1>

        <?php
        session_start();

        // Si ya está logueado, redirigir
        if (isset($_SESSION['usuario_id'])) {
            header("Location: index.php");
            exit();
        }

        $error = "";
        $success = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once 'config/database.php';
            require_once 'models/Usuario.php';

            $database = new Database();
            $db = $database->getConnection();
            $usuario = new Usuario($db);

            $usuario->nombre = $_POST['nombre'];
            $usuario->email = $_POST['email'];
            $usuario->password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            // Validaciones
            if (empty($usuario->nombre) || empty($usuario->email) || empty($usuario->password)) {
                $error = "Todos los campos son obligatorios";
            } elseif ($usuario->password !== $password_confirm) {
                $error = "Las contraseñas no coinciden";
            } elseif (strlen($usuario->password) < 6) {
                $error = "La contraseña debe tener al menos 6 caracteres";
            } elseif ($usuario->emailExiste()) {
                $error = "Este email ya está registrado";
            } else {
                if ($usuario->registrar()) {
                    $success = "¡Registro exitoso! Ahora puedes iniciar sesión";
                } else {
                    $error = "Error al registrar usuario";
                }
            }
        }
        ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required
                    value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required
                    minlength="6" placeholder="Mínimo 6 caracteres">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmar contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>

            <button type="submit" class="btn">Registrarse</button>
        </form>

        <div class="link-container">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>

</html>