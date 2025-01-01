<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requests\AdminBlogRequest;
use App\Article;
use App\Category;
use App\User;
use Auth;
use App\Image;
use App\Street_fighter_v;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class AdminBlogController extends Controller
{
    
    protected $article;
    protected $category;
    
    const NUM_PER_PAGE=10;
    
    //ログイン認証追加
    public function __construct()
    {
        $this->middleware("auth");
    }
    
    public function form(int $id =null)
    {
        
        
       $article=Article::find($id);
       $street_fighter_v=Street_fighter_v::find(['article_id'=>$id]);

       
        
        $input=[];
        if($article){
            $input=$article->toArray();
            $input['post_date']=$article->post_date_text;
        
        }
        else{
            $id=null;
        }
        
        $input=array_merge($input,old());
        $character_list=['リリー(SF6)','ガイル(SF6)','ED(SF6)'];
        
        $category_list=[];
        $category_list=Category::getCategoryList()->toArray();//->pluck('name','id');        //$category_list=Category::orderBy('display_order','asc')->pluck('name','id');
        
        return view('admin_blog.form',compact('input','id','category_list','article', 'character_list'));



    }
    
    public function post(AdminBlogRequest $request)
    {
    

    
    
        
    $input = $request->input();
    
    $id=Arr::get($input,'id');





            $article = Article::updateOrCreate(compact('id'), $input);
    
   
    
   //     if(($request->battle_lounge<$request->battle_lounge_win) 
//    ||($request->rank_match<$request->rank_match_win)
//    ||($request->casual_match<$request->casual_match_win)
 //   ||isset($request->lp)==false)
 //   {
 //           return redirect()->route('admin_form',  ['id' => $article->id])->with('message','勝利数が試合数より多い');
 //       
 //   }
    
   
    
    
    if(isset($request->battle_lounge) ||isset($request->battle_lounge_win) 
    ||isset($request->rank_match)|| isset($request->rank_match_win)
    ||isset($request->casual_match) ||isset($request->casual_match_win)
    ||isset($request->lp))
    {
            $street_fighter_v=Street_fighter_v::updateOrCreate(['article_id'=>$article->id],$input);
    }
            return redirect()->route('admin_form',  ['id' => $article->id])->with('message','記事を保存しました');
        
    }
    
    
    
        public function get_latest_lp(Request $request)
   {
    
    $cnt=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
    ->where('lp','>',0)
    ->where('character',$request->character)
    ->orderby('articles.post_date','desc')
    ->value('lp');

    return response()->json([
        'latest_lp'=>$cnt,
        'character'=>$request->character,
        ]);

    }
    
            public function get_title_count(Request $request)
   {

    $cnt=Article::where('category_id','=',$request->category_id)
    ->count();

    return response()->json([
        'count'=>$cnt,
        ]);

    }
    
    public function get_data_street_fighter_v(Request $request)
    {
   $datas = [];
       if(is_null($request->character) or $request->character=="0")
       {
            $cnt=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
            ->whereDate('articles.post_date','>=',$request->input('start_date'))
            ->whereDate('articles.post_date','<=',$request->input('end_date'))
            ->selectRaw( 'street_fighter_vs.character as playing,
                SUM(battle_lounge) as battle_lounge,
                SUM(battle_lounge_win) as battle_lounge_win,
                SUM(rank_match) as rank_match,
                SUM(rank_match_win) as rank_match_win,
                SUM(casual_match) as casual_match,
                SUM(casual_match_win) as casual_match_win'
                )->groupBy('playing')->get();
            
            foreach ($cnt as $cnt_part)
            {
                $lp_start=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                ->whereDate('articles.post_date','=',$request->input('start_date'))
                ->where('street_fighter_vs.character',$request->playing)
                ->value('lp');
        
                if(is_null($lp_start))
                {
                    $lp_start=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                    ->whereDate('articles.post_date','<',$request->input('start_date'))
                    ->where('street_fighter_vs.character',$cnt_part->playing)
                    ->whereNotNull('lp')
                    ->latest('articles.post_date')->value('lp');    
                }
        
                $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                ->whereDate('articles.post_date','>=',$request->input('start_date'))
                ->whereDate('articles.post_date','<=',$request->input('end_date'))
                ->where('street_fighter_vs.character',$cnt_part->playing)
                ->whereNotNull('lp')
                ->latest('articles.post_date')->value('lp');
        
                if(is_null($lp_end))
                {
                    $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
                    ->whereDate('articles.post_date','<',$request->input('start_date'))
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
                'lp_end'=>$lp_end     
        ];
        
            }
        
        
        
        return response()->json($datas);
      //   https://laravel-blog.paiza-user-basic.cloud/admin/post/get_data_street_fighter_v?start_date=2024/1/1&end_date=2024/12/31   
        
        
        // return response()->json([
        //    'battle_lounge'=>$cnt->battle_lounge,
         //   'battle_lounge_win'=>$cnt->battle_lounge_win,
         //   'rank_match'=>$cnt->rank_match,
         //   'rank_match_win'=>$cnt->rank_match_win,
         //   'casual_match'=>$cnt->casual_match,
         //   'casual_match_win'=>$cnt->casual_match_win,
         //   'lp_start'=>$lp_start,
          //  'lp_end'=>$lp_end,       
        //    'character'=>$cnt->character
        //]);
   }
   else
   {
        $cnt=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
        ->whereDate('articles.post_date','>=',$request->input('start_date'))
        ->whereDate('articles.post_date','<=',$request->input('end_date'))
        ->where('street_fighter_vs.character',$request->character)
        ->selectRaw( 'SUM(battle_lounge) as battle_lounge,
                    SUM(battle_lounge_win) as battle_lounge_win,
                    SUM(rank_match) as rank_match,
                    SUM(rank_match_win) as rank_match_win,
                    SUM(casual_match) as casual_match,
                    SUM(casual_match_win) as casual_match_win'
                    )->first();//->sum('battle_lounge');
                    
                    
        $lp_start=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
        ->whereDate('articles.post_date','=',$request->input('start_date'))
        ->where('street_fighter_vs.character',$request->character)
        ->value('lp');
        
        if(is_null($lp_start))
        {
            $lp_start=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
            ->whereDate('articles.post_date','<',$request->input('start_date'))
            ->where('street_fighter_vs.character',$request->character)
                ->whereNotNull('lp')
            ->latest('articles.post_date')->value('lp');    
        }
        
        $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
            ->whereDate('articles.post_date','>=',$request->input('start_date'))
            ->whereDate('articles.post_date','<=',$request->input('end_date'))
            ->whereNotNull('lp')
            ->where('street_fighter_vs.character',$request->character)
            ->latest('articles.post_date')->value('lp');
        
        if(is_null($lp_end))
        {
            $lp_end=Street_fighter_v::join('articles','street_fighter_vs.article_id','=','articles.id')
            ->whereDate('articles.post_date','<',$request->input('start_date'))
            ->where('street_fighter_vs.character',$request->character)
                ->whereNotNull('lp')
            ->latest('articles.post_date')->value('lp');
        }
        
        $framework[]=[
            'battle_lounge'=>$cnt->battle_lounge,
            'battle_lounge_win'=>$cnt->battle_lounge_win,
            'rank_match'=>$cnt->rank_match,
            'rank_match_win'=>$cnt->rank_match_win,
            'casual_match'=>$cnt->casual_match,
            'casual_match_win'=>$cnt->casual_match_win,
            'lp_start'=>$lp_start,
            'lp_end'=>$lp_end,       
            'character'=>$request->character];
            return response()->json($framework);//[
 //           'battle_lounge'=>$cnt->battle_lounge,
 //           'battle_lounge_win'=>$cnt->battle_lounge_win,
 //           'rank_match'=>$cnt->rank_match,
 //           'rank_match_win'=>$cnt->rank_match_win,
 //           'casual_match'=>$cnt->casual_match,
 //           'casual_match_win'=>$cnt->casual_match_win,
 //           'lp_start'=>$lp_start,
 //           'lp_end'=>$lp_end,       
 //           'character'=>$request->character
//        ]);
       }
    }
        public function delete(AdminBlogRequest $request)
    {
         $result = Article::destroy($request->id);
      
        $message = ($result) ? '記事を削除しました' : '記事の削除に失敗しました。';

        // フォーム画面へリダイレクト
        return redirect()->route('admin_list')->with('message', $message);
    }

    public function list()
    {
        $list=Article::orderby('id','desc')->paginate(self::NUM_PER_PAGE);
    
        
        return view('admin_blog.list',compact('list'));
    }
    
    public function category()
    {
    $list=Category::getCategoryList(self::NUM_PER_PAGE);
        
        return view('admin_blog.category',compact('list'));
    }
    
    public function editCategory(AdminBlogRequest $request)
    {
        $input=$request->input();
         $id=$request->id;//;Arr::get($input,'id');
     
        $category=Category::updateOrCreate(compact('id'),$input);

        
        return response()->json($category);
    }
  
      public function deleteCategory(AdminBlogRequest $request)
      {


         Article::where('category_id', $request->id) ->update(['category_id' => '1']);
         
           Category::destroy($request->id);
         
          return response()->json();
          
      }
      
        public function introduction()
    {
        
    
           $input=User::find(Auth::id());
    
        return view('admin_blog.introduction',compact('input'));


    }
    
         public function editIntroduction(AdminBlogRequest $request)
    {
        
    
           $id=Auth::id();
            
        User::where('id', Auth::id()) ->update(['name'=>$request->name,'comment'=>$request->comment]);
 
        
        
        return redirect()->route('admin_introduction')->with('message','変更を保存しました');


    }
    
    public function logout()
    {
        
        Auth::logout();
        
        return redirect()->route('front_index');
        
    }
    
    

    public function postMyImg(AdminBlogRequest $request)
    {
    //    $img='Y0MHoeEQwoT9156jPydvfm98NuTwqBV0UqZ95HN6';
//         File::delete(storage_path().'/app/public/img/'.$img.'.jpeg');
         
     $img=$request->file('name')->store('public/img');
Image::create(['name' => $img,'user_id'=>$request->user_id]);



  //     $input=$request->input();
    //     $input->name=$img;
   //      $id=$request->id;//;Arr::get($input,'id');
  
  //  Image::updateOrCreate(compact('id'),$input);
   
   return redirect()->route('admin_introduction')->with('message','画像ををアップしました');
    }
   
       public function postArticleImg(AdminBlogRequest $request)
    {
    //    $img='Y0MHoeEQwoT9156jPydvfm98NuTwqBV0UqZ95HN6';
//         File::delete(storage_path().'/app/public/img/'.$img.'.jpeg');
         
     $img=$request->file('name')->store('public/img');
Image::create(['name' => $img,'user_id'=>$request->user_id,'article_id'=>$request->article_id]);



  //     $input=$request->input();
    //     $input->name=$img;
   //      $id=$request->id;//;Arr::get($input,'id');
  
  //  Image::updateOrCreate(compact('id'),$input);
   
     return redirect()->route('admin_form',  ['id' => $request->article_id])->with('message','画像投稿しました');
   
}
 
 public function deleteImg(AdminBlogRequest $request)
 {
        
        
        \DB::transaction(function () use ($request) {
         Image::findOrFail($request->image_id)->delete();
         User::where('image_id', $request->image_id)->update(['image_id' => 0]);
         
        //$post->delete();
        File::delete(storage_path().'/app/'.$request->name);
    });
        
        
         return redirect()->route('admin_introduction')->with('message','画像を削除しました');
  
 }
    
    
    public function setMyImg(AdminBlogRequest $request)
    {
 
       $input=$request->input();
  
         $id=$request->id;
  
    User::where('id', Auth::id()) ->update(['image_id'=>$request->image_id,]);
 
       // updateOrCreate(compact('id'),$input);
   
   return redirect()->route('admin_introduction')->with('message','プロフィール画像に設定しました');
    }
    
    



    
}