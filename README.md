# 📋 Task Manager - Gestor de Tareas

Aplicación fullstack de gestión de tareas con autenticación de usuarios, permisos por usuario y CRUD completo. Frontend en HTML, CSS y JavaScript; backend en PHP con MySQL. Desplegada en producción en **Zeabur**.

## 🚀 Proceso de Desarrollo

Este proyecto fue desarrollado en un entorno local usando **XAMPP** (htdocs), lo que permitió un desarrollo ágil e iterativo con pruebas constantes en un servidor Apache local. Una vez completada la funcionalidad y validada la aplicación, el código fue migrado a un repositorio de GitHub para el despliegue en **Zeabur**.

**Nota sobre el historial de commits:** El historial de Git refleja principalmente la migración y preparación para producción, ya que el desarrollo principal se realizó en el entorno local de XAMPP. Esta es una práctica habitual cuando se trabaja con herramientas de desarrollo local antes de establecer el repositorio para deployment.

## 🎯 Características

### Autenticación
- 🔐 **Registro de usuarios** con validación y hash de contraseña (bcrypt)
- 🔑 **Login / Logout** con gestión de sesiones PHP
- 🛡️ **Protección de rutas** — redirige al login si no hay sesión activa
- 👤 **Header dinámico** con nombre del usuario y botón de cerrar sesión

### Tareas
- ✅ **CRUD Completo**: Crear, Leer, Actualizar y Eliminar tareas
- 🔒 **Aislamiento por usuario**: cada usuario solo ve y gestiona sus propias tareas
- 🎨 **Sistema de Prioridades**: Alta (🔴), Media (🟡), Baja (🟢)
- 📅 **Fechas Límite** con indicador de tareas vencidas
- 🔍 **Filtros y Búsqueda**: por estado, prioridad y texto libre
- 📊 **Estadísticas en Tiempo Real**: total, pendientes y completadas
- ✨ **Notificaciones Toast**: feedback visual temporal
- 📱 **Totalmente Responsivo**: móvil, tablet y desktop

## 🛠️ Tecnologías

### Frontend
- HTML5
- CSS3 (Variables CSS, Flexbox, Grid, Animaciones)
- JavaScript ES6+ (Fetch API, Async/Await, Vanilla JS)
- Sin dependencias externas

### Backend
- PHP 7.4+ con PDO
- MySQL 5.7+ / MariaDB
- API RESTful (un endpoint, múltiples métodos HTTP)
- Sesiones PHP para autenticación

## 📁 Estructura del Proyecto

```
task-manager/
├── index.php               # Entrada principal (protegida, inyecta header de usuario)
├── index.html              # Interfaz de la aplicación
├── login.php               # Formulario de inicio de sesión
├── registro.php            # Formulario de registro
├── logout.php              # Cierre de sesión
├── config/
│   └── database.php        # Configuración PDO (clase Database)
├── models/
│   └── Usuario.php         # Modelo de usuario (login, registro, validación)
├── includes/
│   └── auth_check.php      # Middleware de autenticación
├── api/
│   ├── config.php          # Configuración de BD para la API
│   └── tasks.php           # API RESTful con CRUD completo
└── styles/
    ├── main.css             # Estilos principales
    └── auth.css             # Estilos de login/registro
```

## 🔒 Seguridad Implementada

- ✅ **Prepared Statements** — previene SQL Injection en todas las queries
- ✅ **password_hash / password_verify** — contraseñas nunca en texto plano
- ✅ **Autenticación por sesión** — rutas y API protegidas
- ✅ **Aislamiento de datos** — `usuario_id` en todas las operaciones CRUD
- ✅ **htmlspecialchars()** — previene XSS en outputs PHP
- ✅ **Validación de datos** — título, prioridad, ID en backend
- ✅ **Códigos HTTP apropiados** — 200, 201, 400, 401, 404, 500
- ✅ **Try-Catch** — manejo de errores sin exponer información sensible

## 🎯 Lo que Demuestra este Proyecto

✅ **Fullstack Development** — frontend y backend integrados
✅ **Autenticación y permisos** — sesiones, hash, aislamiento por usuario
✅ **API RESTful** — arquitectura moderna con métodos HTTP
✅ **Seguridad** — prepared statements, bcrypt, validaciones, XSS prevention
✅ **UX/UI** — diseño responsivo, animaciones, feedback visual
✅ **JavaScript Moderno** — async/await, Fetch API, ES6+
✅ **PHP Moderno** — PDO, sesiones, OOP, manejo de JSON
✅ **Base de Datos** — diseño de tablas, relaciones, índices
✅ **Despliegue en producción** — configuración para Zeabur

---

**Desarrollado por Nelicah** - Fullstack Developer
