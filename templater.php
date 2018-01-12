<?php
	// FUNCTION TEMPLATING FOR SITE - 
		//	tPlat - run with all your variables for page
		//

	function tPlat($title, $blurb, $col3, $img3, $tfit2, $tfif2, $img1, $content, $content2, $tfit, $tfif, $col, $img2, $col2, $loc){
		// location of file
			if (!$loc) { 	$loc = './timg/';	};
		// colours
			if(!$col){ $col = 'black'; 	};
			if (!$col2) { $col2 = 'teal';	};
		// images	
			$limg1 = $loc . $img1;
			$limg2 = $loc . $img2;
			$limg3 = $loc . $img3;
		// title & desc
			if(!$title){ $title = 'TrendyPublishing';	};
			if (!$blurb) {	$blurb = '...';	};
		// content and other content
			if(!$tfit){ $tfit = '';};
			if(!$tfif){ $tfif = '';};
			if (!$tfit2) {	$tfit = '';};
			if (!$tfif2) {	$tfif = '';};
			// prepare it!
		tHead($title,$blurb, $limg1, $col);
		tMain($content,$tfit,$tfif,$col,$limg2);
		tMain2($content2, $tfit2, $tfif2, $col3, $limg3);
		tFoot($col2);
	}

	function tHead($title,$blurb, $img1){
		echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
		require_once('./tHeader.php');
		echo ' </head><body><header>';
		require_once('navi.php');
		echo '</header><div class="parallax-container valign-wrapper"><div class="parallax"><img src="./timg/' . $img1 . '" alt="p1"><div class="container"><div class="section no-pad-bot teal">
			<h3 class="header center white-text">' . $title . '</h3><br><h5 style="font-family:tinyHands; src:("fonts/tinyHands.woff")" class="header center grey-text">'.$blurb.'</h5><br><br>
			 </div></div></div>';
	}	

	function tMain($content,$tfit,$tfif,$col,$img2){		
		echo '<section class="container no-padding-red" id="main"><div class="parallax-container valign-wrapper"><div class="parallax"><img src="./timg/' . $img2 . '" alt="p2"><div class="container"><div class="row">
	<div class="col s12 center"><p class="left-align light">'.$content . '</p></div></div><div class="container"><div class="row center"><h5 class="header col s12 light">'.$tfit.'</h5>'. $tfif . '</div></div></section></main>';
	  }

function tMain2($content2, $tfit2, $tfif2, $col3, $img3)
{
	echo '<section class="container no-padding-red" id="main"><div class="parallax-container valign-wrapper"><div class="parallax"><img src="./timg/' . $img3 . '" alt="p2"><div class="container"><div class="row">
	<div class="col s12 center"><p class="left-align light">' . $content2 . '</p></div></div><div class="container"><div class="row center"><h5 class="header col s12 light">' . $tfit2 . '</h5>' . $tfif2 . '</div></div></section></main>';
}

function tFoot($col2){
	echo '<footer class="container ' . $col2 . '" id="footer"><div class="container">';		
	require_once('footer.php');	
	echo '</div></footer>';	
	include_once('js.php');
	echo '</body></html>';
}