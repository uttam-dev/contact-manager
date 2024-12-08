<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["login"])) {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Contacts</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/all-contacts.css">
    <link rel="shortcut icon" href="images/Tv Icon.jpg" type="image/x-icon">
</head>

<body>
    <?php require "header.php"; ?>


    <a href="add-contact" class="add-contact-btn">
        <img src="images/add-contact.png" alt="">
    </a>

    <div class="wrapper">
        <div class="top-header-content">
            <div class="left-content">
                <p class="content-text">All Contacts</p>
            </div>
            <div class="right-content">
                <input type="text" id="search-input" placeholder="Search contects here..">
                <input type="button" id="search-btn" value="Search">
            </div>
        </div>

        <div class="main-content">
            <table id="main-table">
                <tr class="table-header">
                    <th></th>
                    <th>Name</th>
                    <th>Mobile No</th>
                    <th>Contact Details</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </table>
            <div class="pagination" id="pagination">
            </div>
        </div>
    </div>

    <script>
        // BOTTOM NOTIFY POPUP
        function setNotifyPopup(text, hideTime = 1500) {
            let popup = document.getElementById("notify-popup");
            popup.innerHTML = text;
            popup.classList.add("active");
            setTimeout(() => {
                popup.innerHTML = "";
                popup.classList.remove("active");
            }, hideTime)

        }


        // SEARCH BAR DEBOUNCING
        let timeout;
        document.getElementById("search-input").addEventListener('keyup', (e) => {

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                loadData(e.target.value.trim());
            }, 600);

        })


        // LOAD DATA
        function loadData(searchDatadata = "",page=1) {
            xhr = new XMLHttpRequest();
            xhr.open("POST", "action/read-contacts.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = () => {
                if (xhr.status == 200 && xhr.readyState == 4) {
                    data = JSON.parse(xhr.responseText);
                    if (data.status == 200) {
                        document.getElementById("main-table").innerHTML = data.response;
                        document.getElementById("pagination").innerHTML = data.pagination;
                        profileHandling();
                        paginationHandaling();
                    }
                }
            }
            let data = `search=${encodeURIComponent(searchDatadata)}&page=${page}`;

            xhr.send(data);
        }
        loadData();


        // PAGINATION HANDALING

       function paginationHandaling(){
            let pagination = document.querySelectorAll(".pagination a");

            pagination.forEach((e) => {
                e.addEventListener('click', (elem)=>{
                    elem.preventDefault();
                    loadData("",e.getAttribute("page"));
                    
                })
            })
        }
        

        // DELETE DATA
        function deleteData(cid) {

            cid = cid.split("cid_")[1];
            row = document.getElementById("row_" + cid);

            xhr = new XMLHttpRequest();

            xhr.open("POST", "action/delete-contact.php");

            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = () => {
                if (xhr.status == 200 && xhr.readyState == 4) {
                    let json_data = JSON.parse(xhr.response);
                    if (json_data.status == 200) {
                        if (row) {
                            row.remove();
                        }
                        setNotifyPopup("contact deleted", 3000);
                        modalHide();
                    }
                }
            }
            xhr.send("cid=" + encodeURIComponent(cid));
        }


        // NON IMAGE PROFILE HANDALING
        function profileHandling() {
            let profile = Array.from(document.getElementsByClassName("profile"));
            let name = Array.from(document.getElementsByClassName("name"));
            name.forEach((elem, index) => {
                let letter = elem.innerHTML.charAt(0).toUpperCase();
                if (!(profile[index].className.includes("img"))) {
                    profile[index].innerHTML = letter;
                }
            })
            shuffleColors();
            modalHandling();
        }


        // COLORS FOR NON IMAGE PROFILE 
        function shuffleColors() {
            var colors = ["#32CBFF", "#EF9CDA", "#89A1EF", "#47682C", "#839788", "#D36135", "#E6AA68", "#7180B9", "#157F1F", "#7E4E60", "#69306D", "#82204A", "#32CBFF", "#EF9CDA", "#89A1EF", "#47682C", "#839788", "#D36135", "#E6AA68", "#7180B9", "#157F1F", "#7E4E60", "#69306D", "#82204A"];
            var contentBlock = document.getElementsByClassName("profile");
            Array.from(contentBlock).forEach((e, index) => {
                if (!(contentBlock[index].className.includes("img"))) {
                    var randomColor = colors[Math.floor(Math.random() * colors.length)];
                    e.style.background = randomColor;
                }
            })
        }


        // MODAL HANDLING (DELETE)
        function modalHandling() {
            let elements = Array.from(document.querySelectorAll(".delete"))
            elements.forEach(event => {
                event.addEventListener('click', (e) => {
                    let modal = document.getElementById("modal");
                    modal.style.visibility = "visible";
                    modal.style.opacity = 1;
                    document.getElementsByClassName("btn btn-yes")[0].setAttribute("id", e.currentTarget.id);

                    Array.from(document.getElementsByClassName("close")).forEach((elem) => {
                        elem.addEventListener('click', event => {
                            let modal = document.getElementById("modal");
                            modal.style.visibility = "hidden";
                            modal.style.opacity = 0;
                        })
                    })
                });
            });
        }


        // MODAL HIDING 
        function modalHide() {
            let modal = document.getElementById("modal");
            modal.style.visibility = "hidden";
            modal.style.opacity = 0;

        }
    </script>
</body>

</html>