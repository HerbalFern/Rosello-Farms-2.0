<?php require 'userheader.php'; 
    require_once 'user.functions.php';
    
    // Check if user is logged in
    if (!isset($_SESSION['userid'])) {
        echo '<script>alert("Please login to view your transactions"); window.location.href="../loginpage.php";</script>';
        exit();
    }
    
    // Get user transactions
    $transactions = getUserTransactions($conn, $_SESSION['userid']);
?>

<br>
<div class="shoppingbox" style="justify-content:center;">
    <div>
        <h1 style="text-align: center;">ORDER HISTORY</h1>
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="user.php" style="text-decoration: none;">
                <button type="button" style="background-color: #28A745; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                    Continue Shopping
                </button>
            </a>
            <a href="cart.php" style="text-decoration: none; margin-left: 10px;">
                <button type="button" style="background-color: #28A745; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                    View Cart
                </button>
            </a>
        </div>
    </div>
    
    <?php if (empty($transactions)): ?>
        <div style="text-align: center; padding: 30px;">
            <h2>You haven't placed any orders yet</h2>
            <p>Your order history will appear here once you've made a purchase.</p>
        </div>
    <?php else: ?>
        <div class="transactions-container" style="width: 90%; margin: 0 auto;">
            <table style="width: 100%; background-color:rgb(171, 251, 173); border-radius: 7px; overflow: hidden; border: 3px solid black;">
                <thead>
                    <tr style="background-color:rgb(34, 135, 57); color: white;">
                        <th style="padding: 12px; text-align: left;">Order ID</th>
                        <th style="padding: 12px; text-align: left;">Item</th>
                        <th style="padding: 12px; text-align: center;">Quantity</th>
                        <th style="padding: 12px; text-align: center;">Price</th>
                        <th style="padding: 12px; text-align: center;">Total</th>
                        <th style="padding: 12px; text-align: center;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td style="padding: 12px;">#<?php echo $transaction['transaction_id']; ?></td>
                            <td style="padding: 12px; display: flex; align-items: center;">
                                <img src="<?php echo $transaction['img_filepath'] . $transaction['img_filename']; ?>" style="width: 50px; height: 50px; border: 2px solid #28A745; border-radius: 5px; margin-right: 10px;">
                                <div>
                                    <h4 style="margin: 0;"><?php echo $transaction['item_name']; ?></h4>
                                    <p style="margin: 0; font-size: 14px;">Unit: <?php echo $transaction['unit']; ?></p>
                                </div>
                            </td>
                            <td style="padding: 12px; text-align: center;"><?php echo $transaction['quantity']; ?></td>
                            <td style="padding: 12px; text-align: center;">₱<?php echo number_format($transaction['price_at_purchase'], 2); ?></td>
                            <td style="padding: 12px; text-align: center; font-weight: bold;">
                                ₱<?php echo number_format($transaction['price_at_purchase'] * $transaction['quantity'], 2); ?>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <?php echo date('M d, Y - h:i A', strtotime($transaction['transaction_date'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<br>
<?php require 'userfooter.php'; ?>