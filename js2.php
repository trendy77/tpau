//js2.php

  
function checkLoginState() { FB.getLoginStatus(function(response) {    statusChangeCallback(response); });
  };FB.getLoginStatus(function(response) {  if (response.status === 'connected') {  var accessToken = response.authResponse.accessToken;
    } } );
  function testAPI() {   console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {    console.log('Successful login for: ' + response.name);document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';   }); };
// Only works after `FB.init` is called
function myFacebookLogin() {  FB.login(function(){}, {scope: 'publish_actions'});};function onButtonClick() {FB.AppEvents.logEvent("sentFriendRequest");};</script>
