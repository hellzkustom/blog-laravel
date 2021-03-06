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
</div>