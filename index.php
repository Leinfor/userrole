<?php
require_once "config.php";
session_start();

// Redirect unauthenticated guests to login entry page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit();
}

$current_role = $_SESSION['role'];
$username = $_SESSION['username'];
?>


<!DOCTYPE html>
<html>
<head>
    <title>Schoolwork To-Do List</title>
    <style>
        body {
            background-color: #f7fafc;
            color: #2d3748;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        h1 {
            font-size: 2rem;
            color: #1a202c;
            margin-bottom: 24px;
        }
        
        .btn-add {
            display: inline-block;
            background-color: #4c51bf;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .btn-add:hover {
            background-color: #434190;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: #edf2f7;
            color: #4a5568;
            font-weight: 600;
            text-align: left;
            padding: 14px;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 14px;
            border-bottom: 1px solid #edf2f7;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .bg-high { background-color: #fed7d7; color: #9b2c2c; }
        .bg-medium { background-color: #feebc8; color: #9c4221; }
        .bg-low { background-color: #edf2f7; color: #4a5568; }
        
        .bg-pending { background-color: #e2e8f0; color: #4a5568; }
        .bg-completed { background-color: #c6f6d5; color: #22543d; }
        
        
        .task-completed td {
            text-decoration: line-through;
            color: #a0aec0 !important;
        }
        
        .actions-links a {
            color: #4c51bf;
            text-decoration: none;
            font-weight: 600;
        }
        .actions-links a:hover {
            text-decoration: underline;
        }
        .no-data {
            text-align: center;
            color: #718096;
            padding: 30px !important;
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; background-color: #edf2f7; padding: 12px 20px; margin-bottom: 30px; border-radius: 8px; font-size: 0.95rem;">
    <div>Logged in as: <strong><?php echo htmlspecialchars($username); ?></strong> (<span style="text-transform: uppercase; color: #4c51bf; font-weight: bold;"><?php echo $current_role; ?></span>)</div>
    <div><a href="logout.php" style="color: #e53e3e; text-decoration: none; font-weight: bold;">Logout 🚪</a></div>
</div>


    <div class="container">
        <h1>📝 Schoolwork To-Do List</h1>

        <?php if ($current_role === 'teacher'): ?>
        <a href="create.php" class="btn-add">+ Add New Task</a>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tasks ORDER BY due_date ASC";
                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $p_class = ($row['priority'] == 'High') ? 'bg-high' : (($row['priority'] == 'Medium') ? 'bg-medium' : 'bg-low');
                        $s_class = ($row['status'] == 'Completed') ? 'bg-completed' : 'bg-pending';
                        $row_class = ($row['status'] == 'Completed') ? 'task-completed' : '';

                        echo "<tr class='$row_class'>";
                        echo "<td style='font-weight: 600;'>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                        echo "<td>" . $row['due_date'] . "</td>";
                        echo "<td><span class='badge $p_class'>" . $row['priority'] . "</span></td>";
                        echo "<td><span class='badge $s_class'>" . $row['status'] . "</span></td>";
                        echo "<td class='actions-links'>";
                            echo "<a href='update.php?id=" . $row['id'] . "'>Edit</a> | ";
                            
                            if ($current_role === 'teacher'){
                            echo "<a href='delete.php?id=" . $row['id'] . "' style='color: #e53e3e;' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>";
                            }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>Your list is empty! Add a task above.</td></tr>";
                }
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
