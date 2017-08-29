<?php
// trendyPUB
if ($FBPAGEID){
	if ($FBTKN){

	$fbpgid ='1863943023885616';
$fbacTok = 'EAAafPw3RZCTABAHFbZAmqRpBlXVS0PRAGB6MXa5NBbEcYIZBZCvWfBoLjNbvNXZBSZClZB0i2yeZC2Yu7JmZBUZAHPOcLui2eo5rGZCc9SZCxY7MeXQdC9lOkisF6IsXyllmEAlohA0MiafQloQKp1hUfy0UgySjJqApWyAZD';
//Get the JSON
$json_object = @file_get_contents('https://graph.facebook.com/' . $fbpgid . '/posts?access_token=' . $fbacTok);
//Interpret data
$posts = "";
$fbdata = json_decode($json_object);
foreach($fbdata as $post){
$posts .= '<p><a href="' . $post->link . '">' . $post->story . '</a></p>';
$posts .= '<p><a href="' . $post->link . '">' . $post->message . '</a></p>';
$posts .= '<p>' . $post->description . '</p>';
$posts .= '<br />';
}
//Display the posts
echo $posts;
?>




<div class="fb-page" data-href="https://www.facebook.com/<?php echo $FBADDY; ?>/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/<?php echo $FBADDY; ?>/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/<?php echo $FBADDY; ?>/"><?php echo $FBTIT; ?></a></blockquote></div>


<div class="fb-page" 
  data-tabs="timeline,events,messages"
  data-href="https://www.facebook.com/organisebiz"
  data-width="380" 
  data-hide-cover="false"></div>
  
<div class="fb-page" 
  data-tabs="timeline,events,messages"
  data-href="https://www.facebook.com/govnewsinfo"
  data-width="380" 
  data-hide-cover="false"></div>
  
<div class="fb-page" 
  data-tabs="timeline,events,messages"
  data-href="https://www.facebook.com/globetravelsearch"
  data-width="380" 
  data-hide-cover="false"></div>
  

<div class="fb-page" 
  data-tabs="timeline,events,messages"
  data-href="https://www.facebook.com/trendypublishing"
  data-width="380" 
  data-hide-cover="false"></div>
  
  
  <div class="fb-page"
  data-href="https://www.facebook.com/truthinews" 
  data-width="340"
  data-hide-cover="false"
  data-show-facepile="true"></div>
  
  <!-- Your embedded comments code https://www.facebook.com/dialog/pagetab?app_id=' . $FBAPPID . '&redirect_uri=' .  --->
  
  
  <!-- Your embedded comments code -->
<div class="fb-comment-embed"
   data-href="https://www.facebook.com/zuck/posts/10102735452532991?comment_id=1070233703036185"
   data-width="500"></div>