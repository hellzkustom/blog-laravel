{{--右カラム--}}
<div class="col-md-3">
        <div class="panel panel-default ">
            <div class="panel-heading">
                <h3 class="panel-title">自己紹介</h3>
            </div>
        
            <div class="list-group">
                
                
            @if(isset($introduction->image_user->name))
               <div class="div_img_intro">
                 <img src="{{ asset('/storage/app/'.$introduction->image_user->name)  }}" width="96" heigh"96"/>
              </div>
           @endif
                <li class="list-group-item list_intro">
                    <div><span class="name_space">name</span>:{{$introduction['name']}}</div>
                    <div class="comment">comment:</div> 
                    <div class="comment">{!!nl2br(e($introduction['comment']))!!}</div>
                </li>
                
            </div>

    </div>

@if(strcmp(url()->current(),route('sf5lounge'))==0 )
       <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">リンク</h3>
        </div>
        <div class="panel-body">
        <a href="{{ route('front_index') }}">eふぁいたーっす!</a><br>
        </div>
    </div>
   
  
  
@else  
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">直近の成績</h3>
        </div>
            <div class="panel-body">
                @if(count($results)>0)
                    @foreach($results as $result)
                        {{$result['character']}}<br>        
                        期間{{$result['start_date']}}-{{$result['end_date']}}<br>
                        現在のLP{{$result['lp_end']}} 増減{{$result['lp_end']-$result['lp_start']}}<br>
                            @if(isset($result['rank_match']))
                                ランクマ試合数{{$result['rank_match']}} 勝利数{{$result['rank_match_win']}}<br>
                            @else
                                ランクマ試合なし<br>
                            @endif
                            @if(isset($result['casual_match']))
                                カジュアル試合数{{$result['casual_match']}} 勝利数{{$result['casual_match_win']}}<br>
                            @else
                                カジュアル試合なし<br>
                            @endif
                            {{-- @if(isset($result['battle_lounge']))
                                ラウンジ試合数{{$result['battle_lounge']}} 勝利数{{$result['battle_lounge_win']}}<br>
                            @else
                                ラウンジ試合なし<br>
                            @endif --}}
                            <br>
                    @endforeach
                @else
                    対戦なし<br>
                @endif
        
                 {{--  @if(strcmp(url()->current(),route('dayily_post'))!=0 )
                    <a href="{{ route('dayily_post') }}">トレモ風景～</a>
                    @endif --}}
        </div>
        
    </div>
<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">ランク状況</h3>
        </div>
            <div class="panel-body">
                @if(count($ranks)>0)
                    @foreach($ranks as $rank)
                        {{$rank['character']}} LP{{$rank['lp_end']}}  {{$rank['rank']}} <br>
                    @endforeach
                @else
                    対戦なし<br>
                @endif
        </div>
    </div>
     <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">リンク</h3>
        </div>
        <div class="panel-body">
      {{--  <a href="{{ route('sf5lounge') }}">SF5ラウンジ募集</a> --}}
        @if(strcmp(url()->current(),route('commentList'))!=0 )
        <a href="{{ route('commentList') }}">ノート</a>
        @endif
       {{--  @if(strcmp(url()->current(),route('drift_post'))!=0 )
        <a href="{{ route('drift_post') }}">ドリフト~</a>
        @endif        --}}
        <br>
         <a href="https://sf6.halipe.co/myrank/2634206553">LP推移</a>
         
          @if(strcmp(url()->current(),route('result'))!=0 )
        <br>
        <a href="https://stronger-a-day.solunita.net/SF6">ヒトツヨ</a>
        <br>
        <a href="{{ route('result') }}">戦績詳細</a>
        @endif               
         
         
        </div>
        
    </div>
   
       <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">カテゴリー</h3>
        </div>
        <div class="panel-body">
            <ul class="category_archive">
                @forelse($category_list as $category)
                    <li>
                        <a href="{{ route('front_index', ['category_id' => $category->id,]) }}">
                            {{ $category->name }}
                        </a>            
                    </li>
                @empty
                    <p>カテゴリーがありません</p>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">月別アーカイブ</h3>
        </div>
        <div class="panel-body">
            <ul class="monthly_archive">
                @forelse($month_list as $value)
                    <li>
                        <a href="{{ route('front_index', ['year' => $value->year, 'month' => $value->month]) }}">
                            {{ $value->year_month }}
                        </a>
                    </li>
                @empty
                    <p>記事がありません</p>
                @endforelse
            </ul>
        </div>
    </div>
@endif
</div>