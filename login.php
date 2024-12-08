<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/Tv Icon.jpg" type="image/x-icon">
</head>

<body>
    <?php require "header.php" ?>

    <div class="container">
        <form class="wrapper" method="POST">
            <p class="title-text">Login</p>
            <div class="input-fields">
                <label for="email">
                    email
                </label>
                <input type="email" id="email">
                <p class="err-msg" id="err-email"></p>
                <label for="password">
                    password
                </label>
                <input type="password" id="password">
                <p class="err-msg" id="err-password"></p>
            </div>
            <a href="forgot-pass" class="forgot-pass">Forgot password</a>

            <button type="submit" class="submit-btn">
                Login
            </button>
            <p class="redirect">Don't have an account? <a href="register.php">Register</a></p>
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

            let erremail = document.getElementById("err-email");
            let errpassword = document.getElementById("err-password");

            let err = false;

            if (email.value.trim() == "") {
                erremail.innerHTML = "email field cannot be emapty.please enter email.";
                err = true;
            } else {
                erremail.innerHTML = "";
            }

            if (password.value.trim() == "") {
                errpassword.innerHTML = "password field cannot be emapty.please enter password.";
                err = true;
            } else {
                errpassword.innerHTML = "";
            }

            if (!err) {

                let xhr = new XMLHttpRequest();

                xhr.open("POST", "action/login-action.php", true);

                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                const data = `email=${encodeURIComponent(email.value)}&password=${encodeURIComponent(password.value)}`;

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            try {
                                let json_data = JSON.parse(xhr.responseText);
                                if (json_data.status == 200 && json_data.response == "login-success") {
                                    setNotifyPopup("Login successfully", 1500);
                                    setTimeout(() => {
                                        window.location.href = "all-contacts";
                                    }, 1500)
                                } else if (json_data.status == 409 && json_data.response == "email-not-exist") {
                                    setNotifyPopup("email not exist.", 1500);
                                    erremail.innerHTML = "email not exist"
                                } else if (json_data.status == 409 && json_data.response == "password-not-match") {
                                    setNotifyPopup("password not match.", 1500);
                                    errpassword.innerHTML = "password not match"
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