<?php
    include '../ADMINPAGES/adminheader.php';
    require_once '../includes/dbh.inc.php';
    require_once '../ADMINPAGES/adminfunctions.inc.php';
    $message = "";
    $errorZ = "";
    $update = 2;
    require_once '../ADMINPAGES/currinventory.dashboard.functions.php';
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
                </nav>
        </aside>
    </div>
        
    <div class="contentpanel">
        <div class='centerblock'>
            <h1 style="font-size: 55px;">FARM INVENTORY SYSTEM</h1>
        </div>
            <!--HERE-->
            <div class="rowofgblock">
                <div class="inventoryinput">
                    <h1>ADD ITEM</h1><br>
                    <div style="display: flex;">
                    <form method="post">
                        <label for="item_name">Name of Item: &nbsp;</label>
                        <input type="text" name="item_name">
                        &nbsp;&nbsp;

                        <label for="quantity" >Quantity: &nbsp;</label>
                        <input type="number" style="width: 70px;" name="quantity" min="1" required>

                        <br><br>
                        <label for="category">Category: &nbsp;</label>
                        <select name="category" required>
                            <option value="" disabled selected hidden>Select...</option>
                            <option value="livestock">Livestock</option>
                            <option value="meat">Meat</option>
                            <option value="crops">Crops</option>
                            <option value="tools">Tools</option>
                            <option value="feed">Feed</option>
                            <option value="fertilizer">Fertilizer</option>
                            <option value="others">Others</option>
                        </select>

                        &nbsp;&nbsp;
                        <label for="unit">Unit: &nbsp;</label>
                        <select name="unit" required>
                            <option value="" disabled selected hidden>Select...</option>
                            <option value="head">Head</option>
                            <option value="pcs">Pieces</option>
                            <option value="kg">Kilograms</option>
                            <option value="mg">Milligrams</option>
                            <option value="g">Grams</option>
                            <option value="L">Liters</option>
                            <option value="ml">Milliliters</option>
                            <option value="sacks">Sacks</option>
                            <option value="units">Units</option>
                        </select>
                        <br><br><br><br><br>
                        <div style="text-align: right;">
                        <button type="submit" class="greenbutton" name="create">SUBMIT</button>
                        <?php
                            if (!empty($message) && $update === 0){
                                if ($errorZ === 'yes'){
                                    echo '<p style="text-align: center; color: red;"><br><b>'. $message . '</b></p>';
                                } else {
                                echo '<p style="text-align: center;"><br>'. $message . '</p>';
                                }
                                $message = "";
                                $update = 2;
                            } 
                        ?>
                        </div>
                    </form>
                    </div>
                </div>

                <div class="inventoryinput">
                    <h1>UPDATE ITEM</h1><br>
                    <div style="display: flex;">
                    <form method="post">

                        <label for="id">Item to Update: &nbsp;</label>
                        <select name="id" required>
                            <option value="" disabled selected hidden>Select...</option>
                            <?php
                                while ($row = mysqli_fetch_assoc($dbresult)){
                                    echo "<option value='" .$row['item_id'] . "'> ID: ". $row['item_id'] ." || ITEM NAME:" . $row['item_name'] ."</option>";
                                }
                            ?>
                        </select>
                        
                        <br><br>
                        <label for="item_name">Name of Item: &nbsp;</label>
                        <input type="text" name="item_name">
                            <br><br>
                        <label for="quantity" >Quantity: &nbsp;</label>
                        <input type="number" style="width: 70px;" name="quantity" min="1" required>

                        
                        <label for="category">Category: &nbsp;</label>
                        <select name="category" required>
                            <option value="" disabled selected hidden>Select...</option>
                            <option value="livestock">Livestock</option>
                            <option value="meat">Meat</option>
                            <option value="crops">Crops</option>
                            <option value="tools">Tools</option>
                            <option value="feed">Feed</option>
                            <option value="fertilizer">Fertilizer</option>
                            <option value="others">Others</option>
                        </select>

                        &nbsp;&nbsp;
                        <label for="unit">Unit: &nbsp;</label>
                        <select name="unit" required>
                            <option value="" disabled selected hidden>Select...</option>
                            <option value="head">Head</option>
                            <option value="pcs">Pieces</option>
                            <option value="kg">Kilograms</option>
                            <option value="mg">Milligrams</option>
                            <option value="g">Grams</option>
                            <option value="L">Liters</option>
                            <option value="ml">Milliliters</option>
                            <option value="sacks">Sacks</option>
                            <option value="units">Units</option>
                        </select>
                        <br><br>
                        <div style="text-align: right;">
                        <button type="submit" class="greenbutton" name="update">UPDATE</button>
                        
                        </div>
                        <?php
                            if (!empty($message) && $update === 1){
                                if ($errorZ === 'yes'){
                                    echo '<p style="text-align: center; color: red;"><b>'. $message . '<br></b></p>';
                                } else {
                                echo '<p style="text-align: center;"><br>'. $message . '</p>';
                                }
                                $message = "";
                                $update = 2;
                            } 
                        ?>
                    </form>
                    </div>
                </div>
            
            </div>   <!--HERE-->
        
        <br><br>
        
        <?php

            $query = "SELECT * FROM farminventory";
            $dbresult = mysqli_query($conn, $query);

        ?>

        <div class='centerblock'>
            <h1 style="font-size: 45px;">ALL INVENTORY ITEMS</h1>
        </div>
        
        <div class="centerblock">
            <table>
                <tr>
                    <td style="background-color: #28A745;"><b>ITEM ID</b></td>
                    <td style="background-color: #28A745;"><b>ITEM NAME</b></td>
                    <td style="background-color: #28A745;"><b>Quantity</b></td>
                    <td style="background-color: #28A745;"><b>...</b></td>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($dbresult)): ?>
                <tr>
                    <td><?php echo $row['item_id']; ?></td>
                    <td><?php echo $row['item_name'] . ' (' . $row['unit'] . ')'; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td class="action-buttons">
                        <form method="post" action="">
                            <input type="hidden" name="delete_id" value="<?php echo $row['item_id']; ?>">
                            <button type="submit" name="delete" class="redbutton">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>

    



</div>

<?php
    require '../ADMINPAGES/adminfooter.php';
?>