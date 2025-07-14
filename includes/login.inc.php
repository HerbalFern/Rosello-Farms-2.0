<?php
session_start();
require_once 'functions.inc.php';
require_once 'dbh.inc.php';

    if (isset($_POST["login"])) {

        $email = $_POST["email"];
        $password = $_POST["pword"];
        
        if (isLoginEmpty($email, $password) !== false){
            header("location: ../loginpage.php?error=filloutallforms");
            exit();
        }

        loginUser($conn, $email, $password);

    } else {
        header('location: ../loginpage.php');
        exit();
    }
