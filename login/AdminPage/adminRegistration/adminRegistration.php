<?php

    include '../../connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="adminRegistration.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="background-blur"></div>

    <div class="registration-container">
        <h1>Admin Registration</h1>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" placeholder="Ex. John Doe" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Ex. email@exemail.com" required>
            <label for="mobile">Mobile No.:</label>
            <input type="text" name="mobile" id="mobile" placeholder="+639--" required>
            <label for="role">Role:</label>
            <input type="text" name="role" id="role" placeholder="Ex. Administrator" required>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Ex. doejohn12" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="cPassword">Confirm Password:</label>
            <input type="password" name="cPassword" id="cpassword" placeholder="Confirm Password" required>
            <div class="show-password">
                <input type="checkbox">
                <p>Show Password</p>
            </div>
            <button type="submit" name="submit" id="submit">SIGN UP</button>
        </form>

        <?php
        
            if(isset($_POST['submit'])) {
                $fullName = $_POST['name'];
                $userEmail = $_POST['email'];
                $userMobile = $_POST['mobile'];
                $userRole = $_POST['role'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['cPassword'];

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $checkEmail = "SELECT * From admin_info where email='$userEmail'";
                $checkEmailResult = $connection->query($checkEmail);

                $checkUsername = "SELECT * From admin_info where username='$username'";
                $checkUsernameResult = $connection->query($checkUsername);

                if($password != $confirmPassword) {
                    echo "<script>alert('Password Do not Match')</script>";
                }elseif($checkEmailResult->num_rows>0){
                    echo "<script>alert('Email Already Exist')</script>";
                }elseif($checkUsernameResult->num_rows>0){
                    echo "<script>alert('Username Already Exist, Please enter a diffrent one')</script>";
                }else{
                    $insertAdminInfo = "INSERT INTO admin_info(name, role, email, mobile_no, username, password)
                                        VALUES ('$fullName', '$userRole', '$userEmail', '$userMobile', '$username', '$hashedPassword')";
                    if($connection->query($insertAdminInfo) == TRUE){
                        header("location: adminRegistration.php?status=success");
                    }else{
                        header("location: adminRegistration.php?status=error");
                    }
                }
                
            }
        
        
        
        ?>
    </div>
    <div id="php-status" data-status="<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>"></div>
    <script src="adminRegistration.js"></script>
</body>
</html> 