<?php
// Include your database connection file
include 'config.php';

session_start();
// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Handle not logged in error
    exit('Location: ../index.php');
}


// Check if task ID is set
if (isset($_POST['taskId'])) {
    $taskId = $_POST['taskId'];

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to delete the task
    $query = "DELETE FROM lists WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $taskId);
    $stmt->execute();

    // Check if task was deleted successfully
    if ($stmt->affected_rows > 0) {
        echo "Task deleted successfully";
    } else {
        echo "Error deleting task";
    }
} else {
    echo "Task ID not set";
}