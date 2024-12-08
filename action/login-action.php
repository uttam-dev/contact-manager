<?php

require "../conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT uid,email,password,name FROM user WHERE email = '$email'";
    if ($response = mysqli_query($conn, $query)) {

        $result = mysqli_fetch_assoc($response);

        if (mysqli_num_rows($response) > 0) {
            if (!password_verify($password, $result["password"])) {
                echo json_encode(array("status" => 409, "response" => "password-not-match"));
            } else {
                session_start();
                $_SESSION["login"] = array("uid"=>$result["uid"],"email" => $result["email"],"name"=> $result["name"]);
                echo json_encode(array("status" => 200, "response" => "login-success"));
            }
        } else {
            echo json_encode(array("status" => 409, "response" => "email-not-exist"));
        }
    } else {
        echo json_encode(array("status" => 400, "response" => "login-failed"));
    }
}
