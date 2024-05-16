<!-- Submit new task for the user -->
<?php

session_start(); // start or resume session

require 'config.php';

$status = 0;
$statusMsg = "";
if(isset($_POST['new_task_submit'])){
    // Get the submitted form data
    $UserId = $_SESSION['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Check whether submitted data is not empty
    if(!empty($UserId) && !empty($title) && !empty($content)){
        $stmt = $conn->prepare("INSERT INTO lists (UserId, Title, Content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $UserId, $title, $content);
        
        if ($stmt->execute()) {
            $status = 1;
            $statusMsg = 'New task added successfully.';
            header("Location: ../home.php?mess=success");
        } else {
            $statusMsg = 'Error occurred: ' . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $statusMsg = 'Please fill in all the mandatory fields.';
    }
}

$response = array(
    'status' => $status,
    'message' => $statusMsg
);
echo json_encode($response);
?>