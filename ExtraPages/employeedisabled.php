<?php
    session_start();
?>

<!-- HEADER PHP CUT-->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosello Farms</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../ExtraPages/extrapagestyle.css">
</head>

    <body class="bodycolumn">
            <header class="mainheader">

                <div>
                    <!--ADD an if statement on what link to echo-->             
                    <a href="../loginpage.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a> 
                    <a href="" class="headerlink"><h3>ANNOUNCEMENTS</h3></a>
                    
                </div>
                
                <div>
                    <a href="../includes/logout.inc.php" class="logoutlink"><h3>LOGOUT</h3></a>
                </div>

            </header>
   
   


        
        <div style="text-align: center;">
        
            <br>
            <div class="aboutmepagecentertext">
            <h1 class="headercentertext">ACCOUNT IS CURRENTLY AWAITING APPROVAL</h1>
            
        </div>
    </div>

    <br><br>

        <footer class="mainfooter">

        <h2 style="font-size: 20px;" >ROSELLO FARMS &copy;</h2>

        <!-- ECHO DIFFERENT THINGIES?? DO CHANGES on actual php file-->

        <a href="#" class="aboulink"><h2 style="font-size: 20px;">ABOUT US</h2></a>

            <div class="centerdiv">
                <h3>CONTACTS<br></h3>
                <p>09193204572<br></p>
                <p>fernafelrosello@gmail.com</p>
            </div>

        </footer>

    </body>

</html>
