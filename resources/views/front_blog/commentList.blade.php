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
                @forelse($list as $comment)
                 {!! nl2br(e($comment->updated_at)) !!}
                 <br>
                
                 {!! nl2br(e($comment->body)) !!}
                 <br>
                                 @empty
                    <p>記事がありません</p>
                @endforelse

            </div>
            @include('front_blog.right_column')
            </div>
@endsection