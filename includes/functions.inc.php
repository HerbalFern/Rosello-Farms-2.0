<?php

    # SIGNUP FUNCTIONS

    function isEmailValid($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false){
            return true;
        } else {
            return false;
        }
    }

    function isFormEmpty($fname, $email, $pword, $repeatpwrd){
        if(empty($fname) || empty($email) || empty($pword) || empty($repeatpwrd)){
            return true;
        }  else {
            return false;
        }

    }

    function isPasswordValid($pword){
        if (strlen($pword) < 8){
            return false;
        }
        return true;
    }

    function isPwdmatch($pword, $repeatpword){
        if ($pword !== $repeatpword){
            return false; 
        } else {
            return true;
        } 
    }

    function createuser($conn, $fname, $email, $pword, $usertype, $contact) {
        
        if ($usertype == "customer"){
            $usertypeid = 1;
            $userstatus = 1;
        }
        else if ($usertype == "personnel"){
            $usertypeid = 2;
            $userstatus = 0; // personnel are disabled by default
        }
        
        $sql = "INSERT INTO user (full_name, email, password, user_type_id, account_status) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }


        $hashedPwd = password_hash($pword, PASSWORD_DEFAULT);
        
        mysqli_stmt_bind_param($stmt, "sssii", $fname, $email, $hashedPwd, $usertypeid, $userstatus);
        mysqli_stmt_execute($stmt);

        $userid = $conn->insert_id; // get userid from connection 

        

        mysqli_stmt_close($stmt);
        $_SESSION['success'] = "Signed Up Successfully";

        if ($usertype == 'personnel') {
            $insert_employee = "INSERT INTO personnel (personnel_type, user_id) VALUES (?, ?)";
            $stmt2 = $conn->prepare($insert_employee);
            $stmt2->bind_param("si", $usertype, $userid);
            $stmt2->execute();
            $stmt2->close();
        }

        else {

            $insert_customer = "INSERT INTO customer (contact_info, user_id) VALUES (?, ?)";
            $stmt2 = $conn->prepare($insert_customer);
            $stmt2->bind_param("si", $contact, $userid);
            $stmt2->execute();
            $stmt2->close();
        }

        header("location: ../signup.php?error=none");
        exit();
    }




    function isEmailTaken($conn, $email) {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");

        if (!$stmt){
            error_log("error type shi (email taken)");
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
    
        $isTaken = $stmt->num_rows > 0;
        $stmt->close();

        return $isTaken;
    }

    # gotta make some UID functions yknow. 

    function isUserExist($conn, $email){
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../loginpage.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt); 

        if ($row = mysqli_fetch_assoc($resultData)){
            return $row;
        } else {
            $result = false;
            return $result;
        }
        mysqli_stmt_close($stmt);
    }


    
    # NEXT PART IS THE LOGIN FUNCTIONS (YAY):
    /*
        aight so we list down all the functions that we need for logins:
        - empty login input
        - login user function. 
            + apparently this is featureful so be sure to be ready to add alot of stuff.
    */
    
    function isLoginEmpty($email, $password) {

        if (empty($email) || empty($password)){            
           return true;
        } else {
            return false;
        }
    }

    function getUserByEmail($conn, $email) {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) return false;
    
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc() ?: false;
    }

    function getUserTypeString($conn, $user_type_id) {
        $sql = "SELECT user_type FROM user_type WHERE user_type_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_type_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_assoc()['user_type'] ?? 'Unknown';
    }



    function loginUser($conn, $email, $password) {
        $user = getUserByEmail($conn, $email);

        if (!$user || !password_verify($password, $user["password"])) {
            header("Location: ../loginpage.php?error=wronglogin");
            exit();
        }

        $user_id = $user["user_id"];
        $user_type_id = $user["user_type_id"];
        $account_status = $user["account_status"];
        $user_type = getUserTypeString($conn, $user_type_id);

        // Start session
        session_start();
        $_SESSION["user_id"] = $user_id;
        $_SESSION["email"] = $user["email"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["user_type"] = $user_type;
        $_SESSION["user_type_id"] = $user_type_id;
        $_SESSION["account_status"] = $account_status;

        // Redirect based on role and status
        if ($account_status == 0 && $user_type_id != 3) {
            // If not admin and account is disabled
            header("Location: ../ExtraPages/accountdisabled.php");
            exit();
        }
        
        switch ($user_type_id) {
            case 1: // Customer
                header("Location: ../USERPAGES/user.php");
                break;
            case 2: // Employee
                header("Location: ../WORKERPAGES/worker.php");
                break;
            case 3: // Admin
                header("Location: ../ADMINPAGES/admin.php");
                break;
            default:
                header("Location: ../loginpage.php?error=unknownrole");
        }

        exit();
}


/*
    function createuser($conn, $fname, $lname, $username, $email, $pword, $usertype, $userstatus){
        $sql = "INSERT INTO users (first_name, last_name, username, email, password, userstatus, usertype) VALUES (?, ?, ?, ?, ?, ? ,?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }

        $hashedPwd = password_hash($pword, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "sssssss", $fname, $lname, $username, $email, $hashedPwd, $usertype, $userstatus);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['success'] = "Signed Up Sucessfully";

        if ($usertype === 'employee') {
            $userid = '';
            $findID = "SELECT userid FROM users WHERE username = ?";
            $stmt1 = $conn->prepare($findID);
            $stmt1->bind_param("s", $username);
            $stmt1->execute();
            $stmt1->bind_result($userid);
            $stmt1->fetch();
            $stmt1->close();

            $default_salary = 0; 
            $insert_employee = "INSERT INTO employees (userid, salary) VALUES (?, ?)";
            $stmt2 = $conn->prepare($insert_employee);
            $stmt2->bind_param("id", $userid, $default_salary);
            $stmt2->execute();
        }

        header("location: ../signup.php?error=none");
        exit();

    }
*/

    # add a if username is taken function
    # still need to study this.
    # idk if this even works
    /*
        Okay so apparently the first line is an sql query (i still dont understand)
        bind param puts in the the username to the statement
        then execute
        then stores the result in the statement thingy
        
        if the result(how mahy rows with that username) returns more than 0 then it is true.
    */

/*
    function isNameTaken($fullname, $conn) {
        
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        
        if (!$stmt){
            error_log("error type shi (username taken)");
            return false;
        }

        $stmt->bind_param("s", $fullname);
        $stmt->execute();
        $stmt->store_result();
    

        # apparently safer method as it closes the statement but the boolean is already stored in a variable (?)
        $isTaken = $stmt->num_rows > 0;
        $stmt->close();

        return $isTaken;
    }
*/

