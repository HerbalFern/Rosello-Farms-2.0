<?php
    
    
    function isItemExists($itemname, $conn){
        $stmt = $conn->prepare("SELECT * FROM farminventory WHERE item_name = ?");
        
        if (!$stmt){
            error_log("error type shi (item name taken)");
            return false;
        }

        $stmt->bind_param("s", $itemname);
        $stmt->execute();
        $stmt->store_result();
        # derived from isusername taken;
        $isTaken = $stmt->num_rows > 0;
        $stmt->close();

        return $isTaken;
    }


    if (isset($_POST['create'])){
        $itemname = $_POST["item_name"];
        $quantity = $_POST["quantity"];
        $category = $_POST["category"];
        $unit = $_POST["unit"];

        $sql = "INSERT INTO farminventory (item_name, category, quantity, unit) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if(!isItemExists($itemname, $conn)){
            if (!mysqli_stmt_prepare($stmt, $sql)){
                $message = "ERROR: Could not add item in Inventory. PHP ERROR: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, "ssis", $itemname, $category, $quantity, $unit);
                
                if (mysqli_stmt_execute($stmt)) {
                    $message = "Successfully added Item in Inventory";
                } else {
                    $message = "ERROR: Could not add item in Inventory. PHP ERROR: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            $message = "ITEM ALREADY EXISTS, PLEASE USE THE UPDATE FEATURE";
            $errorZ = "yes";
        }
        $update = 0;
    }
    
    

    if (isset($_POST['update'])) {
        $itemname = $_POST["item_name"];
        $quantity = $_POST["quantity"];
        $category = $_POST["category"];
        $unit = $_POST["unit"];
        $id = $_POST["id"];
        
        if (!empty($itemname)){
            $sql = "UPDATE farminventory SET item_name = ?, category = ?, quantity = ?, unit = ? WHERE item_id = ?";
            $stmt = mysqli_stmt_init($conn);
            
            if (!isItemExists($itemname, $conn)) {
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    $message = "ERROR: Could not update item in Inventory. PHP ERROR: " . mysqli_error($conn);
                } else {
                    mysqli_stmt_bind_param($stmt, "ssisi", $itemname, $category, $quantity, $unit, $id);
        
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "Successfully updated item in Inventory";
                    } else {
                        $message = "ERROR: Could not update item in Inventory. PHP ERROR: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                $message = "Item Name taken. Please choose another item name";
                $errorZ = "yes";

            }
            $update = 1;

        } else {

            $sql = "UPDATE farminventory SET category = ?, quantity = ?, unit = ? WHERE item_id = ?";
            $stmt = mysqli_stmt_init($conn);
                

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                $message = "ERROR: Could not update item in Inventory. PHP ERROR: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, "sisi", $category, $quantity, $unit, $id);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "Successfully updated item in Inventory";
                } else {
                    $message = "ERROR: Could not update item in Inventory. PHP ERROR: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
    
    if (isset($_POST['delete'])) {
        $id = $_POST['delete_id'];
        $sql = "DELETE FROM farminventory WHERE item_id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $message = "ERROR: Could not update item in Inventory. PHP ERROR: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                $message = "Successfully deleted item in Inventory";
            } else {
                $message = "ERROR: Could not delete item in Inventory. PHP ERROR: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        
    }


    // for the sake of row printing and printing of choices. (drag drop thingy)
    $query = "SELECT * FROM farminventory";
    $dbresult = mysqli_query($conn, $query);
?>