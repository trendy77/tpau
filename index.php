<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php include('header.php'); ?>
<body>
<header>

	<div class="parallax-container valign-wrapper">

		<nav class="grey darken-4" role="navigation">
			<div class="nav-wrapper">
			  <ul id="nav-mobile" class="right hide-on-med-and-down">
			 <li> <li class="active"><a class="white-text bold" href="#">HOME</a></li>   
			 <li><a class="white-text bold" href="#platforms"/>Platforms</a></li>       
			 <li><a class="white-text bold" href="terms/privacy.php"/>Media</a></li>  
			 <li><a class="white-text bold" href="advertise.php"/>Advertise</a></li>  
			 <li><a class="white-text bold" href="terms/index.php"/>Terms</a></li> 
			 <li><a class="white-text bold" href="index.php#contact"/>Contact</a></li>
			 <li><div class="fb-login-button" data-max-rows="2" data-size="large" data-button-type="login_with" data-show-faces="true" data-auto-logout-link="true" data-use-continue-as="false">Login with Facebook</div></li>
			 <li><button onclick="window.open('fb-messenger://share?link=' + encodeURIComponent(<?php echo $_SERVER['SELF']; ?>) + '&app_id=' + encodeURIComponent(<?php echo $FBAPPID; ?>));)">share Via Facebook Messenger</button></li>
			 </ul>
	</div>
   </nav>
  
</header>
 </div>
 
	<div class="parallax"><img src="./timg/2.jpg" alt="background img 1"></div>
		

		<div class="section no-pad-bot black">
				<h1 class="header center white-text">Trendy Publishing</h1>
				<br> <br>
				<h4 class="header center grey-text">Digital Engagement Tools</h4>	 
				<br>
			</div>
		</div>	
		<div class="container"> <div class="section">
 				<div class="row center">
					<div class="col m4 s12">
						<div class="icon-block">
							<h5 class="center deep-orange-text pulse">Target</h5>
								<a class="btn btn-floating btn-large deep-orange pulse"><i class="material-icons">video_library</i></a> 
								<p class="white-text">Pinpoint key demographics with mathematical precision.</p>
						</div>	
					</div>
						<div class="col m4 s12">
							<div class="icon-block">
									<h5 class="center red-text">Engage</h5>
									<a class="btn btn-floating btn-large red pulse"><i class="material-icons">radio</i></a>
									<p class="white-text">Push social content viral with engaged user-communities.</p>
								</div>
						</div>
							<div class="col m4 s12">
								<div class="icon-block">
								<h5 class="center blue-text">Amplify</h5>
								<a class="btn btn-floating btn-large blue pulse"><i class="material-icons">launch</i></a> 
								<p class="white-text">Leverage social media to exponential effect.</p>
								</div>
							</div> 	
	</div>

</div>


 <div class="container light black">
    <div class="row">
	<div class="col s1">
<img id="logo-container" type="image" src="https://orgmy.biz/tpau/logo.gif" height="45" width="45"></a></div><div class="col s1">
<img id="logo-container" type="image" src="https://orgmy.biz/orgbiz/logo.gif" height="90" width="90"></a></div><div class="col s1">
 <img id="logo-container" type="image" src="https://orgmy.biz/fnr/logo.gif" height="90" width="90"></a></div><div class="col s1">
	<img id="logo-container" type="image" src="https://orgmy.biz/ckww/logo.gif" height="90" width="90"></div><div class="col s1">
	<img id="logo-container" type="image" src="https://orgmy.biz/vape/logo.gif" height="90" width="90"></a></div><div class="col s1">
	<img id="logo-container" type="image" src="https://orgmy.biz/glo/logo.gif" height="90" width="90"></a></div><div class="col s1">
  <img id="logo-container" type="image" src="https://orgmy.biz/glo/window.gif" height="90" width="90"></a></div><div class="col s1"> 
<img id="logo-container" type="image" src="https://orgmy.biz/gov/logo.gif" height="90" width="90"></a></div><div class="col s1">
 <img id="logo-container" type="image" src="https://orgmy.biz/ama/logo.gif" height="90" width="90"></a></div><div class="col s1">
	<img id="logo-container" type="image" src="https://orgmy.biz/logowom.gif" height="90" width="90"></a></div><div class="col s1">
	<img id="logo-container" type="image" src="https://orgmy.biz/dj/logo.gif" height="90" width="90"></a></div><div class="col s1">
	<img id="logo-container" type="image" src="https://orgmy.biz/logosty.gif" height="90" width="90"></a></div><div class="col s1">
  <img id="logo-container" type="image" src="https://orgmy.biz/glo/road.gif" height="90" width="90"></a></div><div class="col s1">
  <img id="logo-container" type="image" src="https://orgmy.biz/tp/logo.gif" height="45" width="45"></a>
  </div>
		</div>
	</div>
	</div>
	</div>
	</div>
	<?php	include_once('contact.php');	 //include('fbFeed.php'); ?>
	
</main>
<footer>
 <div class="footer-copyright">
  <?php include_once('footer.php'); ?>
 </div>
</footer>
<?php include_once('js.php'); ?>
 </body>
</html>