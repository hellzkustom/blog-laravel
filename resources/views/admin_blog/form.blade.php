@extends('app')
@section('title','ブログ記事投稿フォーム')

@section('head')
    {{--jQuery は下記のファイルに記述し読み込むようにする--}}

   <script src="{{ asset('/js/ajax.js') }}"></script>    
    <script src="{{ asset('/js/form.js') }}"></script>    
@endsection




@section('body')
    <div class="container">
        <div class="row" name="main">
            <div class="col-md-10 col-md-offset-1">
    <h2>ブログ記事投稿・編集</h2>
<a href="{{ route('admin_list') }}">リストに戻る</a>
    @if(session('message'))
        <div class="alert alert-success">
        
        {{session('message')}}
        
        </div>
    @endif
@include('error')
    
 @include('image_view_index')
 
    @if(isset($id))
           <form method="POST" action="{{route('admin_post_article_img')}}" enctype="multipart/form-data" class="image_form">
                     <div class="post_intro">   <input type="file" name="name" accept="image/*" >
                                      <input type="hidden" name="user_id" value="{{Auth::id()}}">
                                      <input type="hidden" name="article_id" value="{{$input['id']}}">
                     
                     </div>
                    <div class="post_intro"><input type="submit"  class="btn btn-primary" value="投稿"></input>
                    </div>
        </form>
 
    @else
    <p>文章を作成してから画像投稿可能</p>
    @endif
    
    
    <form method="POST" action="{{route('admin_post')}}">
    日付<br>
    <input class="form_control"  type="date" name="post_date" size="20" value="{{isset($input['post_date']) ? $input['post_date']:null}}" placeholder="日付を入力して下さい。"><br><br>
  
  カテゴリー <br>
        <select class="form_control" name="category_id" id="category">
                          <option value="0" selected>カテゴリー選択してください</option>
    
        @if(isset($input['category_id']))
           @foreach($category_list as $category_list)
               @if($input['category_id']==$category_list['id'])
                  <option value="{{$category_list['id']}}" selected>{{$category_list['name']}}</option>
	           @else
                  <option value="{{$category_list['id']}}">{{$category_list['name']}}</option>
              @endif	           
	           
	       @endforeach 
        @else
           @foreach($category_list as $category_list)
	               <option value="{{(int)$category_list['id']}}">{{$category_list['name']}}</option>
	       @endforeach
        @endif
        </select>
        
        <br><br>

    タイトル<br>
    <input class="form_control" name="title" value="{{isset($input['title']) ? $input['title'] : null}}" placeholder="タイトルを入力して下さい。"><br><br>
    
    
        <div>
            
            <div>
    キャラクター<br>

     <select class="form_control" name="character" id="character">
        <option value="0" selected>キャラクター選択してください</option>
 
        @if(isset($article->street_fighter_v))
              @foreach($character_list as $character)

               @if($character== optional($article->street_fighter_v)->character)
                  <option value="{{$character}}" selected>{{optional($article->street_fighter_v)->character}}</option>
	           @else

                  <option value="{{$character}}">{{$character}}</option>
              @endif	           
	           
	       @endforeach 
        @else
            @foreach($character_list as $character)
	               <option value="{{$character}}">{{$character}}</option>
	       @endforeach
        @endif
        </select>
        <br>
        <br>
        
        
        
        
        
        
                    @if(isset($article->street_fighter_v))
                        LP:<input class="number" type="number" name="lp" value="{{optional($article->street_fighter_v)->lp}}">
                    @else
                        LP:<input class="number" type="number" name="lp" value="">
                    @endif
                                <button id="check_lp" type="button" name="check_lp" class="btn btn-primary">LP増減</button>
   
            </div>
        Battle Lounge<br>
            <div>
                    @if(isset($article->street_fighter_v))
                    試合数:<input class="number" type="number" name="battle_lounge" value="{{optional($article->street_fighter_v)->battle_lounge}}">
                    @else
                    試合数:<input class="number" type="tel" name="battle_lounge" value="">
                    @endif

                    @if(isset($article->street_fighter_v))
                    勝利数:<input class="number" type="number" name="battle_lounge_win" value="{{optional($article->street_fighter_v)->battle_lounge_win}}"}><br><bzr>
                    @else
                    勝利数:<input class="number" type="number" name="battle_lounge_win" value=""}><br><bzr>
                    @endif
            </div>

        Rank Match<br>
            <div>
                    @if(isset($article->street_fighter_v))
                    試合数:<input class="number" type="number" name="rank_match" value="{{optional($article->street_fighter_v)->rank_match}}">
                    @else
                    試合数:<input class="number" type="number" name="rank_match" value="">
                    @endif
                
                    @if(isset($article->street_fighter_v))
                    勝利数:<input class="number" type="number" name="rank_match_win" value="{{optional($article->street_fighter_v)->rank_match_win}}"}><br><bzr>
                    @else
                    勝利数:<input class="number" type="number" name="rank_match_win" value=""}><br><bzr>
                    @endif
            </div>

            Casual Match<br>
            <div>
        
                    @if(isset($article->street_fighter_v))
                        試合数:<input class="number" type="number" name="casual_match" value="{{optional($article->street_fighter_v)->casual_match}}">
                    @else
                        試合数:<input class="number" type="number" name="casual_match" value="">
                    @endif

                    @if(isset($article->street_fighter_v))
                        勝利数:<input class="number" type="number" name="casual_match_win" value="{{optional($article->street_fighter_v)->casual_match_win}}"}><br><bzr>
                    @else
                        勝利数:<input class="number" type="number" name="casual_match_win" value=""}><br><bzr>
                    @endif
                
            </div>
        </div>
    <div>
    <br>
        <div class="post_sum">
        　      <input class="form_control date_sum"  type="date" name="start_date" size="20" value=""><div>〜</div>
          　    <input class="form_control date_sum"  type="date" name="end_date" size="20" value="">
                    <button id="sum" type="button" class="btn btn-primary">集計</button>
   
        </div>
    
    <br>

    本文<br>
    <textarea class="form_control" cols="50" rows="15" name="body" placeholder="本文を入力してください。">{{isset($input['body']) ? $input['body'] : null}}</textarea><br><br>
  
        
    <input type="submit"  class="btn btn-primary" value="送信" name="post">
    {{--article_id があるか無いかで新規作成か既存編集かを区別する--}}
    <input type="hidden" name="id" value="{{ $id }}">
    {{--CSRFトークンが生成される--}}
    {{ csrf_field() }}
</form>
            @if (isset($id))
            <br><br>
                            <form action="{{ route('admin_delete') }}" method="POST">
                            <input type="submit" class="btn btn-danger btn-sm" value="削除">
                            <input type="hidden" name="id" value="{{ $id }}">
                        {{ csrf_field() }}
                 </form>
                
            @else
                <br>
                <br>
                
            @endif
        </div>
    </div>
</div>    
@endsection

