<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ブログ記事投稿フォーム</title>
    {{--asset ヘルパー関数を使うと public/ 配下ファイルへのURLを生成してくれる--}}

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">   
    <link rel="stylesheet" href="{{ asset('/public/css/blog.css') }}">
</head>

<body>
    
    <h2>ブログ記事投稿・編集</h2>
    
    @if(session('message'))
        <div class="alert alert-success">
        
        {{session('message')}}
        
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>
                    {{$error}}
                </li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{route('admin_post')}}">
    日付<br>
    <input class="form_control" name="post_date" size="20" value="{{isset($input['post_date']) ? $input['post_date'] : null}}" placeholder="日付を入力して下さい。"><br><br>

    タイトル<br>
    <input class=form_control" name="title" value="{{isset($input['title']) ? $input['title'] : null}}" placeholder="タイトルを入力して下さい。"><br><br>

    本文<br>
    <textarea class="form_control" cols="50" rows="15" name="body" placeholder="本文を入力してください。">{{isset($input['body']) ? $input['body'] : null}}</textarea><br>

    <input type="submit" value="送信">
    {{--article_id があるか無いかで新規作成か既存編集かを区別する--}}
    <input type="hidden" name="id" value="{{ $id }}">
    {{--CSRFトークンが生成される--}}
    {{ csrf_field() }}
</form>
    <!-- jqueryの読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>