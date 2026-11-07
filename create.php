<?php
session_start();
$current_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'student';

if ($current_role !== 'teacher') {
    // Boot them back to the dashboard if they are not a teacher
    header("Location: index.php");
    exit();
}
 require_once "config.php";

$title = $subject = $due_date = $priority = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $subject = trim($_POST["subject"]);
    $due_date = trim($_POST["due_date"]);
    $priority = trim($_POST["priority"]);

    $sql = "INSERT INTO tasks (title, subject, due_date, priority) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $title, $subject, $due_date, $priority);
        if (mysqli_stmt_execute($stmt)) {
            header("location: index.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add a New Homework</title>
    <style>
        body {
            background-color: #f7fafc;
            color: #2d3748;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 40px 20px;
        }
        .form-card {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        h2 {
            margin-bottom: 20px;
            color: #1a202c;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #4a5568;
        }
        /* Guaranteed stable wide sizing variables */
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            box-sizing: border-box; /* Crucial block element scaling fix */
            font-size: 1rem;
        }
        input:focus, select:focus {
            border-color: #4c51bf;
            outline: none;
        }
        .btn-submit {
            background-color: #4c51bf;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-submit:hover {
            background-color: #434190;
        }
        .btn-cancel {
            color: #718096;
            text-decoration: none;
            margin-left: 15px;
            font-weight: 600;
        }
        .btn-cancel:hover {
            color: #4a5568;
        }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>➕ Add New Homework</h2>
        
        <form action="create.php" method="post">
            
            <div class="form-group">
                <label>Assignment Title</label>
                <input type="text" name="title" required placeholder="e.g., Read Chapter 4">
            </div>
            
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required placeholder="e.g., History">
            </div>
            
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" required>
            </div>
            
            <div class="form-group">
                <label>Priority Level</label>
                <select name="priority">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-submit">Save Task</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </div>
            
        </form>
    </div>

</body>
</html>
