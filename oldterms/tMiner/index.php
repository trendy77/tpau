<?php

function createmineAd($adNum, $siteId){
	switch ($siteId) {
		case 'orgbiz':
		$key = "QVFq7tS52iTKY6UI7ErsuOuOdxKsVUHC";
		break;
		case 'fnr':
		$key = "ligbhZOyv127IeN0DGyddIQVFDRYVX16";
		break;
		case 'vape':
		$key = "6oVmUtLTtuPoKNidT1ci9licqM9JGYUD";
		break;
		default:
		$key = "QVFq7tS52iTKY6UI7ErsuOuOdxKsVUHC";
		break;
	}
	switch ($adNum) {
		case '1':
		$adw = "300";
		$adh = "250";
		break;
		case '2':
		$adw = "336";
		$adh = "280";
		break;
		case '3':
		$adw = "728";
		$adh = "90";
		break;
		default:
		$adw = "160";
		$adh = "600";
		break;
	}
echo '<div class="coinhive-miner" style="width: ' . $adw . 'px; height: ' . $adh . 'px"
	data-key="' . echo $key . '" data-autostart="true" data-user="trendy" data-whitelabel="true"><em>tMiner Commencing...</em></div>';
}
?>
<html>
<head>

<script src="https://authedmine.com/lib/simple-ui.min.js" async></script>


</head>
<body>

<section>
	<?php
	createmineAd("1","orgbiz");
	?>
</section>

<section>
	<?php
	createmineAd("2","orgbiz");
	?>
</section>

<section>
	<?php
	createmineAd("7","orgbiz");
	?>
</section>

<section>
	<?php
	createmineAd("3","orgbiz");
	?>
</section>



	<footer>


	</footer>

</body>
</html>
