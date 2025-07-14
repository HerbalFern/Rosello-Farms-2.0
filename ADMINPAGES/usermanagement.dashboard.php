<?php
    include '../ADMINPAGES/adminheader.php';
    require_once '../includes/dbh.inc.php';
    require_once '../ADMINPAGES/adminfunctions.inc.php';
    
    // Include our functions file
    require_once '../ADMINPAGES/usermanagement.dashboard.functions.php';

    $users = 'SELECT * FROM users';
    $userdata = mysqli_query($conn, $users);
    
    $employee = 'SELECT * FROM employees';
    $empdata = mysqli_query($conn, $employee);
?>

<div class="admin-main">
    <div>
        <aside class="adminsidebar">
            <h2>ADMIN FEATURES</h2>
                <nav class="adminsidebarlink">
                    <div class="sidebardiv"><a href="../ADMINPAGES/admin.php"><img src="../ADMINPAGES/adminicons/dashboard.png"> Main Dashboard</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/currinventory.dashboard.php"><img src="../ADMINPAGES/adminicons/inventory.png"> Current Inventory</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/shopinventory.dashboard.php"><img src="../ADMINPAGES/adminicons/shoppingcart.png"> Shopping Inventory</a></div>
                    <div class="sidebardiv"><a href="#"><img src="../ADMINPAGES/adminicons/usermanagement.png">User Management</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/statistics.dashboard.php"><img src="../ADMINPAGES/adminicons/statistics.png">Buyer Statistics</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/createannouncement.dashboard.php"><img src="../ADMINPAGES/adminicons/announcment.png">Create Announcements</a></div>
                </nav>
        </aside>
    </div>
        
    <div class="contentpanel">
        <div class='centerblock'>
            <h1 style="font-size: 55px;">USER MANAGEMENT SYSTEM</h1>

        </div>
        

        <div class="centerblock">
            <table>
                <tr>
                    <td style="background-color: #28A745;"><b>Name</b></td>
                    <td style="background-color: #28A745;"><b>Username</b></td>
                    <td style="background-color: #28A745;"><b>Email</b></td>
                    <td style="background-color: #28A745;"><b>Actions</b></td>
                </tr>

                <?php 
                mysqli_data_seek($userdata, 0);
                while ($row = mysqli_fetch_assoc($userdata)){
                if ($_SESSION['usertype'] === $row['usertype']){
                    continue;
                }
                ?>
                
                <tr>
                    <td><?php echo $row['first_name'] . $row['last_name']; ?></td>
                    <td><?php echo $row['username'] ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td class="action-buttons">
                        <form method="post" action="">
                            <input type="hidden" name="disable" value="<?php echo $row['userid']; ?>">
                            <input type="hidden" name="identify" value="<?php echo $row['userstatus']; ?>">
                            
                            <?php 
                                if ($row['userstatus'] === 'enabled') {
                                    echo '<button type="submit" class="redbutton">Disable</button>';
                                } else {
                                    echo '<button type="submit" class="greenbutton">Enable</button>';
                                }
                            ?>

                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
        
        <div class="centerblock" style="color: dark-green;"><br><p><b><?php echo $message; $message ="" ?></b></p></div>

        <div class='centerblock'>
            <h1 style="font-size: 55px;">EMPLOYEE SALARIES</h1>
        </div>
        
        <div class="centerblock">
            <div class="inventoryinput">
            <form style="text-align: left;" method="post">
                <label for="id">Salary to Update: &nbsp;</label>
                <select name="id" id="id" required>
                    <option value="" disabled selected hidden>Select...</option>
                    <?php
                        mysqli_data_seek($empdata, 0);
                        while ($row2 = mysqli_fetch_assoc($empdata)){
                            echo "<option value='" . $row2['employee_id'] . "'> ID: ". $row2['userid'] ." ||  USERNAME:" . $row2['username'] . " (". $row2['last_name']. ")</option>";
                        }
                    ?>
                </select>
                <br><br>
                <div>
                    <label for="salary">Salary (In PHP): &nbsp; ₱</label>
                    <input type="number" id="salary" name="salary" min="0.01" step="0.01" required>
                </div>
                <br>
                <div style="text-align: right;"> 
                    <button type="submit" class="greenbutton" name="update_salary">UPDATE</button>
                </div>
            </form>
            </div>
        </div>

        <div class="centerblock" style="color: dark-green;"><br><p><b><?php echo $message2; $message2 ="" ?></b></p></div>

    <br><br>
                    
       
    </div>
        


</div>

<?php
    require '../ADMINPAGES/adminfooter.php';
?>