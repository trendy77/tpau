<!DOCTYPE html>
<html lang="en">
	<head>
	   		<?php include('header.php'); ?>
	</head>
<body>
		<header>
		<?php include('navi.php'); ?>
		<div class="parallax-container valign-wrapper">
   		<div class="parallax"><img src="./timg/2.jpg" alt="background img 1"></div>
				<div class="container">
					<div class="section no-pad-bot black">
					<h1 class="header center white-text">Trendy Publishing</h1>
					<h4 class="header center grey-text">Digital Engagement Tools</h4>
				</div>
			</div>
		</div>
	</header>

<section>
  <div class="container white">
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
							<h5 class="center deep-orange-text pulse">Target</h5>
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

	<div id="index-banner" class="parallax-container">
		 <div class="parallax"><img src="./timg/9.jpg" alt="b"></div>

		 <div class="section no-pad-bot">
		 			<div class="container black">

	<?php include ('platforms.php'); ?>
			</div>
	 </div>

<div class="section no-pad-bot">
			<div class="container black">

		<?php include ('platforms2.php'); ?>

		 </div>
	</div>


	 	<section>
 			<div class="container light black">
					 <?php include ('phones.php'); ?>
 	  	</div>
		</section>

		<div class="section no-pad-bot">
     		  <div class="container black">
				<?php include ('socFollow.php'); ?>
				 </div>
 	   </div>
		<div class="section no-pad-bot">
						<div class="container black">
					<?php include ('socFollow0.php'); ?>
					 </div>
				</div>
			<div class="section no-pad-bot">
							<div class="container black">
						<?php include ('socLike.php'); ?>
						 </div>
					</div>

<?php	include_once('botTemp.php');	  ?>

<?php include_once('js.php'); ?>
</body>
</html>
