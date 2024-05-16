<?php

if(isset($_POST['title']) && isset($_POST['content'])){
    require 'config.php';

    $UserId = $_SESSION['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if(empty($title)){
        header("Location: ../index.php?mess=error");
    } else if(empty($content)){
        header("Location: ../index.php?mess=error");
    }else {
        $stmt = $conn->prepare("INSERT INTO lists VALUE(?, ?, ?)");
        $res = $stmt->execute([$UserId], [$title], [$content]);

        if($res){
            header("Location: ../index.php?mess=success"); 
        }else {
            header("Location: ../index.php");
        }
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}