@extends('app')
@section('title','eふぁいたーっす')

@section('head')
    {{--jQuery は下記のファイルに記述し読み込むようにする--}}

   <script src="{{ asset('/js/ajax.js') }}"></script>    
    <script src="{{ asset('/js/form.js') }}"></script>    
@endsection

@section('body')
    <div class="container">
        <div class="row" name="main">
            <div class="col-md-10 col-md-offset-1">
                <h2>スト6 戦績</h2>

                ランクマ履歴<br>
                <table>
                    <tr>
                        <th>キャラクター</th>
                        <th>ランク</th>
                        <th>初戦日</th>
                        <th>昇格日</th>
                        <th>対戦数</th>
                        <th>勝利数</th>
                        <th>勝率</th>
                    </tr>
   
                    @foreach( $datas as $data)
                    <tr>
                      <td>{{$data['character']}}</td>
                      <td>{{$data['rank']}}</td>
                      <td>{{$data['start_date']}}</td>
                      <td>{{$data['lank_up_date']}}</td>
                      <td>{{$data['match']}}</td>
                      <td>{{$data['match_win']}}</td>
                      <td>{{$data['rate']}}</td>
                    </tr>
                    @endforeach
                </table>           
                
                <br>
                詳細
                <br>
                <br>
                <select class="form_control" name="character" id="character">
                <option value="0" selected>キャラクター選択してください</option>
 
                @if(isset($article->street_fighter_v))
                  @foreach($character_list as $character)

                       @if($character== optional($article->street_fighter_v)->character)
                          <option value="{{$character}}" selected>{{optional($article->street_fighter_v)->character}}</option>
	                   @else
                          <option value="{{$character}}">{{$character}}</option>
                      @endif	           
	           
                    @endforeach 
                @else
                    @foreach($character_list as $character)
	                       <option value="{{$character}}">{{$character}}</option>
	               @endforeach
                @endif
                </select>
                <br>
                <br>
                                

    <br>
        <div class="post_sum">
        　      <input class="form_control date_sum"  type="date" name="start_date" size="20" value=""><div>〜</div>
          　    <input class="form_control date_sum"  type="date" name="end_date" size="20" value="">
                    <button id="sum" type="button" class="btn btn-primary">集計</button>
   
        </div>
    
    <br>

    本文<br>
    <textarea class="form_control" cols="50" rows="15" name="body" placeholder="本文を入力してください。">{{isset($input['body']) ? $input['body'] : null}}</textarea><br><br>
  
        
                 <br><a href="{{ route('front_index') }}">トップに戻る</a><br><br>
 
        </div>
    </div>
</div>    
@endsection

