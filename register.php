<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/Tv Icon.jpg" type="image/x-icon">
</head>

<body>
    <?php require "header.php" ?>

    <div class="container">
        <form method="POST" class="wrapper">
            <p class="title-text">Register</p>
            <div class="input-fields">
                <label for="name">
                    name
                </label>
                <input type="text" id="name">
                <p class="err-msg" id="err-name"></p>

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

                <label for="cpassword">
                    confirm password
                </label>
                <input type="password" id="cpassword">
                <p class="err-msg" id="err-cpassword"></p>
            </div>

            <button type="submit" class="submit-btn">
                Register
            </button>
            <p class="redirect">Already have an account? <a href="login.php">Login</a></p>
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

        function onRegister() {

            let name = document.getElementById("name");
            let email = document.getElementById("email");
            let password = document.getElementById("password");
            let cpassword = document.getElementById("cpassword");

            let errname = document.getElementById("err-name");
            let erremail = document.getElementById("err-email");
            let errpassword = document.getElementById("err-password");

            const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            let errcpassword = document.getElementById("err-cpassword");

            let err = false;
            if (name.value.trim() == "") {
                errname.innerHTML = "name field cannot be emapty.please enter your name.";
                err = true;
            } else {
                errname.innerHTML = "";
            }

            if (email.value.trim() == "") {
                erremail.innerHTML = "email field cannot be emapty.please enter email.";
                err = true;
            } else if (!regex.test(email.value.trim())) {
                erremail.innerHTML = "enterd valid email address.";
                err = true;
            } else {
                erremail.innerHTML = "";
            }

            if (password.value.trim() == "") {
                errpassword.innerHTML = "password field cannot be emapty.please enter password.";
                err = true;
            } else if (password.value.length < 8 || password.value.length > 16) {
                errpassword.innerHTML = "password must be more then 8 and less then 16 characters.";
                err = true;
            } else {
                errpassword.innerHTML = "";
            }


            if (cpassword.value == "") {
                errcpassword.innerHTML = "confirm password field cannot be emapty. please enter password.";
                err = true;
            } else if (cpassword.value != password.value) {
                errcpassword.innerHTML = "confirm password not match with password.";
                err = true;
            } else {
                errcpassword.innerHTML = "";
            }

            if (!err) {
                let xhr = new XMLHttpRequest();

                xhr.open("POST", "action/register-action.php", true);

                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                const data = `name=${encodeURIComponent(name.value)}&email=${encodeURIComponent(email.value)}&password=${encodeURIComponent(password.value)}`;

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            try {
                                let json_data = JSON.parse(xhr.responseText);
                                if (json_data.status == 200) {
                                    setNotifyPopup("Register Successfully.", 1500);
                                    setTimeout(() => {
                                        window.location.href = "all-contacts";
                                    }, 1000);
                                } else if (json_data.status == 400) {
                                    setNotifyPopup("Something went wrong.", 3000);
                                } else if (json_data.status == 409) {
                                    setNotifyPopup("email address is already registered.", 3000);
                                    erremail.innerHTML = "email address is already registered";
                                } else {
                                    setNotifyPopup("Unexpected response status.", 3000);
                                }
                            } catch (e) {
                                console.error("Error parsing JSON response:", e);
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
            onRegister();
        })
    </script>
</body>

</html>