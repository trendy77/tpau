<html>
<head>
<title>Facebook Location?</title>
<!-- Include the Facebook Javascript API -->
<script src="http://connect.facebook.net/en_US/all.js"></script>
<!-- Include the normal stylesheet-->
<link href="/css/style.css" rel="stylesheet" />
<!-- Include the Facebook Javascript API -->
<script src="//trendypublishing.com.au/js/location.js"></script>
<?php $target_name = isset($_POST['tname']) ? trim($_POST['tname']): trim($_GET['tname']); 
      $name = isset($_POST['name']) ? trim($_POST['name']) : trim($_GET['name']);script src="//trendypublishing.com.au/js/location.js"></script>
</head>
<body>
<?php include '/top.php'; ?>

<div class="container" id="main">
      <div id="fb-root"></div>
      <div id="wrapper">
            <div class="container" id="friendslist">


            </div>
            <div class="container" id="buttons">
                  <input class="btn" type="button" id="loginBtn" value="Get Permissions"/>
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
