<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\User;
use App\Street_fighter_v;
use App\Http\Controllers\FrontBlogController;

class TwitterController extends Controller
{
    
    public function dayily_post(Request $request)
    {
        
                $t = new CallTwitterApi();
        $data = $t->serachTweets("hellzkustom 日課投稿",20);

        $array = array();
        $check=array();
        foreach($data as $d) {
        
              $array[] = array($t->statusesOembed($d->id));

        }
        
        $introduction =User::find(1);
        

       $month_list=FrontBlogController::getMonthList();
        $category_list=FrontBlogController::getCatgoryList();
        $result=FrontBlogController::get_data_street_fighter_v();
            
         return view('front_blog.dayily_post',['twitter' => $array,'introduction'=>$introduction,'result'=>$result,'month_list'=>$month_list,'category_list'=>$category_list]);
    }
    
    //　Twitterのアカウント検索
    public function index(Request $request)
    {
        
        $t = new CallTwitterApi();
        $data = $t->serachTweets("#ストVラウンジ募集 OR ストVラウンジ OR #スト5ラウンジ募集 OR スト5ラウンジ exclude:retweets",10);

        $array = array();
        $check=array();
        foreach($data as $d) {
        
              $array[] = array($t->statusesOembed($d->id));

        }
        
        $introduction =User::find(1);
        
        
        
    
        
        return view('front_blog.sf5lounge', ['twitter' => $array,'introduction'=>$introduction]);
        
        //　Vueファイルで入力された検索されたキーワードの定義
    //    $q ="%23ストVラウンジ募集";// $request->keyword;

        // API keyなどを定義
    //    $consumer_key = config('twitter.twitter-api'); 
    //    $consumer_secret = config('twitter.twitter-ap     i-secret'); 
    //    $access_token = config('twitter.twitter-token'); 
      //  $access_token_secret = config('twitter.twitter-token-secret'); 

    //    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret); 
      //  $twitterRequest =$connection->get('search/tweets', array("q"=>$q,  "count" => 20)); 

        
     //  return response()->json(['result'=>$twitterRequest],200,
     //              ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
     //       JSON_UNESCAPED_UNICODE
    //    );
    
          }
          
         
}
class callTwitterApi
{
    
    private $t;
    
    public function __construct()
    {
        //$consumer_key = config('twitter.twitter-api'); 
        //$consumer_secret = config('twitter.twitter-api-secret'); 
        //$access_token = config('twitter.twitter-token'); 
        //$access_token_secret = config('twitter.twitter-token-secret'); 

        $this->t= new TwitterOAuth(config('twitter.twitter-api'), 
                                    config('twitter.twitter-api-secret'),
                                    config('twitter.twitter-token'),
                                    config('twitter.twitter-token-secret')
                                    ); 

    }
    
    // 投稿検索
    public function serachTweets(String $searchWord, Int $cnt)
    {

                $d = $this->t->get("search/tweets", [
                'q' => $searchWord,
                'count' => $cnt,
                'result_type'=>'recent'
             ]);

         
        return $d->statuses;
    }
    
    //oEmbed互換形式で取得
    public function statusesOembed(String $id)
    {
        $d = $this->t->get("statuses/oembed", [
            'id' => $id,
         ]);
         
        return $d->html;
    }
}

