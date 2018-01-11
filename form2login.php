<?php
session_start();

$username = $_POST['name'];
$password = md5($_POST['pwd']);
$mysql_db_hostname = "localhost";
$mysql_db_user = "trendyp4";
$mysql_db_password = "trendypub11!";
$mysql_db_database = "test";
$con = mysql_connect($mysql_db_hostname, $mysql_db_user, $mysql_db_password)
        or die("Could not connect database");
mysql_select_db($mysql_db_database, $con) or die("Could not select database");

$query = "SELECT * FROM registered_users WHERE name='$username' AND password='$password'";
$result = mysql_query($query) or die(mysql_error());
$num_row = mysql_num_rows($result);
$row = mysql_fetch_array($result);
if ($num_row >= 1) {
        echo 'true';
        $_SESSION['user_name'] = $row['name'];
} else {
        echo 'false';
}
?>
<form action="login.php">
  <div class="imgcontainer">
    <img src="img_avatar2.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="user_name" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <button type="submit">Login</button>
    <input type="checkbox" checked="checked"> Remember me
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form> 

