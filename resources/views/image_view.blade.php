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
           {{$page=$input->image()->orderby('id','desc')->paginate(10)}}
                @forelse($page as $image)
                        @if($i==0)
                            <div class="image_view_row">
                        @endif
                    <div class="image_view_cell">
                        <img src="{{ asset('/storage/app/' . $image->name) }}" class="image_base" width="130" heigh"130"/>
                            
                            <form method="POST" action="{{route('admin_set_img')}}">
                                
                                <input type="hidden" name="image_id" value="{{$image->id}}">
                                <input type="hidden" name="id" value="{{Auth::id()}}">
                                
                                <input type="submit" class="btn btn-primary" value="設定">
                            </form>
                            <br>
                            <form method="POST" action="{{route('admin_delete_img')}}">
                                
                                <input type="hidden" name="image_id" value="{{$image->id}}">
                                <input type="hidden" name="name" value="{{$image->name}}">
                                
                                <input type="submit" class="btn btn-danger btn-sm" value="削除">
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
               {{ $page->links() }}