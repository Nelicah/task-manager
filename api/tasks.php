<?php
// Incluir la configuración de base de datos
require_once 'config.php';

// Indicar que la respuesta será JSON
header('Content-Type: application/json');

// Obtener el método HTTP de la petición
$metodo = $_SERVER['REQUEST_METHOD'];

// Conectar a la base de datos
$conn = conectarDB();

// =====================================================
// ENRUTADOR - Según el método HTTP, ejecutar función
// =====================================================
try {
    switch ($metodo) {
        case 'GET':
            obtenerTareas($conn);
            break;

        case 'POST':
            crearTarea($conn);
            break;

        case 'PUT':
            actualizarTarea($conn);
            break;

        case 'DELETE':
            eliminarTarea($conn);
            break;

        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor',
        'error' => $e->getMessage()
    ]);
}

// =====================================================
// FUNCIÓN GET - Obtener todas las tareas
// =====================================================
function obtenerTareas($conn)
{
    try {
        // Consulta SQL ordenada por prioridad y fecha
        $sql = "SELECT id, title, description, priority, due_date, completed, 
                       created_at
                FROM tasks 
                ORDER BY 
                    CASE priority 
                        WHEN 'alta' THEN 1 
                        WHEN 'media' THEN 2 
                        WHEN 'baja' THEN 3 
                    END,
                    completed ASC,
                    due_date ASC,
                    created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver respuesta exitosa
        echo json_encode([
            'success' => true,
            'tasks' => $tareas,
            'count' => count($tareas)
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al obtener las tareas'
        ]);
    }
}

// =====================================================
// FUNCIÓN POST - Crear nueva tarea
// =====================================================
function crearTarea($conn)
{
    // Obtener los datos enviados desde el frontend
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que el título no esté vacío
    if (empty($data['title'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'El título es obligatorio'
        ]);
        return;
    }

    // Validar que la prioridad sea válida
    $prioridades_validas = ['baja', 'media', 'alta'];
    if (empty($data['priority']) || !in_array($data['priority'], $prioridades_validas)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Prioridad no válida o no proporcionada'
        ]);
        return;
    }

    try {
        // Preparar consulta SQL con placeholders
        $sql = "INSERT INTO tasks (title, description, priority, due_date) 
                VALUES (:title, :description, :priority, :due_date)";

        $stmt = $conn->prepare($sql);

        // Ejecutar con los datos (prepared statement previene SQL injection)
        $stmt->execute([
            ':title' => trim($data['title']),
            ':description' => isset($data['description']) ? trim($data['description']) : null,
            ':priority' => $data['priority'],
            ':due_date' => !empty($data['due_date']) ? $data['due_date'] : null
        ]);

        // Obtener el ID de la tarea recién creada
        $ultimoId = $conn->lastInsertId();

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Tarea creada correctamente',
            'id' => $ultimoId
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al crear la tarea'
        ]);
    }
}

// =====================================================
// FUNCIÓN PUT - Actualizar tarea existente
// =====================================================
function actualizarTarea($conn)
{
    // Obtener los datos enviados desde el frontend
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que se envió el ID
    if (empty($data['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID de tarea requerido'
        ]);
        return;
    }

    try {
        // Verificar si la tarea existe
        $checkSql = "SELECT id FROM tasks WHERE id = :id";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->execute([':id' => $data['id']]);

        if ($checkStmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Tarea no encontrada'
            ]);
            return;
        }

        // Construir la consulta UPDATE dinámicamente
        $camposActualizar = [];
        $parametros = [':id' => $data['id']];

        // Solo actualizar los campos que se enviaron
        if (isset($data['title'])) {
            $camposActualizar[] = "title = :title";
            $parametros[':title'] = trim($data['title']);
        }

        if (isset($data['description'])) {
            $camposActualizar[] = "description = :description";
            $parametros[':description'] = trim($data['description']);
        }

        if (isset($data['priority'])) {
            // Validar prioridad
            $prioridades_validas = ['baja', 'media', 'alta'];
            if (!in_array($data['priority'], $prioridades_validas)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Prioridad no válida'
                ]);
                return;
            }
            $camposActualizar[] = "priority = :priority";
            $parametros[':priority'] = $data['priority'];
        }

        if (isset($data['due_date'])) {
            $camposActualizar[] = "due_date = :due_date";
            $parametros[':due_date'] = !empty($data['due_date']) ? $data['due_date'] : null;
        }

        if (isset($data['completed'])) {
            $camposActualizar[] = "completed = :completed";
            $parametros[':completed'] = $data['completed'] ? 1 : 0;
        }

        // Verificar que haya algo que actualizar
        if (empty($camposActualizar)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'No hay campos para actualizar'
            ]);
            return;
        }

        // Construir y ejecutar la consulta
        $sql = "UPDATE tasks SET " . implode(', ', $camposActualizar) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);

        echo json_encode([
            'success' => true,
            'message' => 'Tarea actualizada correctamente'
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar la tarea'
        ]);
    }
}

// =====================================================
// FUNCIÓN DELETE - Eliminar tarea
// =====================================================
function eliminarTarea($conn)
{
    // Obtener los datos enviados desde el frontend
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que se envió el ID
    if (empty($data['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID de tarea requerido'
        ]);
        return;
    }

    try {
        // Preparar y ejecutar consulta DELETE
        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $data['id']]);

        // Verificar si se eliminó alguna fila
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Tarea no encontrada'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Tarea eliminada correctamente'
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar la tarea'
        ]);
    }
}
