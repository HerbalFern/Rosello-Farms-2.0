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
<!--DONEZO (?)-->       <!--ADD an if statement on what link to echo-->   
                    <?php
                    // if statements to go to admin dashboard / customer dashboard link. 
                    if (isset($_SESSION["username"])){
                        if ($_SESSION["usertype"] === "admin"){
                            echo '<a href="../ADMINPAGES/admin.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a>';
                        } else if ($_SESSION["usertype"] === "employee"){
                            echo '<a href="../WORKERPAGES/worker.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a>';
                        } else if ($_SESSION["usertype"] === "customer"){
                            echo '<a href="../USERPAGES/user.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a>';
                        }

                    } else {
                        echo '<a href="../loginpage.php"><img src="../mainfiles/logohd.png" alt="homebutton"></a>';
                    } ?>

                    
                    <div style="padding-right: 30px;">
                        <?php
                            // if statements to go to admin dashboard / customer dashboard link. 
                            if (isset($_SESSION["username"])){
                            if ($_SESSION["usertype"] === "admin"){
                                echo '<a href="../ADMINPAGES/admin.php" class="headerlink"><h3>DASHBOARD</h3></a>';
                            } else if ($_SESSION["usertype"] === "employee"){
                                echo '<a href="../WORKERPAGES/worker.php" class="headerlink"><h3>HOME</h3></a>';
                            } else if ($_SESSION["usertype"] === "customer"){
                                echo '<a href="../USERPAGES/user.php" class="headerlink"><h3>HOME</h3></a>';
                            }
                         

                            } else {
                                echo '<a href="" class="linkbgreentext"><h3>SIGNUP</h3></a>';

                            }
                        ?>
                </div>
                <a href="announcements.php" class="headerlink"><h3>ANNOUNCEMENTS</h3></a>
                    
                </div>
              

                
                <div>
                    <a href="../includes/logout.inc.php" class="logoutlink"><h3>LOGOUT</h3></a>
                </div>
            </header>
   
   


        
        <div style="text-align: center;">
        
            <br>
            <div class="aboutmepagecentertext">
            <h1 class="headercentertext">ABOUT US</h1>
            <p>Welcome to Rosello Farms, where innovation meets tradition to cultivate the future 
                of sustainable agriculture. Nestled in the heart of fertile farmland, we are dedicated 
                to producing high-quality crops using standard technology and eco-friendly practices.</p>
                </p> <br>
                <h1 class="headercentertext">OUR MISSION</h1>
                
            <p>At Rosello Farms, our goal is simple: grow more with less. By integrating simple eco-friendly 
                technology,automated irrigation, and drone-assisted crop monitoring, we ensure that every harvest 
                is optimized for both yield and sustainability. We believe in reducing waste, conserving 
                resources, and delivering fresh, naturally grown produce to communities near and far.
            </p>
            
        </div>
    </div>

    <br><br>

        <footer class="mainfooter">

        <h2 style="font-size: 20px;" >ROSELLO FARMS &copy;</h2>

        <!-- ECHO DIFFERENT THINGIES?? DO CHANGES on actual php file-->

        <a href="#" class="aboulink" style="color: #caf2cb;"><h2 style="font-size: 20px;">ABOUT US</h2></a>

            <div class="centerdiv">
                <h3>CONTACTS<br></h3>
                <p>09193204572<br></p>
                <p>fernafelrosello@gmail.com</p>
            </div>

        </footer>

    </body>

</html>
