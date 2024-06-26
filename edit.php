<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="asset/favicon.ico" type="image/ico">
    <link rel="stylesheet" href="style/style.css">
    <title>Thay đổi thông tin</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Fastodo</a></p>
        </div>

        <div class="right-links">
            <a href="#">Đổi thông tin</a>
            <a href="php/logout.php"> <button class="btn btn-primary fw-bold">Đăng xuất</button> </a>
        </div>
    </div>  
    <div style="min-height: 100vh;" class="container">
        <div class="box form-box">
            <?php 
               if(isset($_POST['submit'])){
                $username = $_POST['username'];
                $email = $_POST['email'];

                $id = $_SESSION['id'];

                $edit_query = mysqli_query($conn,"UPDATE users SET Username='$username', Email='$email' WHERE Id=$id ") or die("error occurred");

                if($edit_query){
                    echo "<div class='message'>
                    <p>Đã cập nhật thông tin!</p>
                </div> <br>";
              echo "<a href='home.php'><button class='btn btn-primary fw-bold'>Về trang chủ</button>";
       
                }
               }else{

                $id = $_SESSION['id'];
                $query = mysqli_query($conn,"SELECT*FROM users WHERE Id=$id ");

                while($result = mysqli_fetch_assoc($query)){
                    $res_Uname = $result['Username'];
                    $res_Email = $result['Email'];
                }

            ?>
            <header>Thay đổi thông tin</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Tên người dùng</label>
                    <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
                </div>
                    
                    <input type="submit" class="btn btn-primary fw-bold" name="submit" value="Cập nhật" required>
                </div>
                
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>