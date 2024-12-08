<?php
session_start();
if (isset($_SESSION['login'])) {
    header("location:all-contacts");
} else {
    header("location:login");
}
