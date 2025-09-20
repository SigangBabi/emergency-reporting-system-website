<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "antipolo_emergency_web";
$connection = new mysqli($host, $user, $password, $database);
if($connection -> connect_error){
    echo "Failed to connect to local Database";
}
?>