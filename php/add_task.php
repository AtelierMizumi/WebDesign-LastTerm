<?php
session_start();

require 'config.php';

$response = array();

try {
    if (isset($_POST['new_task_submit'])) {
        $UserId = $_SESSION['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        if (empty($UserId) || empty($title) || empty($content)) {
            throw new Exception('Please fill in all the mandatory fields.');
        }

        $stmt = $conn->prepare("INSERT INTO lists (UserId, Title, Content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $UserId, $title, $content);

        if ($stmt->execute()) {
            $response['status'] = 1;
            $response['message'] = 'New task added successfully.';
        } else {
            throw new Exception('Error occurred: ' . $stmt->error);
        }
    } else {
        throw new Exception('Invalid request.');
    }
} catch (Exception $e) {
    $response['status'] = 0;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
session_write_close(); // Release the session lock
?>
