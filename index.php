<?php 
   session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="asset/favicon.ico" type="image/ico">
    <link rel="stylesheet" href="style/style.css">
    <title>Đăng Nhập</title>
</head>
<body>
      <div class="container" style="min-height: 100vh;">
        <div class="box form-box">
            <?php 
             
              include("php/config.php");
              if(isset($_POST['submit'])){
                $email = mysqli_real_escape_string($conn,$_POST['email']);
                $password = mysqli_real_escape_string($conn,$_POST['password']);

                $result = mysqli_query($conn,"SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                if(is_array($row) && !empty($row)){
                    $_SESSION['valid'] = $row['Email'];
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['id'] = $row['Id'];
                }else{
                    echo "<div class='message'>
                      <p>Sai tài khoản hoặc mật khẩu</p>
                       </div> <br>";
                   echo "<a href='index.php'><button class='btn btn-primary fw-bold'>Trở về</button>";
         
                }
                if(isset($_SESSION['valid'])){
                    header("Location: home.php");
                }
              }else{

            
            ?>
            <header>Đăng Nhập</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Mật khẩu</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <br>

                <div class="field">
                    
                    <input type="submit" class="btn btn-primary fw-bold" name="submit" value="Đăng Nhập" required>
                </div>
                <div class="links">
                    Chưa có tải khoản? <a href="register.php">Đăng Kí Ngay</a>
                </div>
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>