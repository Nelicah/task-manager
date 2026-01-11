// Configuración de la API
const API_URL = "https://nelicah-task-manager.zeabur.app/api";

// Estado de la aplicación
let tasks = [];
let isEditing = false;
let editingTaskId = null;

// Elementos del DOM
const taskForm = document.getElementById("taskForm");
const tasksList = document.getElementById("tasksList");
const emptyState = document.getElementById("emptyState");
const loadingSpinner = document.getElementById("loadingSpinner");
const formTitle = document.getElementById("form-title");
const submitBtn = document.getElementById("submitBtn");
const cancelBtn = document.getElementById("cancelBtn");

// Inputs del formulario
const taskIdInput = document.getElementById("taskId");
const titleInput = document.getElementById("title");
const descriptionInput = document.getElementById("description");
const priorityInput = document.getElementById("priority");
const dueDateInput = document.getElementById("dueDate");

// Filtros
const filterStatus = document.getElementById("filterStatus");
const filterPriority = document.getElementById("filterPriority");
const searchInput = document.getElementById("searchInput");

// Estadísticas
const totalTasksSpan = document.getElementById("totalTasks");
const pendingTasksSpan = document.getElementById("pendingTasks");
const completedTasksSpan = document.getElementById("completedTasks");

// Inicialización
document.addEventListener("DOMContentLoaded", () => {
  loadTasks();
  setupEventListeners();
  setMinDate();
});

// Configurar event listeners
function setupEventListeners() {
  taskForm.addEventListener("submit", handleSubmit);
  cancelBtn.addEventListener("click", resetForm);
  filterStatus.addEventListener("change", filterTasks);
  filterPriority.addEventListener("change", filterTasks);
  searchInput.addEventListener("input", filterTasks);
}

// Establecer fecha mínima (hoy)
function setMinDate() {
  const today = new Date().toISOString().split("T")[0];
  dueDateInput.setAttribute("min", today);
}

// Mostrar loading
function showLoading() {
  loadingSpinner.style.display = "block";
  tasksList.style.opacity = "0.5";
}

// Ocultar loading
function hideLoading() {
  loadingSpinner.style.display = "none";
  tasksList.style.opacity = "1";
}

// Mostrar toast notification
function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.className = `toast ${type} show`;

  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}

