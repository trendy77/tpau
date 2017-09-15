<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
 <title>Facebook Login</title>
<!-- Include the Facebook Javascript API 
<script src="http://connect.facebook.net/en_US/all.js"></script>
-->
<!-- CSS  -->
		<link rel = "stylesheet" href = "https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
		<script type = "text/javascript" src = "https://code.jquery.com/jquery-2.1.1.min.js"></script>           
		<script src = "https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js">
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

		<link rel = "stylesheet" href ="https://trendypublishing.com.au/css/style.css">
		
		
		
		
<!-- Facebook Pixel Code -->
			<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','//connect.facebook.net/en_US/fbevents.js');
// Insert Your Custom Audience Pixel ID below. 
			fbq('init', '670792923081149');
			fbq('track', 'PageView');
			</script>
			 <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=670792923081149&ev=PageView&noscript=1"/></noscript>
<!-- Facebook Auto LOGIN on view -->
			<script>
			fbq('track', 'ViewContent', {
			content_name: 'autolog',
			content_type: 'product'
			 });
			fbq('track', 'ViewContent', {			// set to track view of 'tpau' site content
			  content_name: 'tpau',
			  content_type: 'site'
			 });


			 </script>
<!-- TRACKS TPAU SITE CONTENT VIEWS ---- ADDS EVENT LISTENER<button id="fbLogin">FB LOGIN</button> -->
		
			<!-- Add Pixel Events to the button's click handler -->
			<script type="text/javascript">
var element = document.getElementById('autolog');
// Then, set the event to be tracked when element is visible
// Note that second parameter is a function, not a function call
executeWhenElementIsVisible(element, function() {
  fbq('track', 'autolog');
});
			var button = document.getElementById('fbLogin');
			  button.addEventListener(
				'click', 
				function() { 
				  fbq('track', 'login', {
					content_name: 'tpau'
				   });          
				},
				false
			  );
			  
			  var button = button.addEventListener(
				'onkeypress', 
				function() { 
				  
				   });          
				},
				false
			  );
			  
			  
			</script>

			
			
	  <!-- Actions per % of page viewed -->		
			<script>
var executeWhenReachedPagePercentage = function(percentage, callback) {
  if (typeof percentage !== 'number') {
    console.error(
      'First parameter must be a number, got',
      typeof percentage,
      'instead',
    );
  }
  if (typeof callback !== 'function') {
    console.error(
      'Second parameter must be a function, got',
      typeof callback,
      'instead',
    );
  }
  function getDocumentLength() {
    var D = document;
    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    )
  }
  function popup(){
	alert("I am an alert box!");  
  }
  function getWindowLength() {
    return window.innerHeight || 
      (document.documentElement || document.body).clientHeight;
  }
  function getScrollableLength() {
    if (getDocumentLength() > getWindowLength()) {
      return getDocumentLength() - getWindowLength();
    } else {
      return 0;
    }
  }
  var scrollableLength = getScrollableLength();
  window.addEventListener("resize", function(){
    scrollableLength = getScrollableLength();
  }, false)
  function getCurrentScrolledLengthPosition() {
   return window.pageYOffset || 
     (document.documentElement || document.body.parentNode || document.body).scrollTop;
  }
  function getPercentageScrolled() {
    if (scrollableLength == 0) {
      return 100;
    } else {
      return getCurrentScrolledLengthPosition() / scrollableLength * 100;
    }
  }
  var executeCallback = (function() {
    var wasExecuted = false;
    return function() {
      if (!wasExecuted && getPercentageScrolled() > percentage) {
        wasExecuted = true;
        callback();
      }
    };
  })();
  if (getDocumentLength() == 0 ||
    (getWindowLength()/getDocumentLength() * 100 >= percentage)) {
    callback();
  } else {
    window.addEventListener('scroll', executeCallback, false);
  }
};
executeWhenReachedPagePercentage(75, function() {
  popup();
});
</script>		



</head>


<body>
<!-- Facebook SDK - async - and FB LOGIN check ---- to be added after opening <body> tag....-->
<script>
   function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else {
      // The person is not logged into your app or we are unable to tell.
      document.getElementById('fbLogin').innerHTML = 'Please log ' + 'into this app.';
    }
  }
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '867691370038214',
    cookie     : true,  // enable cookies to allow the server to access 
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.10' // use graph api version 2.10
  }); 
  FB.AppEvents.logPageView();
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
  };
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
   function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      });
  }
</script>

<!--  NOW FINALLY SOME ACTUAL PAGE CONTENT!! -->

 <div class="parallax-container2">
      <div class="parallax"><img src="timg/sm1.jpeg"></div>
 </div>
	
<?php require_once('https://orgmy.biz/navi.php'); ?>

 <h1>Facebook Login</h1>
  

	<section>
		
		<div class="fbLogin">
		</div>

	</section>
  

  

  
	<section>
		<div class="fbLogin">
	</div>

</section>
  
  
  <div style="height: 120vh; width: 100vw; background-color: #00f;"></div>
 
 <h1 id="fb-fire-pixel">Lead event will fire when this phrase enters the screen</h1>
 
 
 <div style="height: 120vh; width: 100vw; background-color: #000;"></div>
  
  
  
<div id="status">
</div>


  <div style="height: 120vh; width: 100vw; background-color: #00f;"></div>

  <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
<h1 id="fb-fire-auto">If you see this you are being logged in....</h1>
</fb:login-button>
 
  <h1 id="fb-fire-pixel">Lead event will fire when this phrase enters the screen</h1>
  
  <div style="height: 120vh; width: 100vw; background-color: #000;"></div>




<?php insert_once('../js.php'); ?>
</body>
</html>