@extends('app')
@section('title', 'eふぁいたーっす!')

@section('head')
    {{--jQuery は下記のファイルに記述し読み込むようにする--}}
    <script src="{{ asset('/js/ajax.js') }}"></script>
    <script src="{{ asset('/js/comment.js') }}"></script>
@endsection

@section('body')
    <div class="container">
        
          <div class="row" name="main">
            <div class="col-md-10 col-md-offset-1">
                <h2><a href="{{ route('front_index') }}">eふぁいたーっす!</a></h2>
             @include('error')
            </div>
        </div>

<div class="col-md-8 text-right">
    @if(Auth::check() && Auth::user()->admin==true)
        <a href="{{ route('admin_form', ['id' => $article->id]) }}">編集</a>
    @endif
</div>
<br>

        <div class="col-md-7 col-md-offset-1">
            <div class="panel panel-default">
                    <div class="panel-heading">
                        {{--post_date は日付ミューテタに設定してあるので、自動的に Carbon インスタンスにキャストされる--}}
                        <h3 class="panel-title">{{ $article->post_date->format('Y/m/d(D)') }}　{{ $article->title }}</h3>
                    </div>
                            <div class="panel-body">
@php
$ck=false;
@endphp
                                           @include("image_view_index")
                      @if($article->category_id==17)
                      
                            @if( optional($article->street_fighter_v)->battle_lounge)
                                <div>
                               ラウンジ試合数:{{$article->street_fighter_v->battle_lounge}}&nbsp;
                               勝利数:{{$article->street_fighter_v->battle_lounge_win}}&nbsp;
                               勝率:{{sprintf("%.3f",$article->street_fighter_v->battle_lounge_win/$article->street_fighter_v->battle_lounge)}}
                            @php
                            $ck=true;
                            @endphp
                            </div>
                            @endif
                            @if( optional($article->street_fighter_v)->rank_match)
                            <div>
                                ランクマ試合数:{{$article->street_fighter_v->rank_match}}&nbsp;
                               勝利数:{{$article->street_fighter_v->rank_match_win}}&nbsp;
                               勝率:{{sprintf("%.3f",$article->street_fighter_v->rank_match_win/$article->street_fighter_v->rank_match)}}
                            @php
                            $ck=true;
                            @endphp
                            </div>
                            @endif
                            @if( optional($article->street_fighter_v)->casual_match)
                            <div>
                                カジュアル試合数:{{$article->street_fighter_v->casual_match}}&nbsp;
                               勝利数:{{$article->street_fighter_v->casual_match_win}}&nbsp;
                               勝率:{{sprintf("%.3f",$article->street_fighter_v->casual_match_win/$article->street_fighter_v->casual_match)}}
                            @php
                            $ck=true;
                            @endphp
                            </div>
                            
                            @endif
                            
                            @if($ck==true)
                            
                            総括
                            <br>
                            @endif
                                @endif
                                   {{--nl2br 関数で改行文字を <br> に変換する。これをエスケープせずに表示させるため {!! !!} で囲む--}}
                                    {{--ただし、このまま出力するととても危険なので e 関数で htmlspecialchars 関数を通しておく--}}
                                    {!! nl2br(e($article->body)) !!}
                                            <br><br>
                                       <h5>
                                            @if(Auth::check())
                                            
                                                   <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#commentModal">コメント</button>
                                            @else
                                                    コメント
                                            @endif
                                        </h5>
                                @forelse($article->comment->sortByDesc('id') as $comment)
                                <div  class="comment">
                                {!! nl2br(e($comment->body)) !!}
                                <br>
                                </div>
                                <div>
                                    <div class="comment">
                                     <div class="box">   
                                        投稿者{!! nl2br(e($comment->name)) !!}&nbsp;&nbsp;投稿時間{!! nl2br(e($comment->updated_at)) !!}
                                    </div>
                                    @if($comment->user_id==Auth::id())
                                    <div class="box">
                                            <form method="POST"  action="{{route('commentDelete')}}">
                                                <input type="hidden" name="id" value="{{$comment->id}}">
                                                <input type="hidden" name="article_id" value="{{$article->id}}">
                                            <input type="submit" class="btn btn-danger btn-sm" value="削除">
                                            </form>
                                    </div>
                                    @endif
                                    </div>
                                </div>
                                @empty
                                    <p>コメントがありません</p>
                                @endforelse                             
                             
                        
                            </div>
                    <div class="panel-footer text-right">
                                            <a href="{{ route('front_index', ['category_id' => $article->category_id]) }}">
                        {{ $article->category->name}}
                    </a>
                        
                                            &nbsp;&nbsp;
                        {{--updated_at も日付ミューテタに設定してあるので Carbon インスタンスにキャストされる--}}
                        {{ $article->updated_at->format('Y/m/d H:i:s') }}
                    </div>
            </div>

</div>
            @include('front_blog.right_column')
            </div>

<!-- モーダル・ダイアログ -->
    <div class="modal fade" id="commentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span>×</span>
                    </button>
                    <h4 class="modal-title">コメント投稿</h4>
                </div>

                <div class="modal-body">
                    {{--API 通信結果表示部分--}}
                    <div id="api_result" class="hidden"></div>

                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 ">コメント投稿者</label>
                             <div class="col-sm-10">
                                 <input class="form_control" name="name" readonly="readonly" value="{{isset(Auth::user()->name) ? Auth::user()->name:null}}" placeholder="名前を入力して下さい。"><br><br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">コメント本文</label>
                             <div class="col-sm-10">
                                   <textarea class="form_control" cols="20" rows="15" name="body" placeholder="本文を入力してください。"></textarea>
                             </div>
                        </div>
                    
                    </form>
                </div>

                <div class="modal-footer">
            

                    <button type="button" id="comment_submit" class="btn btn-primary">投稿</button>
                   <input type="hidden" name="article_id" value="{{ isset($article)? $article->id:null }}">
                   <input type="hidden" name="user_id" value="{{Auth::id()}}">

                </div>

            </div>
        </div>
    </div>
   
    



            
            




@endsection