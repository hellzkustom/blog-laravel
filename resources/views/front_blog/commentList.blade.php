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

        オンライン対戦後反省会<input type="checkbox" name="id17" value="17"><br>
        オンライン対戦後反省会まとめ<input type="checkbox" name="id19" value="19"><br>
        オフライントレーニング<input type="checkbox" name="id20" value="20" ><br>
        格ゲーオフ会参加<input type="checkbox" name="id23" value="23" ><br>
        <br>
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
            {{ $list->links() }}
            </div>
            @include('front_blog.right_column')
            </div>
@endsection