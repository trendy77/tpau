    <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script>if (!window.jQuery) { document.write('<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"><\/script>'); }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script src="js/init.js"></script>


    <!-- Twitter Button -->
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    <!-- Google Plus Button-->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <!--  Google Analytics  -->
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', '<?php getSiteD('fbapid'); ?>', 'auto');
    ga('require', 'displayfeatures');
    ga('send', 'pageview');
    </script>

  <script>
  var vglnk = { key: 'db8e8b461e1b6dcc640b00494a7a95e9' };
  (function(d, t) {
    var s = d.createElement(t); s.type = 'text/javascript'; s.async = true;
    s.src = '//cdn.viglink.com/api/vglnk.js';
    var r = d.getElementsByTagName(t)[0]; r.parentNode.insertBefore(s, r);
  }(document, 'script'));
</script>


  <script>  // Load the SDK asynchronously
  window.fbAsyncInit = function() {    FB.init({      appId      : '1863943023885616',
        cookie     : true,     xfbml      : true,      version    : 'v2.10'
      });     FB.AppEvents.logPageView();    };
(function(d, s, id) {    var js, fjs = d.getElementsByTagName(s)[0];    if (d.getElementById(id)) return;    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";    fjs.parentNode.insertBefore(js, fjs);  }(document, 'script', 'facebook-jssdk'));
  // Here we run a very simple test of the Graph API after login is successful.  See statusChangeCallback() for when this call is made.
    function checkLoginState() { FB.getLoginStatus(function(response) {    statusChangeCallback(response);
  });
  };FB.getLoginStatus(function(response) {
    if (response.status === 'connected') {
      var accessToken = response.authResponse.accessToken;
    }
  } );
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  };
</script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-4943462589133750",
    enable_page_level_ads: true
  });
</script>

<script>
// Only works after `FB.init` is called
function myFacebookLogin() {
  FB.login(function(){}, {scope: 'publish_actions'});
}
function onButtonClick() {
FB.AppEvents.logEvent("sentFriendRequest");
} ;

</script>


<script>
kik.metrics.enableGoogleAnalytics();
kik.pickUsers(function (users) {
    if (!users) {
        // action was cancelled by user
    } else {
        users.forEach(function (user) {
            typeof user.username;  // 'string'
            typeof user.fullName;  // 'string'
            typeof user.firstName; // 'string'
            typeof user.lastName;  // 'string'
            typeof user.pic;       // 'string'
            typeof user.thumbnail; // 'string'
        });
    }
});
function handleBackButton () {
    // called when back button is pressed
    return false; // optionally cancel default behavior
}

kik.browser.back(handleBackButton);       // handle back button
kik.browser.unbindBack(handleBackButton); // unbind from handling back button

</script>
<script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=ddd0de63-477b-4d8a-a6aa-174f676a7651"></script>
