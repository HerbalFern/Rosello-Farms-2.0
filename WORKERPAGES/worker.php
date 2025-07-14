<?php 
  include 'workerheader.php';
  require_once '../includes/dbh.inc.php';
  $recent_sql = "SELECT * FROM announcements WHERE type = 'employee' ORDER BY announce_date DESC LIMIT 6";
  $r_result = mysqli_query($conn, $recent_sql);

?>

<div class="centerdiv">
  <br><br>

  <div class="announcementbox">
    
  

  <br>
  <h1>WORKER ANNOUNCEMENTS</h1>
  <div class="posts" style="overflow-y: scroll; height:400px; width: 95%; border: 8px solid black; padding:10px; border-radius: 10px; background-color:rgb(142, 245, 145);">
    <?php
      mysqli_data_seek($r_result, 0);   
      while ($row = mysqli_fetch_assoc($r_result)){
    ?>
      <div class="announcementblock">
        <h3><span style="color:rgb(68, 82, 69);">TITLE:</span> <?php echo $row['announcement_title']?></h3>
        <h3><span style="color:rgb(68, 82, 69);">DATE:</span> <?php echo $row['announce_date']?></h3>
        <h3><span style="color:rgb(68, 82, 69);">Author:</span> <?php echo $row['author']?></h3>
        <p class="announcement_text">
          <?php echo $row['announcement_content']; ?>
        </p>
      </div>
    <?php } ?>
  </div>
        <br><br>
  <div class="inventoryinput">
      <h1>CURRENT SALARY:</h1><br>
      <h2 style="text-align: center; color: green;">
        <?php 
          $salary = '';

          $salarysql = 'SELECT salary FROM employees WHERE userid = ?';

          $id = $_SESSION['userid'];

          $stmt = mysqli_stmt_init($conn);
          if (mysqli_stmt_prepare($stmt, $salarysql)) {
              mysqli_stmt_bind_param($stmt, "i", $id);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_bind_result($stmt, $salary);
              mysqli_stmt_fetch($stmt);
              mysqli_stmt_close($stmt);

              echo "₱" . number_format($salary, 2);
          } else {
              echo "Could not fetch salary.";
          }
        ?>
      </h2>
    </div>
    <br>
</div>
<br>
</div>
 <!-- Closing centerdiv -->
<br>

<?php 
  include 'workerfooter.php';
?>
