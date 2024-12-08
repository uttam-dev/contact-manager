<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["login"])) {
    header("location:login.php");
} else {
    require "conn.php";

    $uid = $_SESSION["login"]["uid"];
    if (!isset($_GET["contact_id"])) {
        echo "Contact id not find.";
        exit;
    }

    $cid = base64_decode(urldecode($_GET["contact_id"]));
    $query = "SELECT * FROM uid_$uid WHERE cid=$cid";
    if ($res = mysqli_query($conn, $query)) {

        if (mysqli_num_rows($res) > 0) {
            $result = mysqli_fetch_assoc($res);
        } else {
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
    <title>Edit Contact</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/add-contact.css">
    <link rel="shortcut icon" href="images/Tv Icon.jpg" type="image/x-icon">
</head>

<body>
    <?php require "header.php" ?>

    <div class="wrapper">
        <div class="content-text">
            <p>Edit Contact</p>
        </div>

        <form method="POST" id="form" class="container">
            <input type="hidden" name="cid" value="<?php echo urldecode($_GET["contact_id"]); ?>">

            <div class="row">
                <label for="name">
                    <img src="images/person.png" alt="icon">
                    <span>*</span>Name
                </label>
                <input type="text" name="name" id="name" value="<?php echo $result["name"]; ?>">
                <p class="err-msg" id="errname"></p>
            </div>
            <div class="row">
                <label for="phone">
                    <img src="images/call.png" alt="icon">
                    <span>*</span>phone
                </label>
                <input type="number" id="phone" name="phone" value="<?php echo $result["phone"]; ?>">
                <p class="err-msg" id="errphone"></p>
            </div>
            <div class="row">
                <label for="profile">
                    <img src="images/photo.png" alt="icon">
                    profile piture
                </label>
                <div class="profile-content">
                    <input type="file" id="profile" name="profile" accept="image/*" onchange="preview_image(event)">
                    <p class="err-msg" id="errprofile"></p>
                    <div class="priview-profile">
                        <?php
                        if ($result["profile"] == "") {
                            echo "<img src='images\Tv Icon.jpg' id='profile-preview' alt=''>";
                        } else {
                            echo "<img src='images/profile/" . $result["profile"] . "' id='profile-preview' alt=''>";
                        }
                        ?>

                    </div>
                </div>
            </div>

            <div class="row">
                <label for="nickname">
                    <img src="images/person.png" alt="icon">
                    Nickname
                </label>
                <input type="text" id="nickname" name="nickname" value="<?php echo $result["nickname"]; ?>">
                <p class="err-msg" id="errnickname"></p>
            </div>
            <div class="row">
                <label for="company">
                    <img src="images/edit_note.png" alt="icon">
                    Company</label>
                <input type="text" id="company" name="company" value="<?php echo $result["company"]; ?>">
                <p class="err-msg" id="errcompany"></p>
            </div>
            <div class="row">
                <label for="email">
                    <img src="images/mail.png" alt="icon">
                    email</label>
                <input type="email" id="email" name="email" value="<?php echo $result["email"]; ?>">
                <p class="err-msg" id="erremail"></p>
            </div>
            <div class="row">
                <label for="parmenent-address">
                    <img src="images/location.png" alt="icon">
                    Parmenent Address</label>
                <input type="text" id="parmenent-address" name="parmenent-address" value="<?php echo $result["parmenent_address"]; ?>">
                <p class="err-msg" id="errparmenent-address"></p>
            </div>
            <div class="row">
                <label for="residential-address">
                    <img src="images/location.png" alt="icon">
                    Residential Address
                </label>
                <input type="text" id="residential-address" name="residential-address" value="<?php echo $result["residential_address"]; ?>">
                <p class="err-msg" id="errresidential-address"></p>
            </div>
            <div class="row">
                <label for="dob">
                    <img src="images/calendar.png" alt="icon">
                    Birth Of Date
                </label>
                <input type="date" id="dob" name="dob" value="<?php echo $result["dob"]; ?>">
                <p class="err-msg" id="errdob"></p>
            </div>
            <div class="row">
                <button type="submit" class="submit-btn">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function setNotifyPopup(text, hideTime = 1500) {
            let popup = document.getElementById("notify-popup");
            popup.innerHTML = text;
            popup.classList.add("active");
            setTimeout(() => {
                popup.innerHTML = "";
                popup.classList.remove("active");
            }, hideTime)
        }

        let imagePreview = document.getElementById("profile-preview");

        function preview_image(event) {
            let imagePreview = document.getElementById("profile-preview");
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const objectURL = URL.createObjectURL(file);
                imagePreview.src = objectURL;
            } else {
                imagePreview.src = 'images/Tv icon.jpg';
            }
        }

        function onUpdateContact() {
            let name = document.getElementById("name");
            let phone = document.getElementById("phone");
            let form = document.getElementById("form");
            let errname = document.getElementById("errname");
            let errphone = document.getElementById("errphone");

            let err = false;

            if (name.value.trim() == "") {
                errname.innerHTML = "name field cannot be empty";
                err = true;
            } else {
                errname.innerHTML = "";
            }

            if (phone.value.trim() == "") {
                errphone.innerHTML = "phone field cannot be empty";
                err = true;
            } else if (phone.value.trim().length != 10) {
                errphone.innerHTML = "phone number must be 10 digits";
                err = true;
            } else {
                errphone.innerHTML = "";
            }

            if (!err) {
                xhr = new XMLHttpRequest();

                xhr.open('POST', "action/update-contact-action.php", true);

                let data = new FormData(form);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {

                        try {
                            const json_data = JSON.parse(xhr.responseText);
                            if (json_data.status == 400 && json_data.response == "update-query-not-execute") {
                                setNotifyPopup("something wen't wrong");
                            } else if (json_data.status == 200 && json_data.response == "contact-update-success") {
                                setNotifyPopup("Contact updated successfully.", 3000);
                                setTimeout(()=>{
                                    window.location.href = "all-contacts";
                                },1500);
                            }
                        } catch (e) {
                            setNotifyPopup("Server response error.", 3000);
                        }
                    }
                }
                xhr.send(data);
            }
        }

        document.getElementById("form").addEventListener('submit', (e) => {
            e.preventDefault();
            onUpdateContact();
        })
    </script>

</body>

</html>