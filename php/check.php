<?php

if(isset($_POST['id'])){
    require 'config.php';

    $id = $_POST['id'];

    if(empty($id)){
       echo 'error';
    }else {
        $todos = $conn->prepare("SELECT id, checked FROM lists WHERE id=?");
        $todos->execute([$id]);

        $todo = $todos->fetch();
        $id = $todo['id'];
        $checked = $todo['checked'];

        $uChecked = $checked ? 0 : 1;

        $res = $conn->query("UPDATE lists SET checked=$uChecked WHERE id=$id");

        if($res){
            echo $checked;
        }else {
            echo "error";
        }
        $conn = null;
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}