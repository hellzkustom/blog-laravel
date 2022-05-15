<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Category;
use Illuminate\Support\Arr;
use App\Http\Requests\FrontBlogRequest;
use Carbon\Carbon;
use App\User;
use App\Comment;
use App\Image;
use App\Http\Controllers\AdminBlogController;
use App\Street_fighter_v;
use DateTime;

class FrontBlogController extends Controller
{
    const NUM_PER_PAGE=10;
    
    protected $article;
    protected $category;
    
 //       function __construct(Article $article, Category $category)
 //   {
 //       $this->article = $article;
 //       $this->category = $category;
 //   }




    
   public function index(FrontBlogRequest $request)
    {
        $input=$request->input();
        
        $list=Article::with(['comment'])->orderby('id','desc')->paginate(self::NUM_PER_PAGE);
        
           $list = self::getArticleList(self::NUM_PER_PAGE, $input);
           
             $list->appends($input);
           
       $month_list=self::getMonthList();
        $category_list=self::getCatgoryList();
        
        $introduction =User::find(1);
        
        
        $result=self::get_data_street_fighter_v();
    
      //  $imgpath=Image::find($introduction->image_id);
        
        return view('front_blog.index',compact('list','month_list','category_list','introduction','result'));
    }
    
        public static function get_data_street_fighter_v()
    {
        $start_date= new DateTime('last week');
        $end_date= new DateTime(Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
    ->max('articles.post_date'));
    
    $cnt=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
    ->whereDate('articles.post_date','>=',$start_date)
    ->whereDate('articles.post_date','<=',$end_date)
    ->selectRaw( 'SUM(battle_lounge) as battle_lounge,
                SUM(battle_lounge_win) as battle_lounge_win,
                SUM(rank_match) as rank_match,
                SUM(rank_match_win) as rank_match_win,
                SUM(casual_match) as casual_match,
                SUM(casual_match_win) as casual_match_win'
                )->first();//->sum('battle_lounge');
   
 //   $cnt_battle_lounge_win=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
 //   ->whereDate('articles.post_date','>=',$request->input('start_date'))
 //   ->whereDate('articles.post_date','<=',$request->input('end_date'))
 //   ->sum('battle_lounge_win');
     
    $lp_start=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
    ->whereDate('articles.post_date','=',$start_date)
    ->value('lp');

    $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
    ->whereDate('articles.post_date','=',$end_date)
    ->value('lp');
    
    
    return array(
        'battle_lounge'=>$cnt->battle_lounge,
        'battle_lounge_win'=>$cnt->battle_lounge_win,
        'rank_match'=>$cnt->rank_match,
        'rank_match_win'=>$cnt->rank_match_win,
        'casual_match'=>$cnt->casual_match,
        'casual_match_win'=>$cnt->casual_match_win,
        'lp_start'=>$lp_start,
        'lp_end'=>$lp_end,       
        'start_date'=>$start_date->format('Y/m/d'),
        'end_date'=>$end_date->format('Y/m/d'),
        );

    }

    
    public static function getCatgoryList()
    {
        $category_list=Category::select('name','id')
                             ->orderBy('display_order','asc')
                             ->get();
     
            return $category_list;
    }
    
    public function getArticleList(int $num_per_page=10,array $condition=[])
    {
        $category_id=Arr::get($condition,'category_id');
        $year=Arr::get($condition,'year');        
        $month=Arr::get($condition,'month');
      
      
        
        $query=Article::with('category')->orderBy('id','desc');
        
        if($category_id)
       {
           $query->where('category_id',$category_id);
        }
        
        if($year)
        {
            if($month)
            {
                $start_date=Carbon::createFromDate($year,$month,1);
                $end_date=Carbon::createFromDate($year,$month,1)->addMonth();
            }
            else
            {
                $start_date=Carbon::createFromDate($year,1,1);
                $end_date=Carbon::createFromDate($year,1,1)->addYear();
            
            }
                 $query->where('post_date','>=',$start_date->format('Y-m-d'))
                         ->where('post_date','<',$end_date->format('Y-m-d'));
                         
                        
        }
        
        return $query->paginate($num_per_page);
        
    }
    public static function getMonthList()
    {
        $month_list=Article::selectraw('substring(post_date,1,7) AS year_and_month')
                             ->groupBy('year_and_month')
                             ->orderBy('year_and_month','desc')
                             ->get();
                             
        foreach($month_list as $value)
      {
           list($year,$month)=explode('-',$value->year_and_month);
            $value->year=$year;
        $value->month=(int)$month;
            $value->year_month=sprintf("%04d年%02d月",$year,$month);
      }
        
        return $month_list;
    }
    
    public function article(FrontBlogRequest $request)
    {
        
        
        $article=Article::find($request->id);
        $month_list=self::getMonthList();
        $category_list=self::getCatgoryList();
        
        
        $introduction =User::find(1);
        
        $result=self::get_data_street_fighter_v();
        
        return view('front_blog.article',compact('article','month_list','category_list','introduction','result'));
        
        
    }
    public function commentPost(FrontBlogRequest $request)
    {
 
       $article=Article::findOrFail($request->id);
 
        $article->comment()->create($request->toArray());


        return response()->json();
       
        
    }
    public function commentDelete(FrontBlogRequest $request)
    {
       $result = Comment::destroy($request->id);
      
        $message = ($result) ? 'コメントを削除しました' : 'の削除に失敗しました。';

        // フォーム画面へリダイレクト
        return redirect()->route('front_article', ['id' => $request->article_id]);

    }
    
    public function commentList(Request $request)
    {
        $list =Comment::join('articles','comments.article_id','=','articles.id')
            ->orWhere('articles.category_id','=',17 )
             ->orWhere('articles.category_id','=',19)
            ->orWhere('articles.category_id','=',20 )
            ->orWhere('articles.category_id','=',23 )
            ->orderby('comments.id','desc')->paginate(self::NUM_PER_PAGE);//join('articles','comments.article_id','=','articles.id');
                        
        $month_list=self::getMonthList();
        $category_list=self::getCatgoryList();
        
        
        $introduction =User::find(1);
        
        $result=self::get_data_street_fighter_v();
        
        return view('front_blog.commentList',compact('list','month_list','category_list','introduction','result'));
 
    }   
}