// Cargar todas las tareas
async function loadTasks() {
  showLoading();

  try {
    const response = await fetch(`${API_URL}/tasks.php`);

    if (!response.ok) {
      throw new Error("Error al cargar las tareas");
    }

    const data = await response.json();

    if (data.success) {
      tasks = data.tasks || [];
      renderTasks(tasks);
      updateStats();
    } else {
      showToast(data.message || "Error al cargar las tareas", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error de conexión con el servidor", "error");
    tasks = [];
    renderTasks([]);
  } finally {
    hideLoading();
  }
}

// Renderizar tareas
function renderTasks(tasksToRender) {
  tasksList.innerHTML = "";

  if (tasksToRender.length === 0) {
    emptyState.style.display = "block";
    return;
  }

  emptyState.style.display = "none";

  tasksToRender.forEach((task) => {
    const taskCard = createTaskCard(task);
    tasksList.appendChild(taskCard);
  });
}

// Crear tarjeta de tarea
function createTaskCard(task) {
  const card = document.createElement("div");
  card.className = `task-card ${
    task.completed === "1" || task.completed === 1 ? "completed" : ""
  }`;
  card.setAttribute("data-id", task.id);

  const priorityIcons = {
    alta: "🔴",
    media: "🟡",
    baja: "🟢",
  };

  const formattedDate = task.due_date ? formatDate(task.due_date) : null;
  const isOverdue =
    task.due_date && !task.completed && new Date(task.due_date) < new Date();

  card.innerHTML = `
        <div class="task-header">
            <div class="task-title-group">
                <h3 class="task-title">${escapeHtml(task.title)}</h3>
                ${
                  task.description
                    ? `<p class="task-description">${escapeHtml(
                        task.description
                      )}</p>`
                    : ""
                }
            </div>
        </div>
        
        <div class="task-meta">
            <span class="priority-badge priority-${task.priority}">
                ${priorityIcons[task.priority]} ${capitalize(task.priority)}
            </span>
            
            ${
              formattedDate
                ? `
                <span class="task-date ${isOverdue ? "overdue" : ""}">
                    ${isOverdue ? "⚠️" : "📅"} ${formattedDate}
                    ${isOverdue ? "(Vencida)" : ""}
                </span>
            `
                : ""
            }
        </div>
        
        <div class="task-actions">
            ${
              task.completed !== "1" && task.completed !== 1
                ? `
                <button class="task-btn btn-complete" onclick="toggleTaskComplete(${task.id}, true)">
                    ✓ Completar
                </button>
            `
                : `
                <button class="task-btn btn-complete" onclick="toggleTaskComplete(${task.id}, false)">
                    ↺ Reabrir
                </button>
            `
            }
            <button class="task-btn btn-edit" onclick="editTask(${task.id})">
                ✏️ Editar
            </button>
            <button class="task-btn btn-delete" onclick="deleteTask(${
              task.id
            })">
                🗑️ Eliminar
            </button>
        </div>
    `;

  return card;
}

// Manejar envío del formulario
async function handleSubmit(e) {
  e.preventDefault();

  const taskData = {
    title: titleInput.value.trim(),
    description: descriptionInput.value.trim(),
    priority: priorityInput.value,
    due_date: dueDateInput.value || null,
  };

  if (!taskData.title) {
    showToast("El título es obligatorio", "warning");
    return;
  }

  showLoading();

  try {
    let url = `${API_URL}/tasks.php`;
    let method = "POST";

    if (isEditing && editingTaskId) {
      method = "PUT";
      taskData.id = editingTaskId;
    }

    const response = await fetch(url, {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(taskData),
    });

    const data = await response.json();

    if (data.success) {
      showToast(
        isEditing
          ? "Tarea actualizada correctamente"
          : "Tarea creada correctamente",
        "success"
      );
      resetForm();
      loadTasks();
    } else {
      showToast(data.message || "Error al guardar la tarea", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error de conexión con el servidor", "error");
  } finally {
    hideLoading();
  }
}

// Editar tarea
function editTask(taskId) {
  const task = tasks.find((t) => t.id == taskId);

  if (!task) {
    showToast("Tarea no encontrada", "error");
    return;
  }

  isEditing = true;
  editingTaskId = taskId;

  formTitle.textContent = "✏️ Editar Tarea";
  submitBtn.innerHTML = '<span class="btn-icon">💾</span> Actualizar Tarea';
  cancelBtn.style.display = "inline-flex";

  taskIdInput.value = task.id;
  titleInput.value = task.title;
  descriptionInput.value = task.description || "";
  priorityInput.value = task.priority;
  dueDateInput.value = task.due_date || "";

  // Scroll al formulario
  window.scrollTo({ top: 0, behavior: "smooth" });
  titleInput.focus();
}

// Eliminar tarea
async function deleteTask(taskId) {
  if (!confirm("¿Estás seguro de que deseas eliminar esta tarea?")) {
    return;
  }

  showLoading();

  try {
    const response = await fetch(`${API_URL}/tasks.php`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: taskId }),
    });

    const data = await response.json();

    if (data.success) {
      showToast("Tarea eliminada correctamente", "success");

      // Si estábamos editando esta tarea, resetear el formulario
      if (editingTaskId == taskId) {
        resetForm();
      }

      loadTasks();
    } else {
      showToast(data.message || "Error al eliminar la tarea", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error de conexión con el servidor", "error");
  } finally {
    hideLoading();
  }
}

// Completar/Reabrir tarea
async function toggleTaskComplete(taskId, completed) {
  showLoading();

  try {
    const response = await fetch(`${API_URL}/tasks.php`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id: taskId,
        completed: completed ? 1 : 0,
      }),
    });

    const data = await response.json();

    if (data.success) {
      showToast(
        completed ? "Tarea completada ✓" : "Tarea reabierta",
        "success"
      );
      loadTasks();
    } else {
      showToast(data.message || "Error al actualizar la tarea", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error de conexión con el servidor", "error");
  } finally {
    hideLoading();
  }
}

// Resetear formulario
function resetForm() {
  taskForm.reset();
  isEditing = false;
  editingTaskId = null;

  formTitle.textContent = "➕ Nueva Tarea";
  submitBtn.innerHTML = '<span class="btn-icon">💾</span> Guardar Tarea';
  cancelBtn.style.display = "none";
  taskIdInput.value = "";
}

// Filtrar tareas
function filterTasks() {
  const statusFilter = filterStatus.value;
  const priorityFilter = filterPriority.value;
  const searchTerm = searchInput.value.toLowerCase().trim();

  let filteredTasks = [...tasks];

  // Filtrar por estado
  if (statusFilter !== "all") {
    filteredTasks = filteredTasks.filter(
      (task) => task.completed == statusFilter
    );
  }

  // Filtrar por prioridad
  if (priorityFilter !== "all") {
    filteredTasks = filteredTasks.filter(
      (task) => task.priority === priorityFilter
    );
  }

  // Filtrar por búsqueda
  if (searchTerm) {
    filteredTasks = filteredTasks.filter(
      (task) =>
        task.title.toLowerCase().includes(searchTerm) ||
        (task.description &&
          task.description.toLowerCase().includes(searchTerm))
    );
  }

  renderTasks(filteredTasks);
}

// Actualizar estadísticas
function updateStats() {
  const total = tasks.length;
  const pending = tasks.filter(
    (t) => t.completed !== "1" && t.completed !== 1
  ).length;
  const completed = tasks.filter(
    (t) => t.completed === "1" || t.completed === 1
  ).length;

  totalTasksSpan.textContent = total;
  pendingTasksSpan.textContent = pending;
  completedTasksSpan.textContent = completed;
}

// Formatear fecha
function formatDate(dateString) {
  const date = new Date(dateString);
  const options = { year: "numeric", month: "long", day: "numeric" };
  return date.toLocaleDateString("es-ES", options);
}

// Capitalizar primera letra
function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

// Escapar HTML para prevenir XSS
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Exponer funciones globalmente para los event handlers inline
window.editTask = editTask;
window.deleteTask = deleteTask;
window.toggleTaskComplete = toggleTaskComplete;
