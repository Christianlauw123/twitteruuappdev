<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use Session;
use File;
use App\Tweet;
use App\HistoryTweet;
use App\DetailHistoryTweet;
use App\SocialTwitterAccount;
use Auth;
class CrawlingController extends Controller
{
	private $arrCharacter;
	public function __construct()
    {
    	$this->middleware('auth');
    	$arrCharacter = ["%", "@", "#", "," ,"."];
    }
    public function startCrawling(String $queryInput)
    {
    	// $connection = new TwitterOAuth(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'),Session::get('token_user'),Session::get('token_user_secret'));
    	
    	// $connection->setProxy([
     //        'CURLOPT_PROXY' => 'proxy3.ubaya.ac.id',
     //        'CURLOPT_PROXYUSERPWD' => '',
     //        'CURLOPT_PROXYPORT' => 8080,
     //    ]);
    	$max_id = NULL;
    	$ulangi = 1;
    	//Query Search
    	while($ulangi>0)
     	{
     		if($max_id==NULL)
     		{
				$query = array(
				  "q" => $queryInput." -filter:nativeretweets",
				  "result_type" => 'recent',
				  "count"=>100,
				  "tweet_mode" => "extended",
				  "lang" => "id",
				);
			}
			else
			{
				$query = array(
				  "q" => $queryInput." -filter:nativeretweets",
				  "result_type" => 'recent',
				  "count"=>200,
				  "tweet_mode" => "extended",
				  "lang" => "id",
				  "max_id" =>$max_id,
				);
			}
			$results = Session::get('connect')->get('search/tweets', $query);
		//Alternatif
		//Select semua id dari db masukkan array
		//Kalau mau dipotong disesuaikan dengan termInDoc
		
			foreach ($results->statuses as $result) {

				// TODO Select database dulu id_str nya cek duplikasi
				$cekIdStr = Tweet::where('id_str_tweet',$result->id_str)->first();
				if($cekIdStr==NULL)
				{
					$tweet = $result->full_text;
					if(isset($result->retweeted_status)){
						$tweet = $result->retweeted_status->full_text;
					}
					//echo "$tweet.<br/>";
					//Hasil Stemming Stopword
					$output = $this->preProcessing($tweet);
					//Select DB
					$tweetDbCek2 = Tweet::where('tweet_stemmed',$output)->first();
					if($tweetDbCek2==NULL)
					{
						$username = $result->user->name;
					 	$screenName = $result->user->screen_name;
					 	//Hasil Stemming Stopword
					 	$newTweet = new Tweet([
					 		'id_str_tweet'=> $result->id_str,
					 		'name' => $username,
					 		'screen_name' => $screenName,
					 		'tweet' => $tweet,
					 		'tweet_stemmed'=>$output,
					 	]);
					 	$newTweet->save();
					}
				}
				$max_id = $result->id;
			}
			//echo "<b>$max_id</b><br/>";
			$ulangi--;
		}
		return redirect()->back()->with('success', ['Crawling keyword '.$queryInput.' Selesai']);
    }
}
