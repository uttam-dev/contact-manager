<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/Tv Icon.jpg" type="image/x-icon">
</head>

<body>
    <?php require "header.php" ?>

    <div class="container">
        <form class="wrapper" method="POST">
            <p class="title-text">Forgot Password</p>
            <div class="input-fields">
                <label for="email">
                    email
                </label>
                <input type="email" id="email">
                <p class="err-msg" id="err-email"></p>
                <label for="password">
                    new password
                </label>
                <input type="password" id="password">
                <p class="err-msg" id="err-password"></p>
                <label for="password">
                    confirm password
                </label>
                <input type="password" id="cpassword">
                <p class="err-msg" id="err-cpassword"></p>
            </div>
            <button type="submit" class="submit-btn">
                Save Changes
            </button>
            <p class="redirect">Don't habe an account? <a href="register">Register</a></p>
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

        function onLogin() {

            let email = document.getElementById("email");
            let password = document.getElementById("password");
            let cpassword = document.getElementById("cpassword");

            let erremail = document.getElementById("err-email");
            let errpassword = document.getElementById("err-password");
            let errcpassword = document.getElementById("err-cpassword");

            let err = false;

            if (email.value.trim() == "") {
                erremail.innerHTML = "email field cannot be emapty.please enter email.";
                err = true;
            } else {
                erremail.innerHTML = "";
            }

            if (password.value.trim() == "") {
                errpassword.innerHTML = "password field cannot be emapty.";
                err = true;
            } else if (password.value.length < 8 || password.value.length > 16) {
                errpassword.innerHTML = "password must be more then 8 and less then 16 characters.";
                err = true;
            } else {
                errpassword.innerHTML = "";
            }

            if (cpassword.value.trim() == "") {
                errcpassword.innerHTML = "password field cannot be emapty.";
                err = true;
            } else if (cpassword.value != password.value) {
                errcpassword.innerHTML = "confirm password not match with password field.";
                err = true;
            } else {
                errcpassword.innerHTML = "";
            }

            if (!err) {

                let xhr = new XMLHttpRequest();

                xhr.open("POST", "action/forgot-pass-action.php", true);

                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                const data = `email=${encodeURIComponent(email.value)}&password=${encodeURIComponent(password.value)}`;

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            try {
                                let json_data = JSON.parse(xhr.responseText);
                                if (json_data.status == 200 && json_data.response == "pass-change-success") {
                                    setNotifyPopup("password changed successfully", 1500);
                                    setTimeout(() => {
                                        window.location.href = "login";
                                    }, 1500)
                                } else if (json_data.status == 409 && json_data.response == "email-not-exist") {
                                    setNotifyPopup("email not exist.", 1500);
                                    erremail.innerHTML = "wrong email address."
                                } else {
                                    setNotifyPopup("Something went wrong with the request.", 3000);
                                }
                            } catch (e) {
                                setNotifyPopup("Server response error.", 3000);
                            }
                        } else if (xhr.status == 404) {
                            setNotifyPopup("404 - File Not Found.", 3000);
                        } else {
                            setNotifyPopup("Something went wrong with the request.", 3000);
                        }
                    }
                };
                xhr.send(data);
            }
        }

        document.getElementsByClassName("submit-btn")[0].addEventListener('click', e => {
            e.preventDefault();
            onLogin();
        })
    </script>

</body>

</html>