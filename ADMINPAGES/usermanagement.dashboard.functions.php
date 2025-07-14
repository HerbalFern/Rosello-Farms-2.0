<?php
    
    $message = "";
    $message2 = '';

    if (isset($_POST['disable']) && isset($_POST['identify'])) {
        $id = (int) $_POST['disable'];
        $currentStatus = $_POST['identify'];

        $newStatus = ($currentStatus === 'enabled') ? 'disabled' : 'enabled';

        $sql = "UPDATE users SET userstatus = ? WHERE userid = ?";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $message = "ERROR: Could not update user. PHP ERROR: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "si", $newStatus, $id);

            if (mysqli_stmt_execute($stmt)) {
                $message = "Successfully " . $newStatus . " user.";
            } else {
                $message = "ERROR: Could not update user. PHP ERROR: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_POST['update_salary'])){
        $id = $_POST['id'];
        $salary = $_POST['salary'];

        $sql = 'UPDATE employees SET salary = ? WHERE employee_id = ?';
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){
            $message2 = "Could Not Update Salary. PHP ERROR" . mysqli_stmt_error($stmt);
        } else {
            mysqli_stmt_bind_param($stmt, 'di', $salary, $id);
            if (mysqli_stmt_execute($stmt)) {
                $message2 = "Salary Updated";
            } else {
                $message2 = "Salary cannot be updated. PHP ERROR: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
    }
