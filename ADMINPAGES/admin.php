<?php
    include '../ADMINPAGES/adminheader.php';
    require_once '../includes/dbh.inc.php';
    require_once '../ADMINPAGES/adminfunctions.inc.php'
?>

<div class="admin-main">
    <div>
        <aside class="adminsidebar">
            <h2>ADMIN FEATURES</h2>
                <nav class="adminsidebarlink">
                    <div class="sidebardiv"><a href="../ADMINPAGES/admin.php"><img src="../ADMINPAGES/adminicons/dashboard.png"> Main Dashboard</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/currinventory.dashboard.php"><img src="../ADMINPAGES/adminicons/inventory.png"> Current Inventory</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/shopinventory.dashboard.php"><img src="../ADMINPAGES/adminicons/shoppingcart.png"> Shopping Inventory</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/usermanagement.dashboard.php"><img src="../ADMINPAGES/adminicons/usermanagement.png">User Management</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/statistics.dashboard.php"><img src="../ADMINPAGES/adminicons/statistics.png">Buyer Statistics</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/createannouncement.dashboard.php"><img src="../ADMINPAGES/adminicons/announcment.png">Create Announcements</a></div>
                </nav>
            </aside>
    </div>
        
    <div class="contentpanel">
        <div class='centerblock'>
        <h1 style="font-size: 55px;"> WELCOME <?php echo $_SESSION['username']; ?></h1>
        </div>

        

        <div class="rowofgblock">
        <!-- ADD ITEMS IN FARM INVENTORY  -->
        <!-- ADD ITEMS IN SHOPPING INVENTORY  -->
            <div class="greenblock">
                
                <h2>TOTAL USERS</h2>
                <?php
                    $count = countUsers($conn);
                    echo '<p><b>'.$count.'</b></p>';
                ?>
            </div>

            <div class="greenblock">
                <h2>NEW USERS</h2>
                <?php
                    $count = countNewUsers($conn);
                    echo '<p><b>'.$count.'</b></p>';
                ?>
            </div> 
        </div>

    </div>

</div>

<?php
    require '../ADMINPAGES/adminfooter.php';
?>