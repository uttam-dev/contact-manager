<?php
require "../conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();

    $uid = $_SESSION["login"]["uid"];
    $cid = mysqli_escape_string($conn, base64_decode($_POST["cid"]));
    $name = mysqli_escape_string($conn, $_POST['name']);
    $phone = mysqli_escape_string($conn, $_POST['phone']);
    $nickname = mysqli_escape_string($conn, $_POST['nickname']);
    $company = mysqli_escape_string($conn, $_POST['company']);
    $email = mysqli_escape_string($conn, $_POST['email']);
    $residential_address = mysqli_escape_string($conn, $_POST['residential-address']);
    $parmenent_address = mysqli_escape_string($conn, $_POST['parmenent-address']);
    $dob = mysqli_escape_string($conn, $_POST['dob']);

    
    $img_name ='';

    if ($name == "" && $phone = "") {
        echo json_encode(array("status" => "400", "response" => "column-value-not-find"));
        exit();
    }

    $query = "SELECT `profile` FROM `uid_$uid` WHERE cid = $cid";
    $res = mysqli_query($conn, $query);
    $profile = mysqli_fetch_assoc($res);

    if ($_FILES["profile"]["size"] > 0) {
        $extension = pathinfo($_FILES["profile"]["name"], PATHINFO_EXTENSION);
        $img_name = rand(10000000, 99999999) . "." . $extension;
       
        if($img_name != ""){
            unlink(PROFILE_IMG_PATH.$profile["profile"]);
        }
        move_uploaded_file($_FILES["profile"]["tmp_name"], PROFILE_IMG_PATH . $img_name);
    }else{
        $img_name = $profile["profile"];
    }

    $query = "UPDATE `uid_$uid` SET
`name` = '$name',
`phone`= '$phone',
`profile`='$img_name',
`nickname` = '$nickname',
`company`='$company',
`email`='$email',
`residential_address`='$residential_address',
`parmenent_address`='$parmenent_address',
`dob`='$dob' 
WHERE `cid` = $cid";

    if ($response = mysqli_query($conn, $query)) {
        echo json_encode(array("status" => "200", "response" => "contact-update-success"));
    } else {
        echo json_encode(array("status" => "400", "response" => "update-query-not-execute"));
    }
}
