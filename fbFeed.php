<?php
// trendyPUB
$fbpgid ='1863943023885616';
$fbacTok = 'EAAafPw3RZCTABAHFbZAmqRpBlXVS0PRAGB6MXa5NBbEcYIZBZCvWfBoLjNbvNXZBSZClZB0i2yeZC2Yu7JmZBUZAHPOcLui2eo5rGZCc9SZCxY7MeXQdC9lOkisF6IsXyllmEAlohA0MiafQloQKp1hUfy0UgySjJqApWyAZD';
//Get the JSON
$json_object = @file_get_contents('https://graph.facebook.com/' . $fbpgid . '/posts?access_token=' . $fbacTok);
//Interpret data
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