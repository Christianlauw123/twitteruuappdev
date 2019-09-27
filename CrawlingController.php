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

    	//Query Based On User
    	//AgamaImaji : 855822661515137024
    	//GanisArfianiar : 937109330024734721
    	//ustadtengkuzul : 992690598434947073
    	$max_id = NULL;
    	$ulangi = 1;
    	
   //  	while($ulangi>0)
   //  	{
   //  		if($max_id==NULL)
   //  		{
			// 	$queryUser = array(
		 //    		"screen_name" => $queryInput,
		 //    		"count" =>200,
		 //    		"exclude_replies" => true,
		 //    		"include_rts" => false,
		 //    		"tweet_mode" => "extended",
		 //    	);
	  //   	}
   //  		else
   //  			$queryUser = array(
		 //    		"screen_name" => $queryInput,
		 //    		"count" =>200,
		 //    		"exclude_replies" => true,
		 //    		"include_rts" => false,
		 //    		"tweet_mode" => "extended",
		 //    		"max_id" => $max_id,
		 //    	);

			// $results2 = $connection->get('statuses/user_timeline', $queryUser);

	  //   	foreach($results2 as $item)
	  //   	{
	  //   		$tweet = $item->full_text;
				
			// 	//Hasil Stemming Stopword
			// 	$output = $this->preProcessing($tweet);

			// 	//Belum pernah ada tweet yang sama
			// 	$tweetDbCek = Tweet::where('tweet',$tweet)->first();
			// 	if($tweetDbCek==NULL)
			// 	{
			// 		//Select DB
			// 		//Setelah di stem apakah ada yang sama?
			// 		$tweetDbCek2 = Tweet::where('tweet_stemmed',$output)->first();
			// 		if($tweetDbCek2==NULL)
			// 		{
			// 			$username = $item->user->name;
			// 		 	$screenName = $item->user->screen_name;
			// 		 	//Hasil Stemming Stopword
			// 		 	$newTweet = new Tweet([
			// 		 		'id_str_tweet'=> $item->id_str,
			// 		 		'name' => $username,
			// 		 		'screen_name' => $screenName,
			// 		 		'tweet' => $tweet,
			// 		 		'tweet_stemmed'=>$output,
			// 		 	]);
			// 		 	$newTweet->save();
			// 		}
			// 	}
			// 	$max_id = $item->id;
			// }
	  //   	echo "<b>".$max_id."</b><br/>";
	  //   	$ulangi--;
   //  	}

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
    public function postTweet(String $query)
    {
    	// $connection = new TwitterOAuth(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'),Session::get('token_user'),Session::get('token_user_secret'));

    	// $connection->setProxy([
     //        'CURLOPT_PROXY' => 'proxy3.ubaya.ac.id',
     //        'CURLOPT_PROXYUSERPWD' => '',
     //        'CURLOPT_PROXYPORT' => 8080,
     //    ]);
        
		$status = Session::get('connect')->post( "statuses/update", [
			"status" => $query]);
		if($status)
			echo "1";
		else
			echo "0";
    }

    public function preProcessing(String $tweet)
    {
    	//Hapus Link
    	$regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
    	$strNoLink = preg_replace($regex, ' ', $tweet);
    	//Hapus user
    	$regexUser = "/@\w+/";
		$strNoLink = preg_replace($regexUser, '', $strNoLink);
		
		//Hapus Number
		$regexNumber = "/[0-9]+/";
		$strNoLink = preg_replace($regexNumber, '', $strNoLink);

    	//Ambil Hastag
    	$regexHT = "@#\w+@";
    	preg_match_all($regexHT,$strNoLink,$delete_User);
		$resHastag = "";

		
		foreach($delete_User[0] as $key => $item)
		{			
			if(!ctype_lower(substr($item,1)) && !ctype_upper(substr($item,1)))
			{
				//Memisahkan setiap huruf kapital awal
				$regx2 ="@(?=[A-Z])@";
				$value = preg_split($regx2,$item);
				unset($value[0]);
				$resHastag = $resHastag.implode(' ',$value)." ";
				//$res1[$key]=$im;
			}
			else
				$resHastag = $resHastag.' '.substr($item, 1);
		}
		//Hapus semua hastag dari tweet awal
		$strNoHT = preg_replace($regexHT,'',$strNoLink);
		//Gabungkan Teks biasa dengan hastag
		$sentence = trim(trim($strNoHT).' '.trim($resHastag));

		//Stemming tweet yang sudah dihapus linknya
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer  = $stemmerFactory->createStemmer();
		
		$stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
		$stopWordRemover = $stopWordRemoverFactory->createStopWordRemover();

		//Stemming ke kata dasar dan hapus stopword
		$outputStem   = $stemmer->stem($sentence);				
		$output = $stopWordRemover->remove($outputStem);

		$output2 = explode(" ",$output);
		foreach($output2 as $key => $item)
		{
			if(strlen($item)<3)
				unset($output2[$key]);
		}
		$output2 = implode(" ", $output2);
		
		return $output2;
    }

    public function euclidianDistance(Request $request)
    {
    	//Preprocess Tweet Baru
		$toCheck = $this->preProcessing($request->get('tweet'));

    	//Menyimpan kemunculan Term dalam seluruh dokumen
		$arrTermDf = [];

		//Menyimpan kumpulan term dalam suatu dokumen
		$arrTerminDoc = [];
		$arrEucl = [];


		//Untk setiap dokumen cek jaraknya ( twitter_v1)
		// if(File::exists(public_path().'/termInDoc.json') && File::exists(public_path().'/termDf.json'))
		// {
		// 	$arrTermDf = json_decode(file_get_contents(public_path().'/termDf.json'),true);
		// 	$arrTerminDoc = json_decode(file_get_contents(public_path().'/termInDoc.json'),true);
		// }

		//Untk setiap dokumen cek jaraknya ( twitter_v2)
		// if(File::exists(public_path().'/termInDoc_v2.json') && File::exists(public_path().'/termDf_v2.json'))
		// {
		// 	$arrTermDf = json_decode(file_get_contents(public_path().'/termDf_v2.json'),true);
		// 	$arrTerminDoc = json_decode(file_get_contents(public_path().'/termInDoc_v2.json'),true);
		// }

		if(File::exists(public_path().'/termInDoc_v3.json') && File::exists(public_path().'/termDf_v3.json'))
		{
			$arrTermDf = json_decode(file_get_contents(public_path().'/termDf_v3.json'),true);
			$arrTerminDoc = json_decode(file_get_contents(public_path().'/termInDoc_v3.json'),true);
		}

		//Pecah menjadi token"
		$arrTokenisasi = explode(" ", $toCheck);
		//Menyimpan kemunculan term dalam tweet baru
		$arrQueryTf = [];
		
		foreach($arrTokenisasi as $item)
		{
			if(isset($arrQueryTf[$item]))
				$arrQueryTf[$item]++;
			else
				$arrQueryTf[$item]=1;
		}
		
		//Bobotkan query dengan dokumen
		//alternatif fetch dari db no id
		$allTweet = Tweet::get();
		
		foreach($allTweet as $key => $item)
		{
			$eucRes = 0;
			$arrQuery= [];
			$tweet_id = $item->id;
			$compared = false;
			foreach($arrQueryTf as $key => $item)
				$arrQuery[$key]=false;
			
			//cek apakah Tweet dapat dibandingkan dengan tweet baru?
			// foreach($arrTerminDoc[$tweet_id] as $key => $item)
			// {
			// 	if(isset($arrQueryTf[$key]))
			// 	{
			// 		$compared = true;
			// 		break;
			// 	}
			// }
			//Bisa dibandingkan
			//Bobot kan tweet train dengan tweet baru
			foreach($arrTerminDoc[$tweet_id] as $key => $item)
			{
				if(isset($arrQueryTf[$key]))
				{
					$dalamQuery = $arrQueryTf[$key] * log(count($arrTerminDoc)/$arrTermDf[$key]);
					$dalamDoc = $item['tf-idf'];
					$eucRes += pow(($dalamQuery-$dalamDoc),2);

					$arrQuery[$key]=true;
				}
				else
				{
					//print_r($key);
					$eucRes += pow($item['tf-idf'],2);
				}
			}
			//hitung bobot tweet baru yg tidak ada
			foreach($arrQuery as $key =>$item)
			{

				if(!$item)
				{
					$dalamQuery=0;
					if(isset($arrTermDf[$key]))
						$dalamQuery=
					$arrQueryTf[$key]*log(count($arrTerminDoc)/$arrTermDf[$key]);
					else
						$dalamQuery = $arrQueryTf[$key];
					$eucRes += pow($dalamQuery,2);
				}
				$item=false;
			}
			$arrEucl[$tweet_id]['jarak'] = $eucRes;
		}
		asort($arrEucl);
		//print_r($arrEucl);
		
		//Insert Tweet Baru
		// $newTweet = new Tweet([
		// 	"name"=>Auth::user()->name,
		// 	"screen_name"=>Session::get('screenName'),
		// 	"tweet"=>$tweet,
		// 	"tweet_stemmed"=>$toCheck
		// ]);
		// $newTweet->save();
		// Session::put('lastIdTweet',$newTweet->id);

		// $newHistory = new HistoryTweet([
		// 	"nilaiK"=>Auth::user()->nilaiK,
		// 	"tweet_id"=>$newTweet->id,
		// ]);
		// $newHistory->save();

		//Setelah Check Pos Lalu Insert
		$i=env('Nilai_K');
		// foreach($arrEucl as $key=>$item)
		// {
		// 	if($i>0)
		// 		$i--;
		// 	else
		// 		break;
		// 	$newDetail = new DetailHistoryTweet([
		// 		"distance"=>$arrEucl[$key]['jarak'],
		// 		"tweet_id"=>$key,
		// 		"historytweet_id"=>$newHistory->id,
		// 	]);	
		// 	$newDetail->save();
		// }

		$arrSentimentResult = [];
		foreach($arrEucl as $key=>$item)
		{
			if($i>0)
				$i--;
			else
				break;
			$getTweet = Tweet::find($key);
			
			if(isset($arrSentimentResult[$getTweet->isMelanggar]))
				$arrSentimentResult[$getTweet->isMelanggar]+=1;
			else
				$arrSentimentResult[$getTweet->isMelanggar]=1;
		}
		$i=env('Nilai_K');
		for($i=0;$i<2;$i++)
			if(!isset($arrSentimentResult[$i]))
			{
				$arrSentimentResult[$i]=0;
			}
		
		// $tweetBaru = Tweet::find($newTweet->id);
		// if($arrSentimentResult[0]>$arrSentimentResult[1])
		// 	$tweetBaru->isMelanggar = 0;
		// else
		// 	$tweetBaru->isMelanggar = 1;
		// $tweetBaru->save();
		
		//Tidak Melanggar
		if($arrSentimentResult[0]>$arrSentimentResult[1])
		{
			return response()->json([
				'hasil'=>0
			]);
		}
		else if($arrSentimentResult[0]<$arrSentimentResult[1])
		{
			return response()->json([
				'hasil'=>1
			]);
		}
		else
		{
			return response()->json([
				'hasil'=>-1
			]);
		}
    }
    public function indexingTweet()
    {
		//TODO Select all stemmed content from DB;
		$allTweet = Tweet::where('isActive',1)->get();
		$arrTerminDoc = [];
		$arrTermDf = [];
		$arrTerminDoc = [];
		foreach($allTweet as $key => $item)
		{
			$count = $item->id;
			
			//Tokenisasi
			$arrTokenisasi = explode(" ", $item->tweet_stemmed);
			$arrTemp=[];
			//Tokenisasi unigram
			//Untuk cek jumlah term
			foreach($arrTokenisasi as $item)
				$arrTemp[$item]=false;

			//Masukkan df dulu
			foreach($arrTokenisasi as $item)
			{
				if(!$arrTemp[$item]) //Untuk masukkan df
				{
					if(isset($arrTermDf[$item]))
						$arrTermDf[$item]+=1;
					else
						$arrTermDf[$item]=1;
					$arrTemp[$item]=true;
				}

				if(isset($arrTerminDoc[$count][$item]['tf']))
					$arrTerminDoc[$count][$item]['tf']+=1;
				else
					$arrTerminDoc[$count][$item]['tf']=1;
			}

		}

		//Hitung tf-idf
		foreach($allTweet as $key => $item)
		{
			foreach($arrTerminDoc[$item->id] as $key2 => $item2)
			{
				$arrTerminDoc[$item->id][$key2]['tf-idf'] = $arrTerminDoc[$item->id][$key2]['tf']*log(count($arrTerminDoc)/$arrTermDf[$key2]);
			}
		}
		
		// file_put_contents('termDf.json',json_encode($arrTermDf));
		// file_put_contents('termInDoc.json',json_encode($arrTerminDoc));

		// file_put_contents('termDf_v2.json',json_encode($arrTermDf));
		// file_put_contents('termInDoc_v2.json',json_encode($arrTerminDoc));

		file_put_contents('termDf_v3.json',json_encode($arrTermDf));
		file_put_contents('termInDoc_v3.json',json_encode($arrTerminDoc));
		return redirect()->back()->with('success', ['Indexing Selesai']);
    }
    
    public function updateStem()
    {
    	$allTweet = Tweet::where('isActive',1)->get();
    	foreach($allTweet as $key => $item)
    	{
			$getTweet = Tweet::find($item->id);
			$getTweet->tweet_stemmed = $this->preProcessing($getTweet->tweet);
			$getTweet->save();
    	}

	}
	public function levenshteinDistance(String $kalimat)
	{
		$kalimatPecah = explode(" ",$kalimat);
		$arrWordToCheck = json_decode(file_get_contents('jsonOfKata-Dasar.json'),true);
		
		//MXN Source
		//Target , ambil paling kecil
		$result = [];
		foreach($kalimatPecah as $item)
		{
			$kataGanti=[];
			$min = 99999;

			$kataGanti[0][0]=0;
			//Set Matrix Source
			$sourceChar = str_split($item);
			foreach($sourceChar as $key =>$item2)
				$kataGanti[$key+1][0]=$key+1;
			$sourceSize = count($sourceChar);
			
			//echo "<br/>";
			$cekWord = substr($item,0,1);
			$kataBaru = "";
			//Cek sesuai huruf awalan
			foreach($arrWordToCheck[$cekWord] as $item)
			{
				$kataComplement = [];

				//Set Matrix Target
				$targetSize = count(str_split($item));
				$targetChar = str_split($item);
				foreach($targetChar as $key =>$item2)
					$kataGanti[0][$key+1]=$key+1;

				for($i=1;$i<=$targetSize;$i++)
				{
					for($j=1;$j<=$sourceSize;$j++)
					{
						if($sourceChar[$j-1]==$targetChar[$i-1])
							$subCost=0;
						else
							$subCost=1;
						$kataGanti[$j][$i] = min($kataGanti[$j-1][$i]+1,$kataGanti[$j][$i-1]+1,$kataGanti[$j-1][$i-1]+$subCost);
					}
				}
				if($kataGanti[$sourceSize][$targetSize]<$min)
				{
					$min = $kataGanti[$sourceSize][$targetSize];
					$kataBaru = $item;
				}
			}
			$result[] = $kataBaru;
		}
		return implode(" ",$result);
	}
}
