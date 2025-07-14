<?php
    session_start();
    if (isset($_POST["submit"])){

        $fname = $_POST["fullname"];
        $email = $_POST["email"];
        $pword = $_POST["pword"];
        $repeatpword = $_POST["repeatpword"];
        
        if (isset($_POST["contact_details"])) {
            $contact = $_POST["contact_details"];
        } else {
            $contact = null;
        }
        $usertype = $_POST["usertype"];
        
        if ($usertype === 'personnel'){
            $userstatus = "empdisabled";
        }

        else {
            $userstatus = "enabled";
        }

        require_once 'functions.inc.php';
        require_once 'dbh.inc.php';

        if (isFormEmpty($fname, $email, $pword, $repeatpword)){
            $_SESSION['error'] = "Please fill out everything in the form";
            header("location: ../signup.php?error=formempty");
            exit();
        }

        if (!isPwdmatch($pword, $repeatpword)){
            $_SESSION['error'] = "Password does not match";
            header("location: ../signup.php?error=pwordnotmatch");
            exit();
        }
        
        if (!isPasswordValid($pword)){
            $_SESSION['error'] = "Password should be atleast 8 Characters";
            header("location: ../signup.php?error=passwordinvalid");
            exit();
        }

        if (!isEmailValid($email)){
            $_SESSION['error'] = "Email is invalid";
            header("location: ../signup.php?error=emailinvalid");
            exit();
        }
        
        if (isEmailTaken($conn, $email)) {
            $_SESSION['error'] = "Email is already in use";        
            header("location: ../signup.php?error=emailtaken");
            exit();
        }

        createuser($conn, $fname, $email, $pword, $usertype, $contact);
        
    
    } else {
        header("location: ../signup.php?error=signuperror");
        exit();
    }
