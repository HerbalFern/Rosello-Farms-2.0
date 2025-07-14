<?php
// Cart Functions
function addToCart($conn, $userid, $item_id, $quantity, $item_unit) {
    // Check if item already exists in cart for this user
    $checkSql = "SELECT * FROM cart WHERE userid = ? AND shop_item_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $userid, $item_id);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Item exists, update quantity
        $row = mysqli_fetch_assoc($result);
        $newQuantity = $row['quantity'] + $quantity;
        
        $updateSql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ii", $newQuantity, $row['cart_id']);
        
        if (mysqli_stmt_execute($updateStmt)) {
            return true;
        } else {
            return false;
        }
    } else {
        // Item doesn't exist, insert new cart item
        $insertSql = "INSERT INTO cart (userid, shop_item_id, quantity, item_unit) VALUES (?, ?, ?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "iiis", $userid, $item_id, $quantity, $item_unit);
        
        if (mysqli_stmt_execute($insertStmt)) {
            return true;
        } else {
            return false;
        }
    }
}

function getCartItems($conn, $userid) {
    $sql = "SELECT c.cart_id, c.shop_item_id, c.quantity, c.item_unit, s.item_name, s.price, s.img_filepath, s.img_filename 
            FROM cart c 
            JOIN shoppinginventory s ON c.shop_item_id = s.shop_item_id 
            WHERE c.userid = ?
            ORDER BY c.added_on DESC";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $cartItems = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cartItems[] = $row;
    }
    
    return $cartItems;
}

function getCartTotal($cartItems) {
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function updateCartQuantity($conn, $cart_id, $quantity) {
    $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $cart_id);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

function removeCartItem($conn, $cart_id) {
    $sql = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $cart_id);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

// Transaction Functions
function processTransaction($conn, $userid, $cartItems, $contact_number) {
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    try {
        foreach ($cartItems as $item) {
            $sql = "INSERT INTO transactions (userid, shop_item_id, quantity, price_at_purchase, contact_number) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iiids", $userid, $item['shop_item_id'], $item['quantity'], $item['price'], $contact_number);
            mysqli_stmt_execute($stmt);
            
            $updateSql = "UPDATE shoppinginventory SET quantity = quantity - ? WHERE shop_item_id = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "ii", $item['quantity'], $item['shop_item_id']);
            mysqli_stmt_execute($updateStmt);
        }
        
        $clearSql = "DELETE FROM cart WHERE userid = ?";
        $clearStmt = mysqli_prepare($conn, $clearSql);
        mysqli_stmt_bind_param($clearStmt, "i", $userid);
        mysqli_stmt_execute($clearStmt);
        
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {

        mysqli_rollback($conn);
        return false;
    }
}

function getUserTransactions($conn, $userid) {
    $sql = "SELECT t.transaction_id, t.shop_item_id, s.item_name, t.quantity, t.price_at_purchase, 
            t.transaction_date, t.contact_number, s.unit, s.img_filepath, s.img_filename
            FROM transactions t
            JOIN shoppinginventory s ON t.shop_item_id = s.shop_item_id
            WHERE t.userid = ?
            ORDER BY t.transaction_date DESC";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $transactions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }
    
    return $transactions;
}


function showAlert($message, $type = 'success') {
    $color = ($type == 'success') ? '#28A745' : '#dc3545';
    echo '<div style="background-color: ' . $color . '; color: white; padding: 10px; margin: 10px; border-radius: 5px; text-align: center;">';
    echo $message;
    echo '</div>';
}