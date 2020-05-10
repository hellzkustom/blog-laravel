@extends('front_blog.app')
@section('title', '私のブログ')

@section('head')
    {{--jQuery は下記のファイルに記述し読み込むようにする--}}
    <script src="{{ asset('/js/comment.js') }}"></script>
@endsection

@section('main')
        <div class="col-md-7 col-md-offset-1">
            <div class="panel panel-default">
                    <div class="panel-heading">
                        {{--post_date は日付ミューテタに設定してあるので、自動的に Carbon インスタンスにキャストされる--}}
                        <h3 class="panel-title">{{ $article->post_date->format('Y/m/d(D)') }}　{{ $article->title }}</h3>
                    </div>
                            <div class="panel-body">

                            <h5>本文</h5>
                                                        
                                
                                   {{--nl2br 関数で改行文字を <br> に変換する。これをエスケープせずに表示させるため {!! !!} で囲む--}}
                                    {{--ただし、このまま出力するととても危険なので e 関数で htmlspecialchars 関数を通しておく--}}
                                    {!! nl2br(e($article->body)) !!}
                                            <br><br>
                                       <h5> <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#commentModal">
                                            コメント
                                        </button>

                            </h5>
                                @forelse($article->comment->sortByDesc('id') as $comment)
                                <div>
                                {!! nl2br(e($comment->body)) !!}
                                <br>
                                投稿者{!! nl2br(e($comment->name)) !!}<br>投稿時間{!! nl2br(e($comment->updated_at)) !!}
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
                                 <input class="form_control" name="name" value="" placeholder="名前を入力して下さい。"><br><br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">コメント本文</label>
                             <div class="col-sm-10">
                                   <textarea class="form_control" cols="50" rows="15" name="body" placeholder="本文を入力してください。"></textarea>
                             </div>
                        </div>
                    
                    </form>
                </div>

                <div class="modal-footer">
            

                    <button type="button" id="comment_submit" class="btn btn-primary">投稿</button>
                   <input type="hidden" name="article_id" value="{{ $article->id }}">

                </div>

            </div>
        </div>
    </div>








@endsection