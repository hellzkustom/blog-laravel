@extends('admin_blog.app')
@section('title','ブログ記事一覧')
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>ブログ記事一覧</h2>

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                    <br>
                @endif

                <a href="{{ route('admin_form') }}">
                    <span class="btn btn-primary btn-sm">新規記事作成</span>
                </a>
                
                                <a href="{{ route('admin_category') }}">
                    <span class="btn btn-primary btn-sm">カテゴリ編集</span>
                </a>
                <br>

                @if (count($list) > 0)
                    <br>

                    {{--links メソッドでページングが生成される。しかも生成されるHTMLは Bootstrap と互換性がある--}}
                    {{ $list->links() }}
                    <table class="table table-striped">
                        <tr>
                            <th width="120px">記事番号</th>
                            <th width="120px">日付</th>
                            <th>タイトル</th>
                        </tr>

                        {{--このまま foreach ループにかけることができる--}}
                        @foreach ($list as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>{{ $article->post_date_text }}</td>
                                <td>
                                    <a href="{{ route('admin_form', ['id' => $article->id]) }}">
                                        {{ $article->title }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <br>
                    <p>記事がありません。</p>
                @endif
            </div>
        </div><br><a href="{{ route('front_index') }}">トップに戻る</a><br><br>

    </div>
@endsection