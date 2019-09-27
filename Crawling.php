<?php
require_once 'twitteroauth/twitteroauth.php';
require_once 'vendor/autoload.php';
//require_once 'configDB.php';
define('CONSUMER_KEY', ''); //isikan dengan CONSUMER_KEY anda
define('CONSUMER_SECRET', ''); //isikan dengan CONSUMER_KEY anda
define('ACCESS_TOKEN', '	'); //isikan dengan CONSUMER_KEY anda
define('ACCESS_TOKEN_SECRET', ); //isikan dengan CONSUMER_KEY anda

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
$query = array(
  "q" => 'kpk',
  "result_type" => 'recent',
  "count"=>10,
  "tweet_mode" => "extended"
);
$results = $connection->get('search/tweets', $query);


//print_r($results);
foreach ($results->statuses as $key => $result) {
  //print_r($results) . "<br>";
	$id_str = $result->id_str;
	$created_at = date("Y-m-d H:i:s", strtotime($result->created_at));
	$username = $result->user->name;
	$screenname = $result->user->screen_name;
	$tweet = $result->full_text;
	if(isset($result->retweeted_status)){
		$tweet = $result->retweeted_status->full_text;
	}
	//echo $result->retweeted_status;
	echo $key + 1 . "<br>";
	echo $id_str . "<br>";
	echo $created_at . "<br>";
	echo $username . "<br>";
	echo $screenname . "<br>";
  	echo $tweet . "<br>";
} 
//$mysqli->close();
?>
