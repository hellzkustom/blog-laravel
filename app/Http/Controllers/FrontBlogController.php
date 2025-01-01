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
        
        
        $results=self::get_data_street_fighter_v();
        $ranks=self::get_rank_street_fighter_vi();
    
      //  $imgpath=Image::find($introduction->image_id);
        
        return view('front_blog.index',compact('list','month_list','category_list','introduction','results','ranks'));
    }
    
        public function get_rank_street_fighter_vi()
    {
         $datas = [];
         $rank_data=[25000,19000,13000,9000,5000,3000,1000];
         $star_width=[1200,800,400,200];
         $end_date= new DateTime(Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
        ->max('articles.post_date'));
        
        $cnt=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
            //->where('street_fighter_vs.character','like','')
            ->selectRaw( 'street_fighter_vs.character as playing')->groupBy('playing')->get();
        foreach ($cnt as $cnt_part)
        {
                $rank='New Charanger';
                $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                ->whereDate('articles.post_date','<=',$end_date)
                ->where('street_fighter_vs.character',$cnt_part->playing)
                ->whereNotNull('lp')
                ->latest('articles.post_date')->value('lp');  
                
                 if ($cnt_part->playing=='')
                {
                    $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                    ->whereDate('articles.post_date','<=','2023/6/3')
                    ->where('street_fighter_vs.character',$cnt_part->playing)
                    ->whereNotNull('lp')
                    ->latest('articles.post_date')->value('lp');
                    $cnt_part->playing='SF5';
                    $rank='Silver';
                    $star_check=''; 
                }
                else
                {           
                    $star_check='';
                    $star_num=0;
                    $star_index=0;
                    if($lp_end>=$rank_data[0])
                    {
                        $rank='Master';
                        $star_index=0;
                    }
                    elseif($lp_end>=$rank_data[1])
                    {
                          $rank='Diamond';
                          $star_num=$lp_end-$rank_data[1];
                          $star_index=0;
                          
                    }
                     elseif($lp_end>=$rank_data[2])
                    {
                          $rank='Platium';
                          $star_num=$lp_end-$rank_data[2];
                          $star_index=0;
                    }               
                     elseif($lp_end>=$rank_data[3])
                    {
                          $rank='Gold';
                          $star_num=$lp_end-$rank_data[3];
                          $star_index=1;
                    }
                    elseif($lp_end>=$rank_data[4])
                    {
                          $rank='Silver';
                          $star_num=$lp_end-$rank_data[4];
                          $star_index=1;
                    }     
                    elseif($lp_end>=$rank_data[5])
                    {
                          $rank='Bronze';
                          $star_num=$lp_end-$rank_data[5];
                          $star_index=1;
                    }     
                    elseif($lp_end>=$rank_data[6])
                    {
                          $rank='Iron';
                        $star_num=$lp_end-$rank_data[6];
                          $star_index=2;
                    }
                    elseif($lp_end>0)
                    {
                          $rank='Rookie';
                          $star_index=3;
                    }     
                        
                    if($star_num>0)
                    {
                      $num=ceil($star_num/$star_width[$star_index]);
                      
                      for($i=0;$i<$num;$i++)
                        $star_check.='★'; 
                        
                    }
                }    
                
            $datas[]=[
                'character'=>$cnt_part->playing,
                'lp_end'=>$lp_end,
                'rank'=>$rank.$star_check//$star_num.(string)$star_width[$star_index]
                ];
        }
        
        
        
        
        
        return $datas;
            
    }
        public static function get_data_street_fighter_v()
    {
        $datas = [];
        $start_date= new DateTime('last week');
        $end_date= new DateTime(Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
        ->max('articles.post_date'));
    
        $cnt=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                ->whereDate('articles.post_date','>=',$start_date)
                ->whereDate('articles.post_date','<=',$end_date)
        ->selectRaw('street_fighter_vs.character as playing,
                    SUM(battle_lounge) as battle_lounge,
                    SUM(battle_lounge_win) as battle_lounge_win,
                    SUM(rank_match) as rank_match,
                    SUM(rank_match_win) as rank_match_win,
                    SUM(casual_match) as casual_match,
                    SUM(casual_match_win) as casual_match_win'
                    )->groupBy('playing')->get();//->sum('battle_lounge');
    
        foreach ($cnt as $cnt_part)
        {  
        
            $lp_start=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                ->where('street_fighter_vs.character',$cnt_part->playing)
                ->whereDate('articles.post_date','=',$start_date)
                ->value('lp');
        
            if(is_null($lp_start))
            {
                $lp_start=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                ->whereDate('articles.post_date','<',$start_date)
                ->where('street_fighter_vs.character',$cnt_part->playing)
                ->whereNotNull('lp')
                ->latest('articles.post_date')->value('lp');    
            }

            $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
            ->whereDate('articles.post_date','=',$end_date)
            ->where('street_fighter_vs.character',$cnt_part->playing)
            ->value('lp');
        
            if(is_null($lp_end))
            {
                $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                ->whereDate('articles.post_date','<',$end_date)
                ->where('street_fighter_vs.character',$cnt_part->playing)
                ->whereNotNull('lp')
                ->latest('articles.post_date')->value('lp');    
            }
        
        
            $datas[]=[
                'character'=>$cnt_part->playing,
                'battle_lounge'=>$cnt_part->battle_lounge,
                'battle_lounge_win'=>$cnt_part->battle_lounge_win,
                'rank_match'=>$cnt_part->rank_match,
                'rank_match_win'=>$cnt_part->rank_match_win,
                'casual_match'=>$cnt_part->casual_match,
                'casual_match_win'=>$cnt_part->casual_match_win,
                'lp_start'=>$lp_start,
                'lp_end'=>$lp_end,       
                'start_date'=>$start_date->format('Y/m/d'),
                'end_date'=>$end_date->format('Y/m/d'),
                ];
        }
        return $datas;
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
        
       $results=self::get_data_street_fighter_v();
        $ranks=self::get_rank_street_fighter_vi();
        
        return view('front_blog.article',compact('article','month_list','category_list','introduction','results','ranks'));
        
        
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
     
    $checked_items= array();
    
     if(empty($request->id17)==false)
     {
        array_push($checked_items,$request->id17);
         }
         if(empty($request->id19)==false)
     {
        array_push($checked_items,$request->id19);
     }
          if(empty($request->id20)==false);
     {
        array_push($checked_items,$request->id20);
     }
     
          if(empty($request->id23)==false)
     {
        array_push($checked_items,$request->id23);
     }


          if(empty($request->id24)==false)
     {
        array_push($checked_items,$request->id24);
     }



        $checked_items = array_filter($checked_items);
        
        if(count($checked_items)==0)
        {
        $checked_items=[17,19,20,23];
    
        }
   
     $where_items=array();
  $preg_split = preg_split("/\s/",mb_convert_kana($request->word,"s"));
  
  foreach ($preg_split as $item)
  {
          array_push($where_items,['comments.body','Like','%'.$item.'%']);
  }
  
 
  if(empty($request->start_comment)==false)
  {
        $start_comment=$request->start_comment;
  }
        else
    {
        $start_comment='1901/01/01';
  }
  
    if(empty($request->end_comment)==false)
  {
        $end_comment=$request->end_comment;
  }
        else
    {
        $end_comment='9999/01/01';
  }

  if(empty($request->start_article)==false)
  {
        $start_article=$request->start_article;
  }
        else
    {
        $start_article='1901/01/01';
  }
  
    if(empty($request->end_article)==false)
  {
        $end_article=$request->end_article;
  }
        else
    {
        $end_article='9999/01/01';
  }
          
        $list =Comment::selectRaw('comments.body as body,
                                    articles.id as article_id,
                                    articles.title as title,
                                    comments.updated_at as updated_at')
            ->join('articles','comments.article_id','=','articles.id')
            ->whereDate('comments.updated_at','>=',$start_comment)
            ->whereDate('comments.updated_at','<=',$end_comment)
            ->whereDate('articles.updated_at','>=',$start_article)
            ->whereDate('articles.updated_at','<=',$end_article)
            ->where($where_items)
           ->whereIn('articles.category_id',$checked_items)
            ->orderby('comments.id','desc')->paginate(self::NUM_PER_PAGE);
                        
        $month_list=self::getMonthList();
        $category_list=self::getCatgoryList();
        
        
        $introduction =User::find(1);
        
       $results=self::get_data_street_fighter_v();
        $ranks=self::get_rank_street_fighter_vi();
        
        $name=$request->name;
        return view('front_blog.commentList',compact('list','month_list','category_list','introduction','results','ranks','checked_items'),
        ['word'=>$request->word,
        'start_comment'=>$request->start_comment,'end_comment'=>$request->end_comment,
        'start_article'=>$request->start_article,'end_article'=>$request->end_article]);
 
    }   
}
