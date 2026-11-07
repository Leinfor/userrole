<?php
require_once "config.php";
session_start();

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Query to match credentials
    $sql = "SELECT id, username, role FROM users WHERE username = ? AND password = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Store critical details into user's session
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];
            
            // Send logged-in user to the main dashboard
            header("location: index.php");
            exit();
        } else {
            $error_msg = "Invalid username or password configuration.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Login</title>
    <style>
        body {
            background-color: #f7fafc;
            color: #2d3748;
            font-family: 'Segoe UI', system-ui, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-card {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        h2 { margin-bottom: 24px; text-align: center; color: #1a202c; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; color: #4a5568; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 6px; box-sizing: border-box; font-size: 1rem;
        }
        input:focus { border-color: #4c51bf; outline: none; }
        .btn-submit {
            width: 100%; background-color: #4c51bf; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 1rem; margin-top: 10px;
        }
        .btn-submit:hover { background-color: #434190; }
        .error { color: #e53e3e; text-align: center; margin-bottom: 15px; font-size: 0.95rem; font-weight: 600; }
        .demo-info { background: #edf2f7; padding: 10px; border-radius: 6px; margin-top: 20px; font-size: 0.85rem; color: #4a5568; line-height: 1.4; }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>🔒 Portal Login</h2>
        
        <?php if(!empty($error_msg)) echo "<div class='error'>$error_msg</div>"; ?>

        <form action="login.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="e.g., student1">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <div class="demo-info">
            <strong>Demo Accounts:</strong><br>
            • Teacher: <code>teacher1</code> / <code>password123</code><br>
            • Student: <code>student1</code> / <code>password123</code>
        </div>
    </div>

</body>
</html>
