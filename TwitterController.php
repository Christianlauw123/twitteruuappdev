<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Abraham\TwitterOAuth\TwitterOAuth;

use App\SocialTwitterAccount;
use App\User;
use Session;
use Auth;
class TwitterController extends Controller
{
    public function redirect()
    {
        $connected = @fsockopen("www.twitter.com", 80);
        if($connected)
        {
            try{
                if(Auth::user()==null)
                {
                    $connection = new TwitterOAuth(env('TWITTER_CONSUMER_KEY'),env('TWITTER_CONSUMER_SECRET'));
                    // $connection->setProxy([
                    //     'CURLOPT_PROXY' => 'proxy3.ubaya.ac.id',
                    //     'CURLOPT_PROXYUSERPWD' => '',
                    //     'CURLOPT_PROXYPORT' => 8080,
                    // ]);
            
                    $reqToken = $connection->oauth("oauth/request_token",
                        array("oauth_callback" => "http://localhost:8085/TwitterUUApp2/public/callback/twitter"));
            
                    $url = $connection->url("oauth/authenticate", array("oauth_token" => $reqToken['oauth_token']));
                    
                    Session::put('oauth_token',$reqToken['oauth_token']);
                    Session::put('oauth_token_secret',$reqToken['oauth_token_secret']);
            
                    return redirect()->away($url);
                }
                return redirect('/')->with('alert', 'Anda telah login');
            }
            catch(Exception $e)
            {
                return redirect('/')->with('alert', 'Silahkan Coba lagi');
            }
        }
        return redirect('/')->with('alert', 'Periksa Koneksi Anda');
    }
    public function callback()
    {    
        $connection = new TwitterOAuth(env('TWITTER_CONSUMER_KEY'),env('TWITTER_CONSUMER_SECRET'),Session::get('oauth_token'),Session::get('oauth_token_secret'));
        // $connection->setProxy([
        //     'CURLOPT_PROXY' => 'proxy3.ubaya.ac.id',
        //     'CURLOPT_PROXYUSERPWD' => '',
        //     'CURLOPT_PROXYPORT' => 8080,
        // ]);
        if(isset($_GET['oauth_verifier']))
        {
            $token = $connection->oauth('oauth/access_token',[
                'oauth_verifier' => $_GET['oauth_verifier']
            ]);
            try
            {
                $connection2 = new TwitterOAuth(env('TWITTER_CONSUMER_KEY'),env('TWITTER_CONSUMER_SECRET'),$token['oauth_token'],$token['oauth_token_secret']);
            }
            catch (Exception $e){
                return redirect('/')->with('alert', 'Anda belum login');
            }
            Session::put('connect',$connection2);
            $detail = $connection2->get('account/verify_credentials',[
                'include_email' => 'true',
                'skip_status' => 'true',
                'include_entities' => 'false'
            ]);
            // $connection2->setProxy([
            //     'CURLOPT_PROXY' => 'proxy3.ubaya.ac.id',
            //     'CURLOPT_PROXYUSERPWD' => '',
            //     'CURLOPT_PROXYPORT' => 8080,
            // ]);
            
            Session::put('token_user',$token['oauth_token']);
            Session::put('token_user_secret',$token['oauth_token_secret']);
            Session::put('profileImageSource',$detail->profile_image_url_https);
            Session::put('screenName',$detail->screen_name);
    
            //Login ke sistem
            $account = SocialTwitterAccount::where('provider_user_id',$detail->id)->first();
            //var_dump($account->user);
            if ($account) {
                 auth()->login($account->user);
             }
             else
             {
                $account = new SocialTwitterAccount([
                    'provider_user_id' => $detail->id,
                    'provider' => 'twitter'
                ]);
    
                $user = User::where('email',$detail->email)->first();
                if (!$user) {
                    $user = User::create([
                        'email' => $detail->email,
                        'name' => $detail->name,
                    ]);
                }
                $account->user()->associate($user);
                $account->save();
                auth()->login($user);
             }
            return redirect()->to('/');
        }
        return redirect('/')->with('alert', 'Anda tidak memliki akses');
    }

    public function myTimeline()
    {
        if(Auth::user()!=null)
        {
            $twitterId = SocialTwitterAccount::where('user_id',Auth::user()->id)->get();
            $status = Session::get('connect')->get( "statuses/home_timeline", [
                "user_id" => $twitterId[0]->provider_user_id,
                "count" => 5,
            ]);
            // print_r($status);
            return response()->json($status);
        }
        return redirect('/')->with('alert', 'Anda tidak memliki akses');
    }
    public function postTweet(String $query)
    {
    	// $connection = new TwitterOAuth(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'),Session::get('token_user'),Session::get('token_user_secret'));

    	// $connection->setProxy([
     //        'CURLOPT_PROXY' => 'proxy3.ubaya.ac.id',
     //        'CURLOPT_PROXYUSERPWD' => '',
     //        'CURLOPT_PROXYPORT' => 8080,
     //    ]);
        if(Auth::user()!=null)
        {
            $status = Session::get('connect')->post( "statuses/update", [
                "status" => $query]);
            if($status)
                return "1";
            else
                return "0";
        }
        return redirect('/')->with('alert', 'Anda tidak memiliki akses');
    }
}
