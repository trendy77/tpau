<script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=ddd0de63-477b-4d8a-a6aa-174f676a7651"></script>

<!-- kik API -->
<script src="https://cdn.kik.com/kik/1.0.0/kik.js"></script>
<!-- FB API -->
<script src="https://connect.facebook.net/en_US/all.js"></script>

<!-- Twitter Button -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
</script>
    <!-- Google Plus Button-->
<script src="https://apis.google.com/js/platform.js" async defer></script>
    
	
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
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1863943023885616',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.10'
    });
    FB.AppEvents.logPageView();   
  };
// Only works after `FB.init` is called
function myFacebookLogin() {
  FB.login(function(){}, {scope: 'publish_actions'});
};
function onButtonClick() {
FB.AppEvents.logEvent("sentFriendRequest");
};
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

