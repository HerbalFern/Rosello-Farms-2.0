<?php
    session_start();
    

    if ((!isset($_SESSION["user_id"]) || (!isset($_SESSION["email"]))) || $_SESSION["user_type_id"] !== 3){
        header('location: ../loginpage.php');
        exit();
    }


?>

<!-- HEADER PHP CUT-->
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosello Farms</title>
    <link rel="stylesheet" href="../ADMINPAGES/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>

    <body class="bodycolumn">
        <header class="mainheader">

            <div>
                <!--ADD an if statement on what link to echo-->             
                <a href="../ADMINPAGES/admin.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a> 
                <a href="../ADMINPAGES/admin.php" class="headerlink" style="color: #caf2cb;"> <h3>DASHBOARD</h3></a>
            </div> 

            <div>
                <a href="../includes/logout.inc.php" class="logoutlink"><h3>LOGOUT</h3></a>
            </div>

 

        </header>