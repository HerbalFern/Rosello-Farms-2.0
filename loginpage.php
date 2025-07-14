<?php 
  include 'includes/header.php';
?>



<div class="loginbox">
  
  <h1 class="bgreentext">Log-in</h1>
  <br>
  <form method="post" action="includes/login.inc.php" class="centeredform">
      <input class="inputbox" type="text" id="email" name="email" placeholder="Email" required><br><br>
      <input class="inputbox" type="password" id="pword" name="pword" placeholder="Password" required><br><br>
      
      
      <div style="text-align: left">
        &nbsp;
        <input type="checkbox" id="rememberme" value="isrememberme">
        <label style="font-size: 12px;" for="rememberme">Remember me</label> <br><br>
      </div>
              
        <button name="login" type="submit" class="greenbutton">Log-in</button>
        
      <br><br>
      
      
  </form>
  
  <div style="text-align: center;">
        <a style="text-align: center;" href="signup.php"><button class="greenbutton">Sign-Up</button></a>
        <br>
<!--
        <a class="linkbgreentext" href="#"><p style="font-size: 12.5px;">Forgot Password?</p></a>
-->
  </div>
</div>

<?php include 'includes/footer.php'; ?>
