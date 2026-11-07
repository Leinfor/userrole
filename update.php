<?php
require_once "config.php";
$id = $title = $subject = $due_date = $priority = $status = "";

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
    $sql = "SELECT * FROM tasks WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($res) == 1) {
                $row = mysqli_fetch_assoc($res);
                $title = $row["title"];
                $subject = $row["subject"];
                $due_date = $row["due_date"];
                $priority = $row["priority"];
                $status = $row["status"];
            }
        }
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $title = trim($_POST["title"]);
    $subject = trim($_POST["subject"]);
    $due_date = trim($_POST["due_date"]);
    $priority = trim($_POST["priority"]);
    $status = $_POST["status"];

    $sql = "UPDATE tasks SET title=?, subject=?, due_date=?, priority=?, status=? WHERE id=?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $subject, $due_date, $priority, $status, $id);
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
    <title>Edit My Homework</title>
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
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        input:focus, select:focus {
            border-color: #4c51bf;
            outline: none;
        }
        .btn-submit {
            background-color: #dd6b20;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-submit:hover {
            background-color: #c05621;
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
        <h2>✏️ Edit Homework Task</h2>
        
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="form-group">
                <label>Assignment Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" value="<?php echo $due_date; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Priority Level</label>
                <select name="priority">
                    <option value="Low" <?php if($priority == 'Low') echo 'selected'; ?>>Low</option>
                    <option value="Medium" <?php if($priority == 'Medium') echo 'selected'; ?>>Medium</option>
                    <option value="High" <?php if($priority == 'High') echo 'selected'; ?>>High</option>
                </select>
            </div>

            <div class="form-group">
                <label>Task Status</label>
                <select name="status">
                    <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Completed" <?php if($status == 'Completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
            
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-submit">Save Changes</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </div>
            
        </form>
    </div>

</body>
</html>
