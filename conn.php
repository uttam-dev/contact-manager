<?php 
$conn = mysqli_connect("localhost","root","","contact-manager");

    if($conn == ""){
        echo "connection failed..";
    }

    define('PROFILE_IMG_PATH',"../images/profile/");
?>
