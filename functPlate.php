<?php



function tHead($title,$blurb){
	echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
include ('header.php');
echo ' </head><body><header>';
include ('navi.php');
echo '<div class="parallax-container valign-wrapper">
   		<div class="parallax"><img src="/timg/9.jpg" alt="lib"></div>
				<div class="container black">
					<div class="section no-pad-bot black">
					<h1 class="header center white-text">' . $title . '</h1>
				 <br><br>
					<h4 style="font-family:tinyHands; src:("fonts/tinyHands.woff")" class="header center grey-text">'.$blurb.'</h4>
				 <br><br>
				 </div>
			</div>
		</div>
	</header>
	<main>
<div class="section">
<div class="container black">
  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">';
}	

function tMain($content,$tfit,$tfif)
{		
	echo '</div>
      </div>
    </div>
    <div class="parallax"><img src="timg/b.jpg" alt="bottom"></div>
  </div>
  <div class="container">
    <div class="section">
      <div class="row">
        <div class="col s12 center">
          <h3><i class="mdi-content-send brown-text"></i></h3>
       	   <h4>Contact Us</h4>
          <p class="left-align light">'.$content .'</p>
		  </div>
      </div>
    </div>
  </div>
  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
       
	   <h5 class="header col s12 light">'.$tfit.'</h5>
		  <iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ss&ref=as_ss_li_til&ad_type=product_link&tracking_id=trendypublish-20&marketplace=amazon&region=US&placement=B071KGRXRG&asins=B071KGRXRG&linkId=5843ad1e580c605eeb6e3c518802cc8c&show_border=true&link_opens_in_new_window=true"></iframe>
'. $tfif . '</div>
      </div>
    </div>';
}

function tFoot()
{
  echo '<footer class="container teal" id="footer">
    <div class="footer-copyright">
      <div class="container">';
        include('footer.php');	
	    echo '</div>
    </div>';
	include_once('js.php');
	echo '</body></html>';
}