<script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=ddd0de63-477b-4d8a-a6aa-174f676a7651"></script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-4943462589133750", enable_page_level_ads: true});</script>

<?php if(!$UA){
  $UA = getSiteD($IDENTIFIER,'ua');
}
if(!$FBAPPID){
$FBAPPID = getSiteD($IDENTIFIER,'fbappid');
}
?>
<!-- Twitter Button -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
</script>
    <!-- Google Plus Button-->
<script src="https://apis.google.com/js/platform.js" async defer></script>
    <!--  Google Analytics  -->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', '<?php echo($UA); ?>', 'auto');
    ga('require', 'displayfeatures');
    ga('send', 'pageview');
</script>

<!-- kik API -->
<script src="http://cdn.kik.com/kik/1.0.0/kik.js"></script>
<!-- FB API -->
<script src="http://connect.facebook.net/en_US/all.js"></script>
