<?php 
  include 'includes/header.php';
?>

<br><br> <!-- this fixes the weird ass thing where it doesnt center from the top (band-aid ahh)-->
<div class="mainthing">
    <div class="signup">
        <h1 class="bgreentext">Sign Up</h1>
        
        <form method="post" action="includes/signup.inc.php" class="centeredform">
            
            
            <input class="signupbox" type="text" id="fullname" name="fullname" placeholder="Full Name" required><br><br>
            <input class="signupbox" type="email" id="Email" name="email" placeholder="Email" required><br><br>
            <input class="signupbox" type="password" id="pword" name="pword" placeholder="Password" required><br><br>
            <input class="signupbox" type="password" id="rpword" name="repeatpword" placeholder="Repeat Password" required><br><br>
            
<!-- Just in case you see this code, i did some sstuff to make contact details part cleaner with some JS stuff i copied. -->

            <label class="usertypetext" for="customer">Customer</label>
            <input type="radio" value="customer" name="usertype" onclick="ContactDetails()" required> &nbsp;&nbsp;&nbsp;
            <label class="usertypetext" for="Employee">Employee</label>
            <input type="radio" value="personnel" name="usertype" onclick="ContactDetails()" required>


            <div id='contact_details' style="display: none;">
                <br>
                <input class="signupbox" name='contact_details' placeholder="Contact No."> 
            </div>

            <script>
                function ContactDetails(){
                    const selected = document.querySelector('input[name="usertype"]:checked').value;
                    const extraForm  = document.getElementById('contact_details');
                    
                    extraForm.style.display = (selected === 'customer') ? 'block' : 'none';
                }
            </script> 

            <?php 
                if (!isset($_SESSION['success']) && !isset($_SESSION['error'])){
                    echo '<br><br>';
                }
                
                if (isset($_SESSION['error'])) {
                    echo "<p style='color: red; margin: 0;'><br>{$_SESSION['error']}<br><br></p>";
                    unset($_SESSION['error']);
                }

                if (isset($_SESSION['success'])){
                    echo '<p style="color: green; margin: 0;"><br>Sign-Up Successful<br><br></p>';
                    unset($_SESSION['success']);
                }
                
            ?>
            
            <!-- add a type of user feature-->

            <button name="submit" type="submit" class="greenbutton">Sign Up</button>
            
            <div style="text-align: center;">
            <p class="alrsignedintext"><b>Already a Member? <a href="loginpage.php">Sign in</a></b></p>
        </div>

        </form>
        
    </div>
    
    <br>
</div>


<?php include 'includes/footer.php'; ?>
