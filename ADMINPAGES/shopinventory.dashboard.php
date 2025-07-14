<?php
    include '../ADMINPAGES/adminheader.php';
    require_once '../includes/dbh.inc.php';
    require_once '../ADMINPAGES/adminfunctions.inc.php';
    
    // Include our functions file
    require_once '../ADMINPAGES/shopinventory.dashboard.functions.php';
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
            <h1 style="font-size: 55px;">FARM SHOPPING SYSTEM</h1>
        </div>
            
        <div class='centerblock'>
            <div class='shopinput'>
                <div><h1>ADD ITEM TO SHOP</h1></div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div>
                    <label for="id">Item to Add from Inventory: &nbsp;</label>
                        <select name="id" id="id" required>
                            <option value="" disabled selected hidden>Select...</option>
                            <?php
                                mysqli_data_seek($dbresult, 0);
                                while ($row = mysqli_fetch_assoc($dbresult)){
                                    echo "<option value='" .$row['item_id'] . "'> ID: ". $row['item_id'] ." || ITEM NAME:" . $row['item_name'] . " || Amt. Left: ". $row['quantity']. "</option>";
                                }
                            ?>
                        </select>
                    </div>
                        <br><br>
                    <div>
                        <label for="price">Price (In PHP): &nbsp; ₱</label>
                        <input type="number" id="price" name="price" min="0.01" step="0.01" required>
                        
                        <label for="quantity">&nbsp;Quantity: &nbsp;</label>
                        <input type="number" id="quantity" style="width: 70px;" name="quantity" min="1" required>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <label for="product_image" class="custom-file-upload">Product Image: &nbsp;</label>
                        <input type="file" id="product_image" name="product_image" accept="image/*">
                        <small style="display: block; margin-top: 5px; color: #666;">Supported formats: JPG, JPEG, PNG, GIF (Max: 5MB)</small>
                    </div>
                        
                        <br><br>
                    <div style="text-align: right;">
                        <button type="submit" class="greenbuttonshop" name="ADD" value="1">UPDATE</button>
                    </div>
                </form>
                
                <?php
                    if (!empty($message) && $update === 1){
                        if ($errorZ === 'yes'){
                            echo '<p style="text-align: center; color: red;"><b>'. $message . '<br></b></p>';
                        } else {
                            echo '<p style="text-align: center;"><br>'. $message . '</p>';
                        }
                    } 
                ?>
            </div>
        </div>

        <div class='centerblock'>
            <h1 style="font-size: 45px;"><br>CURRENT SHOP ITEMS</h1>
        </div>
        
        <div class="centerblock">
            <table>
                <tr>
                    <td style="background-color: #28A745;"><b>SHOP ID</b></td>
                    <td style="background-color: #28A745;"><b>ITEM NAME</b></td>
                    <td style="background-color: #28A745;"><b>Quantity</b></td>
                    <td style="background-color: #28A745;"><b>Price</b></td>
                    <td style="background-color: #28A745;"><b>Image</b></td>
                    <td style="background-color: #28A745;"><b>Actions</b></td>
                </tr>
                <?php 
                mysqli_data_seek($dbresultshop, 0);
                while ($row = mysqli_fetch_assoc($dbresultshop)): 
                ?>
                <tr>
                    <td><?php echo $row['shop_item_id']; ?></td>
                    <td><?php echo $row['item_name'] . (isset($row['unit']) ? ' (' . $row['unit'] . ')' : ''); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>₱<?php echo number_format((float)$row['price'], 2); ?></td>
                    <td>
                        <?php if (!empty($row['img_filename'])): ?>
                            <img src="<?php echo $row['img_filepath'] . $row['img_filename']; ?>" alt="Product image" style="max-width: 50px; max-height: 50px;">
                        <?php else: ?>
                            <span>No image</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-buttons">
                        <form method="post" action="">
                            <input type="hidden" name="delete_id" value="<?php echo $row['shop_item_id']; ?>">
                            <button type="submit" name="delete" value="1" class="redbutton">Delete</button>
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