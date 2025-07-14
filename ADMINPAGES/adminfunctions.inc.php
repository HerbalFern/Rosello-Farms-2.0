<?php

    function countUsers ($conn) {
        $sql = "SELECT COUNT(userid) from users";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        return $count; 
    }

    function countNewUsers ($conn){
        $sql = "SELECT COUNT(userid) from users WHERE created_at >= NOW() - INTERVAL 7 DAY";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        return $count; 
    }

?>