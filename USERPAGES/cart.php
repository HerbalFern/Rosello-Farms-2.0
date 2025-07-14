<?php require 'userheader.php'; 
    require_once 'user.functions.php';
    
    // Check if user is logged in
    if (!isset($_SESSION['userid'])) {
        echo '<script>alert("Please login to view your cart"); window.location.href="../loginpage.php";</script>';
        exit();
    }
    
    // Handle remove from cart action
    if (isset($_POST['remove_item'])) {
        $cart_id = $_POST['cart_id'];
        if (removeCartItem($conn, $cart_id)) {
            showAlert("Item removed from cart successfully!");
        } else {
            showAlert("Failed to remove item from cart.", "error");
        }
    }
    
    // Handle update quantity action
    if (isset($_POST['update_qty'])) {
        $cart_id = $_POST['cart_id'];
        $new_qty = $_POST['new_qty'];
        if (updateCartQuantity($conn, $cart_id, $new_qty)) {
            showAlert("Cart updated successfully!");
        } else {
            showAlert("Failed to update cart.", "error");
        }
    }
    
    // Handle checkout action
    if (isset($_POST['checkout'])) {
        $contact_number = $_POST['contact_number'];
        $cartItems = getCartItems($conn, $_SESSION['userid']);
        
        if (empty($cartItems)) {
            showAlert("Your cart is empty. Add some items first.", "error");
        } else {
            if (processTransaction($conn, $_SESSION['userid'], $cartItems, $contact_number)) {
                showAlert("Your order has been placed successfully!");
            } else {
                showAlert("Failed to process order. Please try again.", "error");
            }
        }
    }
    
    // Get cart items
    $cartItems = getCartItems($conn, $_SESSION['userid']);
    $cartTotal = getCartTotal($cartItems);
?>

<br>
<div class="shoppingbox" style="justify-content:center;">
    <div>
        <h1 style="text-align: center;">YOUR CART</h1>
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="user.php" style="text-decoration: none;">
                <button type="button" style="background-color: #28A745; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                    Continue Shopping
                </button>
            </a>
            <a href="transactions.php" style="text-decoration: none; margin-left: 10px;">
                <button type="button" style="background-color: #28A745; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                    View Order History
                </button>
            </a>
        </div>
    </div>
    
    <?php if (empty($cartItems)): ?>
        <div style="text-align: center; padding: 30px;">
            <h2>Your cart is empty</h2>
            <p>Add some products to your cart to see them here.</p>
        </div>
    <?php else: ?>
        <div class="cart-container" style="width: 90%; margin: 0 auto;">
            <table style="width: 100%; border-collapse: collapse; background-color: #96f599; border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background-color: #28A745; color: white;">
                        <th style="padding: 12px; text-align: left;">Item</th>
                        <th style="padding: 12px; text-align: center;">Price</th>
                        <th style="padding: 12px; text-align: center;">Quantity</th>
                        <th style="padding: 12px; text-align: center;">Subtotal</th>
                        <th style="padding: 12px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 12px; display: flex; align-items: center;">
                                <img src="<?php echo $item['img_filepath'] . $item['img_filename']; ?>" style="width: 60px; height: 60px; border: 2px solid #28A745; border-radius: 5px; margin-right: 10px;">
                                <div>
                                    <h4 style="margin: 0;"><?php echo $item['item_name']; ?></h4>
                                    <p style="margin: 0; font-size: 14px;">Unit: <?php echo $item['item_unit']; ?></p>
                                </div>
                            </td>
                            <td style="padding: 12px; text-align: center;">₱<?php echo number_format($item['price'], 2); ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <form method="POST" action="" style="display: flex; align-items: center; justify-content: center;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <input type="number" name="new_qty" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px; padding: 5px; text-align: center;">
                                    &nbsp;<button type="submit" name="update_qty" class="greenbutton">Update</button>
                                </form>
                            </td>
                            <td style="padding: 12px; text-align: center; font-weight: bold;">
                                ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <form method="POST" action="">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" name="remove_item" class="redbutton">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #e8ffe8;">
                        <td colspan="3" style="padding: 12px; text-align: right; font-weight: bold;">Total:</td>
                        <td style="padding: 12px; text-align: center; font-weight: bold; font-size: 18px;">₱<?php echo number_format($cartTotal, 2); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
            <div style="margin-top: 30px; background-color: #96f599; padding: 20px; border-radius: 10px; border: 3px solid black;">
                <h2 style="text-align: center; margin-top: 0;">Checkout</h2>
                <form method="POST" action="">
                    <div style="margin-bottom: 15px; display: flex; flex-direction: column; align-items: center;">
                        <label for="contact_number" style="font-weight: bold; margin-bottom: 5px;">Contact Number:</label>
                        <input type="text" name="contact_number" id="contact_number" required style="padding: 8px; width: 250px; border-radius: 5px; border: 1px solid #ccc;">
                    </div>
                    <div style="text-align: center;">
                        <button type="submit" name="checkout" style="background-color: #28A745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<br>
<?php require 'userfooter.php'; ?>