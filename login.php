<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require_once('tHeader.php'); ?>
	</head>
<body>
	<header>
		<?php require_once('navi.php'); ?>
			<div class="container black">
				<h3 class="header center white-text">Trendy Publishing</h3>
				 	<h4 style="font-family:tinyHands; src:('fonts/tinyHands.woff')" class="header center grey-text">Member Login</h4>
			</div>
	</header>
		<div class="parallax-container valign-wrapper">
 <div class="parallax"><img src="./timg/3.jpg" alt="background"></div>
  <div class="container">
    	<div class="section brown">
		
					<?php require_once('form2login.php'); ?>
 
				</div>
		</div>	


   <div class="section grey" id="login">
	<?php require_once('icons.php'); ?>
 </div>

<footer>
 	<div class="footer-copyright">
 		<?php include_once('footer.php'); ?>
	 </div>
</footer>

<?php include_once('js.php'); ?>
</body>
</html>