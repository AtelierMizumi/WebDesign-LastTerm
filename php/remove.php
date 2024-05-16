<?php

if(isset($_POST['id'])){
    require '../config.php';

    // Get task's id and delete
    $id = $_POST['id'];

    if(empty($id)){
        echo 0;
    }else {
        $stmt = $conn->prepare("DELETE FROM lists WHERE id=?");
        $res = $stmt->execute([$id]);

        if($res){
            echo 1;
        }else {
            echo 0;
        }
        $conn = null;
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}