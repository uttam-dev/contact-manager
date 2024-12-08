<?php

require "../conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $cid = $_POST["cid"];
    $uid = $_SESSION["login"]["uid"];

    $query = "SELECT `profile` FROM uid_$uid WHERE cid = $cid";
    $response = mysqli_query($conn, $query);
    $res = mysqli_fetch_assoc($response);

    $img = $res["profile"];

    $query = "DELETE FROM uid_$uid WHERE `cid` = $cid";

    if (mysqli_query($conn, $query)) {
        if($img != ""){
            unlink(PROFILE_IMG_PATH . $img);
        }
        echo json_encode(array("status" => 200, "response" => "record-delete-success"));
    } else {
        echo json_encode(array("status" => 400, "response" => "query-not-execute"));
    }
}
