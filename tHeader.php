<?php 
/** html page already started, this is just the <header...
 * 
 **/
$IDENTIFIER = 'tpau';
$sitedes= 'Proven, cost-effective Social Media audience engagement. Utilizing proprietary behavioural algorithms and existing online communities we craft bespoke digital campaigns that offer an integrated way to drive content viral, garner viewer engagement exponentially improve campaign ROI.';
?>
<meta charset="UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
<!-- STYLESHEETS FOR MATERIALIZE -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css" />
		<link rel="stylesheet" href="./node_modules/animate.css/animate.min.css" />
        <link href="./css/style.css" type="text/css" rel="stylesheet" media="all" />
		<link rel="icon" href="./favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
	   	<link rel="pingback" href="https://trendypublishing.com/xmlrpc.php" />

	<!-- scripts -->
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" /></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js" /></script>
		
		<script src="./js/init.js"></script>

		<title>Trendy Publishing || Growth Hacking + Viral Media Engagement</title> 

		<link rel="terms" href="//trendypublishing.com.au/terms/index.php" />
		<link rel="privacy" href="//trendypublishing.com.au/terms/privacy.php" />
		<link rel="kik-icon" href="//trendypublishing.com.au/timg/favb.ico" />
		<link rel="manifest" href="./manifest.json" />
		<meta property="fb:app_id" 	content="1863943023885616" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta property="og:type" 		content="website" />
		<meta name="keywords" content="marketing, digital, social, viral"/>
       <meta name="description" content="Trendy Publishing" />
		<meta property="og:site_name" 	content="Trendy Publishing AU" />
		<meta property="og:url" 	content="https://trendypublishing.com.au" />
		<meta property="og:title"      content="Trendy Publishing" />
		<meta property="og:image"      content="https://trendypublishing.com.au/live/wide.jpg" />
		<meta property="og:locale" content="en_AU" />
		<meta name="twitter:site" content="@trendypublishin" />
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:image" content="trendypublishing.com.au/live/wide.jpg" />
		<meta name="twitter:description" content="viral content marketing" />
		<meta name="twitter:title" content="TrendyPublishingAU" />
		<meta name="application-name" content="https://trendypublishing.com/" />
		<meta name="msapplication-square70x70logo" content="./live/small.jpg" />
		<meta name="msapplication-square150x150logo" content="./live/medium.jpg" />
		<meta name="msapplication-wide310x150logo" content="./live/wide.jpg" />
		<meta name="msapplication-square310x310logo" content="./live/large.jpg" />
		<meta name="msapplication-TileColor" content="#ffffff" />
		<meta name="msapplication-notification" content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=https://trendypublishing.com/feed&amp;id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=https://trendypublishing.com/feed&amp;id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=https://trendypublishing.com/feed&amp;id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=https://trendypublishing.com/feed&amp;id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=https://trendypublishing.com/feed&amp;id=5; cycle=1" />
		<meta name="keywords" content="Growth Hacking, Social Media, Viral content, digital marketing, media, publishing" />
		<meta name="author" content="Trendy Publishing" />
		<meta name="description" content="viral Content marketing strategies" />
	
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-84079763-15', 'auto'); ga('require', 'displayfeatures'); ga('send', 'pageview');
	    function _gaLt(event) {
        /* If GA is blocked or not loaded, or not main|middle|touch click then don't track */
        if (!ga.hasOwnProperty("loaded") || ga.loaded != true || (event.which != 1 && event.which != 2)) {
            return;
        }
        var el = event.srcElement || event.target;
        /* Loop up the DOM tree through parent elements if clicked element is not a link (eg: an image inside a link) */
        while (el && (typeof el.tagName == 'undefined' || el.tagName.toLowerCase() != 'a' || !el.href)) {
            el = el.parentNode;
        }
        /* if a link with valid href has been clicked */
       if (el && el.href) {
            var link = el.href;
            /* Only if it is an external link */
            if (link.indexOf(location.host) == -1 && !link.match(/^javascript\:/i)) {
                /* Is actual target set and not _(self|parent|top)? */
                var target = (el.target && !el.target.match(/^_(self|parent|top)$/i)) ? el.target : false;
                /* Assume a target if Ctrl|shift|meta-click */
                if (event.ctrlKey || event.shiftKey || event.metaKey || event.which == 2) {
                    target = "_blank";
                }
                var hbrun = false; // tracker has not yet run
                /* HitCallback to open link in same window after tracker */
                var hitBack = function() {
                    /* run once only */
                    if (hbrun) return;
                    hbrun = true;
                    window.location.href = link;
                };
                if (target) { /* If target opens a new window then just track */
                    ga(
                        "send", "event", "Outgoing Links", link,
                        document.location.pathname + document.location.search
                    );
                } else { /* Prevent standard click, track then open */
                    event.preventDefault ? event.preventDefault() : event.returnValue = !1;
                    /* send event with callback */
                    ga(
                        "send", "event", "Outgoing Links", link,
                        document.location.pathname + document.location.search, {
                            "hitCallback": hitBack
                        }
                    );
                    /* Run hitCallback again if GA takes longer than 1 second */
                    setTimeout(hitBack, 1000);
                }
            }
        }
    }
    var _w = window;
    /* Use "click" if touchscreen device, else "mousedown" */
    var _gaLtEvt = ("ontouchstart" in _w) ? "click" : "mousedown";
    /* Attach the event to all clicks in the document after page has loaded */
    _w.addEventListener ? _w.addEventListener("load", function() {document.body.addEventListener(_gaLtEvt, _gaLt, !1)}, !1)
        : _w.attachEvent && _w.attachEvent("onload", function() {document.body.attachEvent("on" + _gaLtEvt, _gaLt)});
	</script>

	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-4943462589133750", enable_page_level_ads: true})</script>
