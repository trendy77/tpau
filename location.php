<html>
<head>
<title>Facebook Location?</title>

<script src="//trendypublishing.com.au/js/location.js"></script>

<?php $tname = isset($_POST['tname']) ? trim($_POST['tname']): trim($_GET['tname']); 
      $tid = isset($_POST['tid']) ? trim($_POST['tid']) : trim($_GET['tid']); ?>

	<INPUT TYPE="hidden" ID="tname" VALUE="<?php echo($tname);?>">
	<INPUT TYPE="hidden" ID="tid" VALUE="<?php echo($tid);?>">
	<script src="//trendypublishing.com.au/js/location.js"></script>


	
<?php include '/top.php'; ?>

<div class="container" id="main">
      <div id="fb-root"></div>
      <div id="wrapper">
            <div class="container" id="friendslist">


            </div>
            <div class="container" id="buttons">
           <input class="btn" type="button" id="loginBtn" value="Get Permissions" onClick="loginFacebook();/>
      		<input class="btn" type="button" value="Get Friends' Locations" id="friendBtn"/>
      		<!-- the following div will show the status messages during the workflow of application-->
            </div>
            <div class="container" id="locResults">
      		<div id="status"></div>
      		<!-- shows the images loaded till now-->
      		<div id="count"></div>
      		<!-- holds all the friend's locations-->
      		<table id="location"></table>
            </div>
      </div>
</div>
<?php include '/bottom.php'; ?>
</body>
</html>
