<?php

require "../conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = password_hash($_POST["password"],PASSWORD_DEFAULT);

    $query = "SELECT uid,email,password,name FROM user WHERE email = '$email'";
    if ($response = mysqli_query($conn, $query)) {

        $result = mysqli_fetch_assoc($response);

        if (mysqli_num_rows($response) > 0) {

            $query = "UPDATE user SET password = '$password' WHERE email = '$email'";
            if(mysqli_query($conn,$query)){
                echo json_encode(array("status" => 200, "response" => "pass-change-success"));
            }else{
                echo json_encode(array("status" => 400, "response" => "query-execute-failed"));
            }
        
        } else {
            echo json_encode(array("status" => 409, "response" => "email-not-exist"));
        }
    } else {
        echo json_encode(array("status" => 400, "response" => "changes-failed"));
    }
}
