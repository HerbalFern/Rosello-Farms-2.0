<?php require 'userheader.php'; 
    require_once 'user.functions.php';
    
    if (isset($_POST['cart'])) {

        if (!isset($_SESSION['userid'])) {
            echo '<script>alert("Please login to add items to cart"); window.location.href="../loginpage.php";</script>';
            exit();
        }
        
        // form ?????????
        $item_id = $_POST['id'];
        $quantity = intval($_POST['qty']);
        
        // code to get item from db typeshit
        $unitSql = "SELECT unit FROM shoppinginventory WHERE shop_item_id = ?";
        $unitStmt = mysqli_prepare($conn, $unitSql);
        mysqli_stmt_bind_param($unitStmt, "i", $item_id);
        mysqli_stmt_execute($unitStmt);
        $unitResult = mysqli_stmt_get_result($unitStmt);
        $unitRow = mysqli_fetch_assoc($unitResult);
        $item_unit = $unitRow['unit'];
        
        // add to cart
        $result = addToCart($conn, $_SESSION['userid'], $item_id, $quantity, $item_unit);
        
        if ($result) {
            showAlert("Item added to cart successfully!");
        } else {
            showAlert("Failed to add item to cart.", "error");
        }
    }
    
    $shopsql = 'SELECT * FROM shoppinginventory';
    $items = mysqli_query($conn, $shopsql);
?>

<br>
<div class="shoppingbox" style="justify-content:center;">
    <div>
        <h1 style="text-align: center;">SHOPPING SYSTEM</h1>
        <!-- Add view cart button -->
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="cart.php" class="view-cart-btn">
                <button type="button" style="background-color: #28A745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    View Cart <img src="../mainfiles/blackcart.png" style="height: 15px; width: 15px; border: none; margin-left: 5px;">
                </button>
            </a>
        </div>
    </div>
   
    <div class="shopitems">

        <?php 
        mysqli_data_seek($items, 0);
        while ($row = mysqli_fetch_assoc($items)){
        ?>
        <div class="itemblock">
            <div>
                <?php echo '<img src="'. $row['img_filepath'] . $row['img_filename'] .'">'; ?>
            </div>
            <div>
                <h3 style="text-align: center;"><?php echo $row['item_name'] . " (" . $row['unit'] . ")" ?></h3>
                <p style="text-align: center; font-weight: bold;">₱<?php echo number_format($row['price'], 2); ?></p>
            </div>
            
            <div>
                <form method="POST" action="">
                    <?php 
                        if ($row['quantity'] == 0 || $row['quantity'] < 0 ){
                            echo '<input type="number" min="1" max="'. $row['quantity'] .'" disabled>';
                            echo '<p style="color:red; text-align: center;">SOLD OUT</p>'; 
                            echo '<div style="display: flex; justify-content: center; margin-top: 10px;">';
                            echo '<button type="submit" name="cart" style="display: flex; align-items: center; gap: 5px; padding: 6px 12px; font-size: 14px; cursor: pointer;" disabled>';
                            echo 'ADD TO CART';
                            echo '<img src="../mainfiles/greencart.png" style="height: 15px; width: 15px; border: none;">';
                            echo '</button>';
                            echo '</div>';           
                        
                        } else {

                            echo '<input type="hidden" name="id" value="'. $row['shop_item_id'] .'">';
                            echo '<div class="quantity-row">';
                            echo '<label for="qty">Quantity: </label>';
                            echo '<input type="number" name="qty" min="1" max="'. $row['quantity'] .'" value="1" required>';
                            echo '&nbsp<p class="stock-text"><i>Stock: '. $row['quantity'] .' </i></p>';
                            echo '</div><br>';
                            echo '<div style="display: flex; justify-content: center; margin-top: 10px;">';
                            echo '<button type="submit" name="cart" style="display: flex; align-items: center; gap: 5px; padding: 6px 12px; font-size: 14px; cursor: pointer;">';
                            echo 'ADD TO CART';
                            echo '<img src="../mainfiles/blackcart.png" style="height: 15px; width: 15px; border: none;">';
                            echo '</button>';
                            echo '</div>';           
                        }
                    ?>
                </form>
            </div>
        </div>
        <?php } ?>
        <br>
    </div>
    
</div>

<br>
<?php  require 'userfooter.php'; ?>