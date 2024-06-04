<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Đăng Kí</title>
</head>
<body>
      <div class="container">
        <div class="box form-box">

        <?php 
         
         include("php/config.php");
         if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

         //verifying the unique email

         $verify_query = mysqli_query($conn,"SELECT Email FROM users WHERE Email='$email'");

         if(mysqli_num_rows($verify_query) !=0 ){
            echo "<div class='message'>
                      <p>Email này đã tồn tại, xin hãy xử dụng email khác</p>
                  </div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Trở về</button>";
         }
         else{

            mysqli_query($conn,"INSERT INTO users(Username,Email,Password) VALUES('$username','$email','$password')") or die("Error Occured");

            echo "<div class='message'>
                      <p>Đăng kí thành công!</p>
                  </div> <br>";
            echo "<a href='index.php'><button class='btn'>Đăng Nhập</button>";
         }

         }else{
        ?>
            <header>Đăng Kí</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Tên tài khoản</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Mật khẩu</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Register" required>
                </div>
                <div class="links">
                    Đã có tài khoản? <a href="index.php">Đăng Nhập</a>
                </div>
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>