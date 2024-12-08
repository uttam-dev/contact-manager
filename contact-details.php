<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["login"])) {
    header("location:login.php");
} else {
    require "conn.php";

    $uid = $_SESSION["login"]["uid"];
    $cid = base64_decode(urldecode($_GET["contact_id"]));

    $query = "SELECT * FROM uid_$uid WHERE cid=$cid";
    if ($res = mysqli_query($conn, $query)) {

        if(mysqli_num_rows($res) > 0){
            $result = mysqli_fetch_assoc($res);
        }else{
            echo "Opps ! Something wen't wrong.";
            exit;
        }
    } else {
        echo "Opps ! Something wen't wrong.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Detail</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/add-contact.css">
    <link rel="shortcut icon" href="images/Tv Icon.jpg" type="image/x-icon">
    <style>
        input:active,
        input:focus {
            border: none;
            outline: none;
        }
    </style>
</head>

<body>
    <?php require "header.php" ?>

    <div class="wrapper">
        <div class="content-text">
            <p>Contact Details</p>
        </div>
        <div class="container">
            <?php if ($result["profile"] != "") { ?>
                <div class="row profile-img">
                    <img src="images/profile/<?php echo $result["profile"];?>" alt="icon" id="">
                </div>
            <?php } ?>

            <div class="row">
                <label for="name">
                    <img src="images/person.png" alt="icon">
                    Name
                </label>
                <input type="text" id="name" readonly value="<?php echo $result["name"];?>">
            </div>
            <div class="row">
                <label for="phone">
                    <img src="images/call.png" alt="icon">
                    phone
                </label>
                <input type="number" id="phone" readonly value="<?php echo $result["phone"];?>">
            </div>
            <div class="row">
                <label for="nickname">
                    <img src="images/person.png" alt="icon">
                    Nickname
                </label>
                <input type="text" id="nickname" readonly value="<?php echo $result["nickname"];?>">
            </div>
            <div class="row">
                <label for="company">
                    <img src="images/edit_note.png" alt="icon">
                    Company</label>
                <input type="text" id="company" readonly value="<?php echo $result["company"];?>">
            </div>
            <div class="row">
                <label for="email">
                    <img src="images/mail.png" alt="icon">
                    email</label>
                <input type="email" id="email" readonly value="<?php echo $result["email"];?>">
            </div>
            <div class="row">
                <label for="parmenent-address">
                    <img src="images/location.png" alt="icon">
                    Parmenent Address</label>
                <input type="text" id="parmenent-address" readonly value="<?php echo $result["parmenent_address"];?>">
            </div>
            <div class="row">
                <label for="residential-address">
                    <img src="images/location.png" alt="icon">
                    Residential Address
                </label>
                <input type="text" id="residential-address" readonly value="<?php echo $result["residential_address"];?>">
            </div>
            <div class="row">
                <label for="dob">
                    <img src="images/calendar.png" alt="icon">
                    Birth Of Date
                </label>
                <input type="date" id="dob" readonly value="<?php echo $result["dob"];?>">
            </div>
        </div>
    </div>
</body>

</html>