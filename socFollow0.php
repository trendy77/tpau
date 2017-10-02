<?php
$sitez=array('@us','@spec','@style','@gov','@glo','@orgbizes','@tp','@vape','@fnr', '@ckww');
$slugz =array('organizebiz','amazonaustraliaoffers','womenstylechannel','govinfo','globetravelsearch','trendypublishin','vapedirectory','fakenewsregistry','customkits');

foreach($sitez as $site){
$cmd= 'data-href="https://www.facebook.com/' . $sites[$slug] .'"';
    echo '<div class="col s12 m6"><div class="fb-follow" ' . $cmd . ' data-layout="standard" data-size="small" data-show-faces="true"></div> <br><br>';
}
?>
