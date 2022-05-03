@extends('app')
@section('title', 'eふぁいたーっす!')

@section('body')
<h2><a href="{{ route('front_index') }}">eふぁいたーっす!</a></h2>
<div class="col-md-7 col-md-offset-1">
<h4>トレモ風景～</h4>



<div class="col-md-7 col-md-offset-1">

    @foreach ($twitter as $twitter)
        {!! $twitter[0] !!}<br>
    @endforeach
</div>

      
            
@include('front_blog.right_column')
@endsection