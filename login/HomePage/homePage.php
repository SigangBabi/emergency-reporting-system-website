<?php

    session_start();
    include '../connect.php';

    $userLoggedIn = isset($_SESSION['name']) ? true : false;

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="homePage.css">
    <script src="homePage.js" defer></script>
</head>
<body data-loggedin="<?php echo $userLoggedIn; ?>">

    <div class="topmenu">
        <div class="menubar">
            <button onclick="location.href='#home-banner'">HOME</button>
            <button onclick="location.href='#about-us'">ABOUT</button>
            <a href="https://antipolo.ph/" class="antipolo-btn"><img src="assets/antipoloLogo.png" alt="Logo" class="antipoloLogo"/></a>
            <?php
                if($userLoggedIn){
                    echo '<button><a href="../ProfileMenu/Userdashboard.php" id="login-btn">REPORT</a></button>';
                    $name = $_SESSION['name'];
                    $query = mysqli_query($connection, "SELECT * FROM user_info WHERE name='$name'");
                    if($row=mysqli_fetch_array($query)){
                        echo '<button><a href="../ProfileMenu/Userdashboard.php" id="login-btn">PROFILE</a></button>';
                    }
                }else{
                    echo '<button><a href="../LoginPage/login.php" id="login-btn">REPORT</a></button>';
                    echo '<button><a href="../LoginPage/login.php" id="login-btn">LOGIN</a></button>';
                }
            
            ?>
            
        </div>
    </div>
    <section id="home-banner">
        <div class="header">
            <img src="../GeneralAssets/logo.jpeg" alt="Logo" class="systemLogo">
            <div class="systemTitle">ANTIPOLO CITY EMERGENCY RESPONSE SYSTEM</div>
        </div>
    </section>

    <div class="content">
        <section id="about-us">
            <div class="container">
                <div class="text">
                    <p>
                        We strive to strengthen emergency response by delivering timely alerts and actionable insights through
                        our reporting and SMS system supporting first responders in making informed, swift, and impactful
                        decisions that protect lives and communities.
                    </p>
                </div>
                
                
                <div class="images">
                    <img src="assets/homeIMG.jpeg" alt="Image 1" class="img1">
                    <img src="assets/homeIMG2.jpeg" alt="Image 2" class="img2">
                    <img src="assets/homeIMG1.jpeg" alt="Image 3" class="img3">
                </div>
            </div>
        </section>
    </div>

    <section class="slider-container" id="report-now">
        <div class="slider-wrapper">
            <div class="slider">
                <img id="slide-1" src="assets/flood.jpeg" alt="flood"
                     data-title="FLOOD EMERGENCY"
                     data-subtitle="Evacuation Assistance"
                     data-cta-text="REPORT NOW!"
                     data-cta-link=<?php if($userLoggedIn) { echo "../ProfileMenu/Userdashboard.php"; }else { echo "../LoginPage/login.php";}?>
                     data-overlay="linear-gradient(to right, rgba(0,100,150,0.5), rgba(255,255,255,0.0))"
                     data-info="#e3f2fd">
                <img id="slide-2" src="assets/fire.jpeg" alt="fire"
                     data-title="FIRE ALERT"
                     data-subtitle="Control and Evacuation Assistance"
                     data-cta-text="REPORT NOW!"
                     data-cta-link=<?php if($userLoggedIn) { echo "../ProfileMenu/Userdashboard.php"; }else { echo "../LoginPage/login.php";}?>
                     data-overlay="linear-gradient(to right, rgba(0,100,150,0.5), rgba(255,255,255,0.0))"
                     data-info="#e3f2fd">

                <img id="slide-3" src="assets/earthquake.jpeg" alt="earthquake"
                     data-title="EARTHQUAKE ALERT"
                     data-subtitle="Evacuation Assistance"
                     data-cta-text="REPORT NOW!"
                     data-cta-link=<?php if($userLoggedIn) { echo "../ProfileMenu/Userdashboard.php"; }else { echo "../LoginPage/login.php";}?>
                     data-overlay="linear-gradient(to right, rgba(0,100,150,0.5), rgba(255,255,255,0.0))"
                     data-info="#e3f2fd">
                <img id="slide-4" src="assets/crime.jpeg" alt="crime"
                     data-title="CRIMINAL ACTIVITY"
                     data-subtitle="Police Assistance"
                     data-cta-text="REPORT NOW!"
                     data-cta-link=<?php if($userLoggedIn) { echo "../ProfileMenu/Userdashboard.php"; }else { echo "../LoginPage/login.php";}?>
                     data-overlay="linear-gradient(to right, rgba(0,100,150,0.5), rgba(255,255,255,0.0))"
                     data-info="#e3f2fd">
            </div>
            <div class="slider-button">
                <button id="prevBtn">&#10094;</button>
                <button id="nextBtn">&#10095;</button>
            </div>
            
            <div class="slider-nav">
                <a href="#slide-1"></a>
                <a href="#slide-2"></a>
                <a href="#slide-3"></a>
                <a href="#slide-4"></a>
            </div>
        </div>
        <div class="slide-info">
        </div>

    </section>

    <div class="footer-bar">
        <div class="footer-sec1">
            <h1>"The Safety of the People shall be the highest law."</h1>
            <h2>- Marcus Tullius Cicero</h2>
        </div>
        <div class="footer-sec2">
            <img src="assets/antipoloEmergencyNo.webp" alt="Logo" class="footerLogo1" >
            <img src="../GeneralAssets/logo.jpeg" alt="Logo" class="footerLogo2" >
            <img src="assets/antipoloLogo.png" alt="Logo" class="footerLogo3">
        </div>
    </div>
</body>
<footer>
    <p>© 2025 Antipolo City Emergency Response System. All rights reserved.</p>
</footer>
</html>
