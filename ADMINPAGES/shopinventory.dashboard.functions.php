<?php


$message = "";
$errorZ = "";
$update = 2;

// Define upload directory
$upload_dir = "../uploads/products/";

// Make sure the upload directory exists
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (isset($_POST['ADD'])) {
    $id = $_POST['id'];           
    $price = $_POST['price'];     
    $quantity = $_POST['quantity']; 
    $img_filepath = ""; 
    $img_filename = ""; 


    if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != "") {
        if ($_FILES['product_image']['error'] == 0) {

            $file_name = basename($_FILES["product_image"]["name"]);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            $unique_file = uniqid() . '_' . time() . '.' . $file_ext;
            $target_file = $upload_dir . $unique_file;
            
          
            $check = getimagesize($_FILES["product_image"]["tmp_name"]);
            if ($check === false) {
                $message = "Error: File is not an image.";
                $update = 1;
                $errorZ = "yes";
            
            } 
          
            else if ($_FILES["product_image"]["size"] > 5000000) {
                $message = "Error: File is too large. Maximum size is 5MB.";
                $update = 1;
                $errorZ = "yes";
               
            }

            else if (!in_array($file_ext, array("jpg", "jpeg", "png", "gif"))) {
                $message = "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
                $update = 1;
                $errorZ = "yes";
            }

            else if (!move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $message = "Error: There was an error uploading your file.";
                $update = 1;
                $errorZ = "yes";

            }
            else {

                $img_filepath = $upload_dir; 
                $img_filename = $unique_file; 
            }
        } else {
            $message = "Error uploading file: " . $_FILES["product_image"]["error"];
            $update = 1;
            $errorZ = "yes";
        }
    }

    // Only proceed with database operations if there was no error with file upload
    if ($errorZ != "yes") {
        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // First, fetch item data and check stock
            $fetchSql = "SELECT item_name, category, quantity, unit FROM farminventory WHERE item_id = ?";
            $fetchStmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($fetchStmt, $fetchSql)) {
                throw new Exception("Prepare statement failed: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($fetchStmt, "i", $id);
            mysqli_stmt_execute($fetchStmt);
            $result = mysqli_stmt_get_result($fetchStmt);

            if ($row = mysqli_fetch_assoc($result)) {
                if ($row['quantity'] >= $quantity) {
                    // Prepare data for insertion
                    $item_name = $row['item_name'];
                    $category = $row['category'];
                    $unit = $row['unit'];
                    
                    // Insert into shoppinginventory with correct columns
                    $insertSql = "INSERT INTO shoppinginventory (item_name, category, quantity, unit, price, img_filepath, img_filename) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)";
                    
                    $insertStmt = mysqli_stmt_init($conn);
                    
                    if (!mysqli_stmt_prepare($insertStmt, $insertSql)) {
                        throw new Exception("Insert prepare failed: " . mysqli_error($conn));
                    }
                    
                    // Bind parameters with the correct types
                    mysqli_stmt_bind_param($insertStmt, "ssisdss", 
                                          $item_name, 
                                          $category, 
                                          $quantity, 
                                          $unit, 
                                          $price,
                                          $img_filepath,
                                          $img_filename);
                                          
                    if (!mysqli_stmt_execute($insertStmt)) {
                        throw new Exception("Insert execution failed: " . mysqli_stmt_error($insertStmt));
                    }
                    
                    mysqli_stmt_close($insertStmt);
                    
                    // Now update the farm inventory to subtract quantity
                    $updateSql = "UPDATE farminventory SET quantity = quantity - ? WHERE item_id = ?";
                    $updateStmt = mysqli_stmt_init($conn);
                    
                    if (!mysqli_stmt_prepare($updateStmt, $updateSql)) {
                        throw new Exception("Update prepare failed: " . mysqli_error($conn));
                    }
                    
                    mysqli_stmt_bind_param($updateStmt, "ii", $quantity, $id);
                    
                    if (!mysqli_stmt_execute($updateStmt)) {
                        throw new Exception("Update execution failed: " . mysqli_stmt_error($updateStmt));
                    }
                    
                    mysqli_stmt_close($updateStmt);
                    
                    // Commit the transaction
                    mysqli_commit($conn);
                    
                    $message = "Item successfully moved to shopping inventory.";
                    $update = 1;
                    $errorZ = "no";
                } else {
                    mysqli_rollback($conn);
                    $message = "Error: Not enough quantity in farm inventory.";
                    $update = 1;
                    $errorZ = "yes";
                }
            } else {
                mysqli_rollback($conn);
                $message = "Error: Item ID not found.";
                $update = 1;
                $errorZ = "yes";
            }
            
            mysqli_stmt_close($fetchStmt);
            
        } catch (Exception $e) {
            // If an error occurred and we uploaded a file, delete it to avoid orphaned files
            if (!empty($img_filename) && file_exists($img_filepath . $img_filename)) {
                unlink($img_filepath . $img_filename);
            }
            
            mysqli_rollback($conn);
            $message = "Database error: " . $e->getMessage();
            $update = 1;
            $errorZ = "yes";
        }
    }
}

// Add delete functionality
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // First, get the image path to delete the image file if it exists
        $fetchSql = "SELECT img_filepath, img_filename FROM shoppinginventory WHERE shop_item_id = ?";
        $fetchStmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($fetchStmt, $fetchSql)) {
            throw new Exception("Fetch prepare failed: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($fetchStmt, "i", $delete_id);
        mysqli_stmt_execute($fetchStmt);
        $result = mysqli_stmt_get_result($fetchStmt);
        $row = mysqli_fetch_assoc($result);
        $img_filepath = $row['img_filepath'] ?? '';
        $img_filename = $row['img_filename'] ?? '';
        
        mysqli_stmt_close($fetchStmt);
        
        // Delete the item from shopping inventory
        $deleteSql = "DELETE FROM shoppinginventory WHERE shop_item_id = ?";
        $deleteStmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($deleteStmt, $deleteSql)) {
            throw new Exception("Delete prepare failed: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($deleteStmt, "i", $delete_id);
        
        if (!mysqli_stmt_execute($deleteStmt)) {
            throw new Exception("Delete execution failed: " . mysqli_stmt_error($deleteStmt));
        }
        
        mysqli_stmt_close($deleteStmt);
        
        // Commit the transaction
        mysqli_commit($conn);
        
        // Delete the image file if it exists
        if (!empty($img_filename) && !empty($img_filepath) && file_exists($img_filepath . $img_filename)) {
            unlink($img_filepath . $img_filename);
        }
        
        $message = "Item successfully removed from shopping inventory.";
        $update = 1;
        $errorZ = "no";
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $message = "Database error: " . $e->getMessage();
        $update = 1;
        $errorZ = "yes";
    }
}

// for the sake of row printing and printing of choices
$query = "SELECT * FROM farminventory";
$dbresult = mysqli_query($conn, $query);

$shopquery = "SELECT * FROM shoppinginventory";
$dbresultshop = mysqli_query($conn, $shopquery);
?>