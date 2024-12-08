<?php
require "../conn.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "SELECT COUNT(*) as count FROM `user` WHERE `email` = '$email'";
    $response = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($response);

    if ($result["count"] > 0) {
        echo json_encode(array("status" => 409, "response" => "email already exist"));
    } else {
        $query = "INSERT INTO `user` (`name`,`email`,`password`) VALUES('$name','$email','$password')";
        if (mysqli_query($conn, $query)) {
            $uid = mysqli_insert_id($conn);
            echo json_encode(array("status" => 200, "response" => "register-success"));
            session_start();
            $_SESSION["login"] = array("uid"=>$uid,"email" => "$email", "name" => "$name");
        } else {
            echo json_encode(array("status" => 400, "response" => "register-failed"));
        }
    }
}
