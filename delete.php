<?php
session_start();
$current_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'student';

if ($current_role !== 'teacher') {
    // Boot them back to the dashboard if they are not a teacher
    header("Location: index.php");
    exit();
}
    require_once "config.php";
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

    
    $sql = "DELETE FROM tasks WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $id = trim($_GET["id"]);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: index.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    header("location: index.php");
    exit();
}
?>
