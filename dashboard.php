<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$db = "todo";

if (!isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Mengambil ID pengguna dari sesi

$sql = "SELECT * FROM tasks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Menggunakan user_id untuk mengambil tugas yang sesuai
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>To Do List</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: normal;
            line-height: 1.6;
            color: #333;
            background-color: #F3F7EC;
            display: flex;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        a {
            text-decoration: none;
            color: #337ab7;
            transition: color 0.2s ease;
        }

        a:hover {
            color: #23527c;
        }

        /* Sidebar Styles */
        .sidebar {
            background: #337ab7;
            width: 250px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            border-right: 1px solid #34495e;
            text-align: center;
            font-size: 1.5rem;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }

        .sidebar h2 {
            margin-top: 0;
            color: #ecf0f1;
        }

        .nav-link {
            color: #ecf0f1;
            text-decoration: none;
            margin: 10px 0;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .nav-link:hover {
            color: #4ca1af;
            transform: scale(1.1);
        }

        .content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            overflow-y: auto;
        }

        .content h1 {
            margin-top: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            font-size: 2rem;
            text-transform: uppercase;
        }

        .fixed-form-container {
            width: 100%;
            padding: 20px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #todo-form {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        #todo-input, #due-date-input, #description-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #todo-list {
            margin-top: 20px;
        }

        .list-group-item {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            transition: background-color 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #f5f5f5;
        }

        .complete-task {
            margin-right: 10px;
        }

        .edit-task, .delete-task {
            margin-left: 10px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .edit-task:hover, .delete-task:hover {
            background-color: #23527c;
        }

        /* Modal Styles */
        .modal-content {
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .modal-header {
            background-color: #337ab7;
            color: #fff;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            border-radius: 10px 10px 0 0;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            background-color: #337ab7;
            color: #fff;
            padding: 10px;
            border-top: 1px solid #ccc;
            border-radius: 0 0 10px 10px;
        }

        #save-changes {
            background-color: #337ab7;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer
        }
      

.logout-container {
    position: absolute;
    bottom: 20px;
    right: 20px;
    
}

.logout-button {
        color: white;
        background-color: red; 
        padding: 3px 7px;
        border-radius: 5px;
        text-decoration: none;
        margin-right: 63px;
        font-size: 18px;
    }

    .logout-button:hover {
        background-color: darkred; 
    }
    </style>
</head>
<body>
<div class="sidebar">
    <img src="todoo.png" alt="Logo" class="logo">
    <h2>Selamat Datang <?php  echo $_SESSION['nama']; ?></h2>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">To Do</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="belum_selesai.php">Belum Selesai</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="selesai.php">Selesai</a>
      </li>
    </ul>
    <div class="logout-container">
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</div>

<div class="content">
  <h1>To Do List</h1>

  <div class="fixed-form-container">
      <form id="todo-form" class="p-3 bg-light border-top">
          <div class="mb-3">
              <input type="text" id="todo-input" class="form-control" placeholder="Add a new task">
              <input type="datetime-local" id="due-date-input" class="form-control mt-2">
              <textarea id="description-input" class="form-control mt-2" placeholder="Add a description"></textarea>
          </div>
      </form>
  </div>

  <ul id="todo-list" class="list-group">
    <?php foreach ($tasks as $task): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <input type="checkbox" class="complete-task" data-id_task="<?php echo $task['id_task']; ?>" <?php echo $task['status'] === 'completed' ? 'checked' : ''; ?>>
                <strong><?php echo htmlspecialchars($task['task']); ?></strong><br>
                <span><?php echo htmlspecialchars($task['due_date']); ?></span>
                <p><?php echo htmlspecialchars($task['description']); ?></p>
            </div>
            <div>
                <button class="btn btn-warning btn-sm edit-task" style="color: black" data-id_task="<?php echo $task['id_task']; ?>">&#9998;</button>
                <button class="btn btn-danger btn-sm delete-task" style="color: black" data-id_task="<?php echo $task['id_task']; ?>">&#10005;</button>
            </div>
        </li>
    <?php endforeach; ?>
  </ul>

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="edit-form">
              <div class="mb-3">
                <input type="text" id="edit-input" class="form-control" placeholder="Edit task">
                <input type="datetime-local" id="edit-due-date-input" class="form-control mt-2">
                <textarea id="edit-description" class="form-control mt-2" placeholder="Edit description"></textarea>
              </div>
              <input type="hidden" id="edit-id">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="save-changes">Save changes</button>
          </div>
        </div>
      </div>
  </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const form = document.getElementById('todo-form');
    const input = document.getElementById('todo-input');
    const dueDateInput = document.getElementById('due-date-input');
    const descriptionInput = document.getElementById('description-input');
    const list = document.getElementById('todo-list');
    const editForm = document.getElementById('edit-form');
    const editInput = document.getElementById('edit-input');
    const editDueDateInput = document.getElementById('edit-due-date-input'); // Didefinisikan di sini
    const editDescription = document.getElementById('edit-description');
    const editId = document.getElementById('edit-id');
    const saveChangesButton = document.getElementById('save-changes');
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    const handleSubmit = function(e) {
        e.preventDefault();
        const task = input.value.trim();
        const dueDate = dueDateInput.value;
        const description = descriptionInput.value.trim();
        if (task) {
            fetch('add_task.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ task: task, due_date: dueDate, description: description })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.innerHTML = `
                        <div>
                            <input type="checkbox" class="complete-task" data-id_task="${data.id_task}">
                            <strong>${task}</strong><br>
                            <span>${dueDate}</span>
                            <p>${description}</p>
                        </div>
                        <div>
                            <button class="btn btn-warning btn-sm edit-task" style="color: black" data-id_task="${data.id_task}">&#9998;</button>
                            <button class="btn btn-danger btn-sm delete-task" style="color: black" data-id_task="${data.id_task}">&#10005;</button>
                        </div>
                    `;
                    list.appendChild(listItem);
                    input.value = '';
                    dueDateInput.value = '';
                    descriptionInput.value = '';
                } else {
                    console.error('Failed to add task:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    };

    form.addEventListener('submit', handleSubmit);

    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleSubmit(e);
        }
    });

    const handleEdit = function(e) {
        if (e.target.classList.contains('edit-task')) {
            const taskId = e.target.getAttribute('data-id_task');
            fetch(`get_task.php?id_task=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.task && data.task.task && data.task.due_date && data.task.description) {
                            editInput.value = data.task.task;
                            editDueDateInput.value = data.task.due_date;
                            editDescription.value = data.task.description;
                            editId.value = taskId;
                            editModal.show();
                        } else {
                            console.error('Incomplete task data received:', data.task);
                        }
                    } else {
                        console.error('Failed to fetch task:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    };

    const handleSaveChanges = function() {
        const taskId = editId.value;
        const updatedTask = editInput.value.trim();
        const updatedDueDate = editDueDateInput.value;
        const updatedDescription = editDescription.value.trim();
        if (updatedTask) {
            fetch('edit_task.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_task: taskId, task: updatedTask, due_date: updatedDueDate, description: updatedDescription })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const taskElement = document.querySelector(`.edit-task[data-id_task="${taskId}"]`).parentElement.parentElement;
                    taskElement.querySelector('strong').innerText = updatedTask;
                    taskElement.querySelector('span').innerText = updatedDueDate;
                    taskElement.querySelector('p').innerText = updatedDescription;
                    editModal.hide();
                } else {
                    console.error('Failed to edit task:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    };

    document.getElementById('save-changes').addEventListener('click', handleSaveChanges);

    const handleDelete = function(e) {
        if (e.target.classList.contains('delete-task')) {
            const taskId = e.target.getAttribute('data-id_task');
            fetch('delete_task.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_task: taskId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    e.target.parentElement.parentElement.remove();
                } else {
                    console.error('Failed to delete task:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    };

    const handleComplete = function(e) {
        if (e.target.classList.contains('complete-task')) {
            const taskId = e.target.getAttribute('data-id_task');
            const isCompleted = e.target.checked;
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_task: taskId, status: isCompleted ? 'completed' : 'pending' })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to update task status:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    };

    list.addEventListener('click', function(event) {
        handleEdit(event);
        handleDelete(event);
        handleComplete(event);
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
