<?php
// Include your database connection file
include 'config.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Handle not logged in error
    exit('Location: ../index.php');
}

// Get user ID from session
$id = $_SESSION['id'];

// Query to fetch tasks for the user
$result = mysqli_query($conn,"SELECT*FROM lists WHERE UserId=$id");

// Check if query executed successfully
if (!$result) {
    // Handle query error
    exit('Error fetching tasks');
}

// Fetch tasks and encode as JSON
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}

echo json_encode($tasks);
?>
