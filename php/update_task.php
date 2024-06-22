<?php
require 'config.php';

if (isset($_POST['update_task_submit'])) {
    $taskId = $_POST['taskId'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        $stmt = $conn->prepare("UPDATE lists SET Title = ?, Content = ? WHERE Id = ?");
        $stmt->bind_param("ssi", $title, $content, $taskId);
        $res = $stmt->execute();

        if($res) {
            echo json_encode(['status' => 1, 'message' => 'Task updated successfully']);
        } else {
            echo json_encode(['status' => 0, 'message' => 'Failed to update task']);
        }
    } catch (PDOException $e) {
        // Handle PDO exception
        echo json_encode(['status' => 0, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Handle other exceptions
        echo json_encode(['status' => 0, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid parameters']);
}
?>
