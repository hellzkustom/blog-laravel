@extends('app')
@section('title','SF5ラウンジ募集情報')

@section('body')
<h2>SF5ラウンジ募集情報</h2>
<div class="col-md-7 col-md-offset-1">
<h4>ツイッターでの募集状況は以下の通りです〜。</h4>
</div>

<div class="col-md-7 col-md-offset-1">

    @foreach ($twitter as $twitter)
        {!! $twitter[0] !!}<br>
    @endforeach
</div>
@include('front_blog.right_column')
@endsection