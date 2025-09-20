<?php
    include '../connect.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="register.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Registration</title>
</head>
<body>
    <div class="registration-container">
        <div class="manual-reg">
            <div class="title">
                <h1>Register Now!</h1>
                <h3>Fill out the form and you're one step closer to a safer community</h3>
            </div>
            <div class="registration-form">
                <form action="" method="post">
                    <label for="fname">Full Name:</label>
                    <input type="text" id="fname" name="fname" placeholder="Ex. John Doe" required><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Ex. delacrusjuan@123mail.com" required><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter Password" required><br>
                    <label for="confirm">Confirm Password:</label>
                    <input type="password" id="confirm" name="confirm" placeholder="Re-enter Password" required><br>
                    <div class="submit-btn">
                        <label for="seepass" class="seepass">See Password</label>
                        <input type="checkbox" id="seepass" name="seepass" class="seepass"><br>
                        <input type="submit" id="submit" class="submit" name="submit">
                    </div>
                </form>

                <!-- For Checking of registration details -->

                <?php
                    if (isset($_POST['submit'])) {
                        $fullName = $_POST['fname'];
                        $userEmail = $_POST['email'];
                        $userPassword = $_POST['password'];
                        $confirmPassword = $_POST['confirm'];
                        $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

                        $checkEmail = "SELECT * From user_info where email='$userEmail'";
                        $checkResult = $connection->query($checkEmail);

                        if ($userPassword != $confirmPassword) {
                            echo "<p id=\"callback\">Password do not match!</p>";
                        }elseif($checkResult->num_rows>0){
                            echo "<p id=\"callback\">Email Already Exist!</p>";
                        }else{
                            $insertUserInfo = "INSERT INTO user_info(email, password, name  )
                                                VALUES ('$userEmail', '$hashedPassword', '$fullName')";
                            if($connection->query($insertUserInfo)==TRUE){
                                header("location: register.php?status=success");
                                exit();
                            }else{
                                header("location: register.php?status=error");
                            }
                        }


                    }
                ?>

            </div>
            <div class="back-to-login">
                    <h5>Already have an account? <a href="../LoginPage/login.html">Click Here</a> to go back</h5>
            </div>
        </div>
        <div class="other">
            <h1>Be <span class="prep">Prepared.</span> <br>Stay <span class="connect">Connected.</span> <br>Respond <span class="fast">Faster.</span></h1>
            <h4>Join our emergency response network today and ensure that you, your family, and your community never face a crisis alone.</h4>
            <div class="reg-img">
                <img src="assets/register.jpeg">
            </div>
        </div>
    </div>
    
    <!-- Check Registration Status -->
    <div id="php-status" data-status="<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>"></div>
    <script src="register.js"></script>
</body>
</html>
