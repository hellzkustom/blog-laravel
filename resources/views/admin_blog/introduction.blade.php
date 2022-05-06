@extends('app')
@section('title','自己紹介編集')

@section('head')
    {{--jQuery は下記のファイルに記述し読み込むようにする--}}
@endsection


@section('body')
    <div class="container">
        <div class="row" name="main">
            <div class="col-md-10 col-md-offset-1">
    <h2>自己紹介編集</h2>

    @if(session('message'))
        <div class="alert alert-success">
        
        {{session('message')}}
        
        </div>
    @endif

    @include('error')
   
   @if(isset($imgpath['name']))
   
             <img src="{{ asset('/storage/app/' . $imgpath['name']) }}" width="130" heigh"130"/>
  
   
   @endif
    <form method="POST" action="{{route('admin_introduction_edit')}}">

    name<br>
    <input class="form_control" name="name" value="{{isset($input['name']) ? $input['name'] : null}}" placeholder="nameを入力して下さい。"><br><br>

    comment<br>
    <textarea class="form_control" cols="50" rows="2" name="comment" placeholder="commnetを入力してください。">{{isset($input['comment']) ? $input['comment']: null}}</textarea>

    <br><br>
    <input type="submit" class="btn btn-primary" value="送信">
    {{--article_id があるか無いかで新規作成か既存編集かを区別する--}}

    {{--CSRFトークンが生成される--}}
    {{ csrf_field() }}
</form>
@if(Auth::user()->admin==true)
  
    <br><a href="{{ route('admin_list') }}">リストに戻る</a><br><br>
                
@else

    <br><a href="{{ route('front_index') }}">トップに戻る</a><br><br>

@endif
        

    <h2>画像投稿</h2>
        <form method="POST" action="{{route('admin_post_img')}}" enctype="multipart/form-data" class="image_form">
                     <div class="post_intro">   <input type="file" name="name" accept="image/*" >
                                      <input type="hidden" name="user_id" value="{{Auth::id()}}">
                     
                     </div>
                     
                    <div class="post_intro"><input type="submit" class="btn btn-primary" value="投稿"></input>
                    </div>
        </form>
            
             @include('image_view')
           
           </div>
     </div>
</div>          
           
@endsection

