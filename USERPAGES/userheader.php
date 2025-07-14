<?php
    session_start();
    require_once '../includes/dbh.inc.php';
    if ($_SESSION["user_type_id"] !== 1 || $_SESSION["account_status"] === 0 ){
        header('location: ../loginpage.php?error=unauthorizedaccess');
    } 
?>

<!-- HEADER PHP CUT-->
<!DOCTYPE html>
<html lang="en">

<head>
-
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosello Farms</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="user.css">
</head>

    <body class="bodycolumn">
        <header class="mainheader">

            <div>
                <!--DONEZO (?)-->       <!--ADD an if statement on what link to echo-->   
                <?php
                // if statements to go to admin dashboard / customer dashboard link. 
                if (isset($_SESSION["usertype"])){
                if ($_SESSION["usertype"] === "customer"){
                        echo '<a href="../USERPAGES/user.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a>';
                    } 
                }else {
                    echo '<a href="../loginpage.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a>';} ?>

                
                
                <div style="padding-right: 30px;">
                    <a href="../USERPAGES/user.php" style="color: #caf2cb;" class="headerlink"><h3>HOME</h3></a>
                </div>
                <a href="../ExtraPages/announcements.php" class="headerlink"><h3>ANNOUNCEMENTS</h3></a>
            </div>

            <div>
                <a href="../includes/logout.inc.php" class="logoutlink"><h3>LOGOUT</h3></a>
            </div>
            
            
            
        </header>

   