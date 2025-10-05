<?php

    include '../connect.php';
    session_start();
    if(isset($_SESSION['name'])){
        header("Location: ../HomePage/homePage.php");
        exit();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <script src="login.js" defer></script>
    <title>Login</title>
</head>
<body>
    <div class="background-blur"></div>

    <div class ="login-container">
        <div class="login-box">
            <h1>WELCOME!</h1>
            <h2>Log in to Access Emergency Reporting</h2>
            <form action="" method="post">
                <input type="email" class="inputEmail" placeholder="Email" name="email" id="email" required/>
                <span class="email">Email / Mobile No.</span>
                <input type="password" class="inputPass" placeholder="Password" name="password" id="password" required/>
                <span class = "password">Password</span>
                <button type="submit" name="login" id="login" name="login">Login</button>
            </form>

            <?php

                if(isset($_POST['login'])){
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $verifyEmail = "SELECT * FROM user_info WHERE email = '$email'";
                    $verifyResult = $connection->query($verifyEmail);

                    if($verifyResult->num_rows>0) {
                        $row = $verifyResult->fetch_assoc();

                        if (password_verify($password, $row['password'])) {
                            $_SESSION['name']= $row['name'];
                            header("Location: ../HomePage/homePage.php");
                            exit();
                        }elseif(!password_verify($password, $row['password'])){
                            echo "<script>alert('Invalid Login Credentials')</script>";
                        }else{
                            echo "<script>alert('Unexpected Error Occured, Try Again Later')</script>";;
                        }
                    }
                }

            
            
            ?>


        <div class="register">
            <h3>Not Registered?</h3>
            <a href="../RegisterPage/register.php">Click Here!</a><p>To Register Now!</p>
        </div>    
        </div>
        <div class="login-img">
            <div class="logo-container">
                <img src="../GeneralAssets/logo.jpeg" alt="">
            </div>
        </div>
    </div>
</body>
</html>
