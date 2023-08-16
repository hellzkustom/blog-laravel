@extends('app')
@section('title', 'eふぁいたーっす!')

@section('body')

    <div class="container">
          <div class="row" name="main">
            <div class="col-md-10 col-md-offset-1">
                <h2><a href="{{ route('front_index') }}">eふぁいたーっす!</a></h2>
                        @include('error')          
            </div>
        </div>

<br>

<div class="col-md-7 col-md-offset-1">
<h4>ノート</h4>
</div>


        
            <div class="col-md-7 col-md-offset-1">
                {{--forelse ディレクティブを使うと、データがあるときはループし、無いときは @empty 以下を実行する--}}
                
    <form method="GET" action="/comment/list">
        @csrf
 
        <input type="checkbox" name="id17" value="17" {{in_array(17,$checked_items) ? 'checked' : ''}}>オンライン対戦後反省会<br>
        <input type="checkbox" name="id19" value="19" {{in_array(19,$checked_items) ? 'checked' : ''}}>オンライン対戦後反省会まとめ<br>
        <input type="checkbox" name="id20" value="20" {{in_array(20,$checked_items) ? 'checked' : ''}} >オフライントレーニング<br>
        <input type="checkbox" name="id23" value="23" {{in_array(23,$checked_items) ? 'checked' : ''}}>格ゲーオフ会参加<br>
        <input type="checkbox" name="id24" value="24" {{in_array("word",$checked_items) ? 'checked' : ''}}>ブログ仕様<br>
        <br>
        コメント投稿日
        <br>
        <input type="date" name="start_comment" value="{{$start_comment}}"> - <input type="date" name="end_comment" value="{{$end_comment}}"><br><br>
        記事投稿日
        <br>
        <input type="date" name="start_article" value="{{$start_article}}"> - <input type="date" name="end_article" value="{{$end_article}}"><br><br>

        キーワード
        <br>
        <input type="text" name="word" value="{{$word}}"><br><br>
        <input type="submit" name="submit" class="btn btn-primary" value="検索" />
    
        </form>
        <br>
                @forelse($list as $comment)
                        <a href="{{ route('front_article', ['id' => $comment->article_id]) }}">
                        {{$comment->title}}
                        </a>
        
                         <br>
                                      
                                      
                         {!! nl2br(e($comment->body)) !!}
                         <br>
                         {!! nl2br(e($comment->updated_at)) !!}
                         <br>
                         <br>
                @empty
                    <p>コメントがありません</p>
                @endforelse
            {{ $list->appends(request()->query())->links() }}
            </div>
            @include('front_blog.right_column')
            </div>
@endsection