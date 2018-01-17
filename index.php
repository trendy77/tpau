<!DOCTYPE html>
<html lang="en">
	<head>
	<?php include('tHeader.php'); ?>
	</head>
<body>
	<header>
		<?php include('navi.php'); ?>
		<div class="parallax-container valign-wrapper">
   			<div class="parallax"><img src="./timg/2.jpg" alt="lib">
			</div>
				<div class="container black">
					<div class="section no-pad-bot black">
						<h1 class="header center white-text">Trendy Publishing</h1>
				 		<br><br>
						<h4 style="font-family:tinyHands; src:('./fonts/tinyHands.woff')" class="header center grey-text">Digital Engagement Tools</h4>
						<br><br>
					</div>
				</div>
		</div>
	</header>
	<section>
	<div class="container-brown">
	 	<div class="section black">
			<div class="row center">
				<div class="col m4 s12">
					<div class="icon-block">
								<h5 class="center green-text pulse">Target</h5>
								<a class="btn btn-floating btn-large green pulse"><i class="material-icons">search</i></a>
								<p class="white-text">Pinpoint key demographics within fragemented market segments using derivative behavioural analysis.</p>
					</div>
				</div>
				<div class="col m4 s12">
					<div class="icon-block">
							<h5 class="center deep-orange-text pulse">Segment</h5>
							<a class="btn btn-floating btn-large deep-orange pulse"><i class="material-icons">video_library</i></a>
							<p class="white-text">Pinpoint key demographics with mathematical precision.</p>
					</div>
				</div>
				<div class="col m4 s12">
					<div class="icon-block">
							<h5 class="center blue-text">Amplify</h5>
							<a class="btn btn-floating btn-large blue pulse"><i class="material-icons">supervisor_account</i></a>
							<p class="white-text">Leverage social media to expand your reach exponentially - do more with every dollar.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
  	<section>
	
		<div id="mainContentBanner" class="parallax-container">
			<div class="parallax"><img src="./timg/2.jpg" alt="b"></div>
		</div>
	
		<div class="container green-lighten-2" id="platforms">
			<?php include_once('./platforms.php'); ?>
		</div>
	
	</section>
	



		<footer>
		<?php include_once('./footer.php'); ?>
		</footer>
		<?php include_once('./js.php'); ?>
	</body>
</html>