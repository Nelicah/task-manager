# 📋 Task Manager - Gestor de Tareas

Aplicación completa de gestión de tareas con frontend moderno (HTML, CSS, JavaScript) y backend en PHP con MySQL.

## 🎯 Características

- ✅ **CRUD Completo**: Crear, Leer, Actualizar y Eliminar tareas
- ✅ **Interfaz Moderna**: Diseño responsivo con gradientes y animaciones
- 🔍 **Filtros y Búsqueda**: Filtra por estado, prioridad y busca por texto
- 📊 **Estadísticas en Tiempo Real**: Contadores de tareas totales, pendientes y completadas
- 🎨 **Sistema de Prioridades**: Alta (🔴), Media (🟡), Baja (🟢)
- 📅 **Fechas Límite**: Con indicador de tareas vencidas
- ✨ **Notificaciones Toast**: Feedback visual con mensajes temporales
- 📱 **Totalmente Responsivo**: Funciona en móviles, tablets y desktop
- 🔒 **Seguridad**: Prepared statements para prevenir SQL Injection

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

## 📁 Estructura del Proyecto

```
task-manager/
├── index.html          # Página principal
├── styles.css          # Estilos CSS
├── script.js           # Lógica JavaScript
└── api/
    ├── config.php      # Configuración de base de datos
    └── tasks.php       # API RESTful con CRUD completo
```

### Validaciones Implementadas

El backend valida:

- ✅ Título no vacío
- ✅ Prioridad válida (baja, media o alta)
- ✅ ID existe antes de actualizar/eliminar
- ✅ Formato de datos correcto

## 🔒 Seguridad Implementada

- ✅ **Prepared Statements** - Previene SQL Injection
- ✅ **Validación de datos** - Verifica título, prioridad, ID
- ✅ **Escapado de HTML** - Previene XSS en el frontend con `escapeHtml()`
- ✅ **trim()** - Limpia espacios en blanco de los inputs
- ✅ **Headers CORS** - Configurados correctamente
- ✅ **Try-Catch** - Manejo de errores sin exponer información sensible
- ✅ **Códigos HTTP apropiados** - 200, 201, 400, 404, 500

## 🎯 Funciones JavaScript Principales

```javascript
loadTasks(); // Carga todas las tareas desde la API
handleSubmit(e); // Crea o actualiza una tarea
editTask(taskId); // Prepara el formulario para editar
deleteTask(taskId); // Elimina una tarea con confirmación
toggleTaskComplete(); // Marca como completada o reabre
filterTasks(); // Aplica filtros de búsqueda
showToast(msg, type); // Muestra notificaciones temporales
```

### Lo que Demuestra este Proyecto

✅ **Fullstack Development** - Frontend y Backend integrados  
✅ **API RESTful** - Conocimiento de arquitectura moderna  
✅ **Seguridad** - Prepared statements, validaciones, XSS prevention  
✅ **UX/UI** - Diseño responsivo, animaciones, feedback visual  
✅ **JavaScript Moderno** - Async/await, Fetch API, ES6+  
✅ **PHP Moderno** - PDO, manejo de JSON, try-catch  
✅ **Base de Datos** - Diseño de tablas, índices, tipos de datos  
✅ **Problem Solving** - Manejo de edge cases y errores

## ✨ Características del Frontend

### Notificaciones Toast

Mensajes temporales que aparecen y desaparecen:

- Verde: Operación exitosa
- Rojo: Error
- Amarillo: Advertencia

### Animaciones CSS

- Fade in al cargar
- Hover effects en tarjetas
- Transiciones suaves
- Loading spinner

### Sistema de Filtros

- Por estado (todas, pendientes, completadas)
- Por prioridad (todas, alta, media, baja)
- Búsqueda en tiempo real por título/descripción

### Diseño Responsivo

Breakpoints optimizados:

- Mobile: < 768px
- Tablet: 768px - 1199px
- Desktop: 1200px+

## 📝 Notas Finales

- ✅ Compatible con navegadores modernos (Chrome, Firefox, Safari, Edge)
- ✅ Sin dependencias de librerías externas (jQuery, Bootstrap, etc.)
- ✅ Código limpio y bien comentado
- ✅ Arquitectura escalable y mantenible
- ✅ Preparado para producción con validaciones y seguridad
- ✅ Diseñado pensando en la experiencia del usuario

---

**Desarrollado por Nelicah** - Fullstack Developer
