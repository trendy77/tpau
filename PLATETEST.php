<?php 
	$title = 'template index';
	$blurb = 'This is the template index.';
	$img1 = '1.jpg';
	$img2 = '2.jpg';
	$img3 = '3.jpg';
	$col = 'black';
	$col2 = 'green';
	$col3 = 'brown';
	$content = 'some type of content here....';
	$content2 = 's more content hereht here....';
	$tfit = 'tfit is equal to this...';
	$tfif = 'tfif is equal to this...';
	$tfit2 = 'tfit2 is equal to this...';
	$tfif2 = 'tfif2 is equal to this...';
	$loc = './timg/';
include('./templater.php');	
	tPlat($title,$blurb,$col3,$img3, $tfit2, $tfif2, $img1,$content, $content2,$tfit,$tfif,$col,$img2,$col2,$loc);
?>