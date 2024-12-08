
<?php if(session_status() == PHP_SESSION_NONE) { session_start(); }; ?>

<div class="header">
    <div class="nav">
        <p class="header-label"><a href="all-contacts"><img src="images/contacts_icon.png" alt="icon">Contact Manager</a></p>
    </div>
    <div class="login">
        <?php if(isset($_SESSION["login"])){
            echo '<a href="logout">Logout</a>';
        } else{
            echo '<a href="login">Login</a>';
        } ?>
        
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-body">

        <div class="cross close">&#x2716;</div>
        <h1 class="title">
            <img src="images/delete.png" alt="delete icon">
            Delete Contact?
        </h1>
        <p class="details">Are you sure you want to delete this contact ?</p>
        <div class="buttons">
            <button class=" btn btn-yes" onclick="deleteData(id)">Yes,i'm sure</button>
            <button class="btn btn-no close">No,cancel</button>
        </div>
    </div>
</div>

<div class="notify-popup" id="notify-popup">
    this is notify pop up
</div>