                    <div class="image_view ">
            
                    <?php
                        $i=0;
                        if(preg_match('/iphone|ipod|android/ui', $_SERVER['HTTP_USER_AGENT']) ) // 既知の判定用文字列を検索
                             {
                                 $len=1;
                             }
                             else
                             {
                                 $len=4; 
                            }
                    ?>
           @if(isset($input->image))
                @forelse($input->image as $image)
                        @if($i==0)
                            <div class="image_view_row">
                        @endif
                    <div class="image_view_cell">
                        <img src="{{ asset('/storage/app/' . $image->name) }}" class="image_base" width="130" heigh"130"/>
                            
                            <form method="POST" action="{{route('admin_set_img')}}">
                                <input type="hidden" name="image_id" value="{{$image->id}}">
                                
                                <input type="hidden" name="id" value="{{Auth::id()}}">
                                <input type="submit" value="設定">
                            </form>
                    </div>                          
                        
                        
                        @if($i==$len)
                          </div>
                            <?php $i=0; ?>
                        @else
 
                    @if($input->image->count()==1)
                            </div>
                            @endif

                            <?php $i+=1; ?>
                        @endif
                    
                    @empty
                      <p>イメージがありません</p>
                   @endforelse
                 @else
                  <p>イメージがありません</p>
                 @endif
                   </div>
               