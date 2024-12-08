<?php
require "../conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  session_start();

  $uid = $_SESSION["login"]["uid"];
  $name = mysqli_escape_string($conn, $_POST['name']);
  $phone = mysqli_escape_string($conn, $_POST['phone']);
  $nickname = mysqli_escape_string($conn, $_POST['nickname']);
  $company = mysqli_escape_string($conn, $_POST['company']);
  $email = mysqli_escape_string($conn, $_POST['email']);
  $residential_address = mysqli_escape_string($conn, $_POST['residential-address']);
  $parmenent_address = mysqli_escape_string($conn, $_POST['parmenent-address']);
  $dob = mysqli_escape_string($conn, $_POST['dob']);

  $img_name = "";

  if ($name == "" && $phone = "") {
    echo json_encode(array("status" => "400", "response" => "column-value-not-find"));
    exit();
  }

  $query = "CREATE TABLE IF NOT EXISTS uid_" . $uid . " (
    `cid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(50) NOT NULL,
    `phone` bigint(10) DEFAULT NULL,
    `profile` text DEFAULT NULL,
    `nickname` varchar(20) DEFAULT NULL,
    `company` varchar(30) DEFAULT NULL,
    `email` varchar(50) DEFAULT NULL,
    `residential_address` text DEFAULT NULL,
    `parmenent_address` text DEFAULT NULL,
    `dob` date DEFAULT NULL
    )";

  if (!mysqli_query($conn, $query)) {
    echo json_encode(array("status" => "400", "response" => "table-not-created"));
    exit();
  }

  if ($_FILES["profile"]["size"] > 0) {
    $extension = pathinfo($_FILES["profile"]["name"], PATHINFO_EXTENSION);
    $img_name = rand(10000000, 99999999) . "." . $extension;

    move_uploaded_file($_FILES["profile"]["tmp_name"], PROFILE_IMG_PATH . $img_name);
  }

  $query = "INSERT INTO `uid_$uid`
(`name`,`phone`,`profile`,`nickname`,`company`,`email`,`residential_address`,`parmenent_address`,`dob`)
 VALUES('$name','$phone','$img_name','$nickname','$company','$email','$residential_address','$parmenent_address','$dob')";

  if ($response = mysqli_query($conn, $query)) {
    echo json_encode(array("status" => "200", "response" => "contact-add-success"));
    if ($_FILES["profile"]["size"] > 0) {
      move_uploaded_file($_FILES["profile"]["tmp_name"], PROFILE_IMG_PATH . $img_name);
    }
  } else {
    echo json_encode(array("status" => "400", "response" => "insert-query-not-execute"));
  }
}
