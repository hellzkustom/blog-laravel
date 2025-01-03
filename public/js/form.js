$(function () {
    $('#sum').click(get_data_street_fighter_v);
    $('#check_lp').click(get_latest_lp);
    $('#category').change(set_title);
    
    
 class webApi_sum_street_fighter_v {
    
       constructor( type, url,ok_mesg ) {
       
       this.type='GET';
       this.url='https://laravel-blog.paiza-user-basic.cloud/admin/post/get_data_street_fighter_v';//'post/get_data_street_fighter_v';
       this.ok_msg='<span>正常に処理が完了しました</span>';

       
        }
     data()
    {
       
            var data = {
        
            start_date   :$('input[name=start_date]').val(),
            end_date     :$('input[name=end_date]').val(),
            character   :$('select[name=character]').val(),
        };
        return data;
    }    
    get_url(){
        return this.url;
    }
    get_type(){
        return this.type;
    }
    
    get_ok_msg(){
        return this.ok_msg;
    }
}
 
class webApi_get_latest_lp
{
           constructor( type, url,ok_mesg ) {
       
       this.type='GET';
       this.url='https://laravel-blog.paiza-user-basic.cloud/admin/post/get_latest_lp';//'post/get_data_street_fighter_v';
       this.ok_msg='<span>正常に処理が完了しました</span>';

       
        }
            get_url(){
        return this.url;
    }
    
    
                        data()
    {
       
            var data = {
        
            character   :$('select[name=character]').val(),
        
            
        };
        return data;
    }
    get_type(){
        return this.type;
    }
    
    get_ok_msg(){
        return this.ok_msg;
    }
}
 
class webApi_get_title_count
{
           constructor( type, url,ok_mesg ) {
       
       this.type='GET';
       this.url='https://laravel-blog.paiza-user-basic.cloud/admin/post/get_title_count';//'post/get_data_street_fighter_v';
       this.ok_msg='<span>正常に処理が完了しました</span>';

       
        }
        
        
                    data()
    {
       
            var data = {
        
            category_id   :$('select[name=category_id]').val(),
        
            
        };
        return data;
    }
 
            get_url(){
        return this.url;
    }
    get_type(){
        return this.type;
    }
    
    get_ok_msg(){
        return this.ok_msg;
    }
} 
 
 function get_data_street_fighter_v(){
            var obj = new webApi_sum_street_fighter_v();
        ajax_action_get(obj);
  //  $(this).text((new Date()).toLocaleString());
}

function get_latest_lp(){

            var obj = new webApi_get_latest_lp();
        ajax_action_get_lp(obj);
  //  $(this).text((new Date()).toLocaleString());
}

function set_title(){
    
    if($('select[name=category_id]').val()==17||$('select[name=category_id]').val()==19||$('select[name=category_id]').val()==20||$('select[name=category_id]').val()==23)
    {
                var obj = new webApi_get_title_count();
                ajax_action_get_title_count(obj);
    }
    
}


  function ajax_action_get(obj)
    {
    
    if(obj.data().start_date=="" ||obj.data().end_date=="" )
    {
        alert("年月日を指定してください");
        return;
    }   
    
    if(obj.data().start_date>obj.data().end_date )
    {
        alert("設定値が不適切です");
        return;
    }
    
    if(obj.data().character=="0")
    {
            //    alert("キャラクターを選択してください");
        //return;
    }
    
        // APIを呼び出してDBに保存
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        
            type : obj.get_type(),
            url : obj.get_url(),
            data:obj.data(),
            dataType: 'json',
        }).done(function(data, textStatus, jqXHR) {
        
      
        
        data.forEach(function(value){
        var msg="";
        msg+=value["character"]+"\n";
        var rate;
            if(value["battle_lounge"]>0)
            {
                rate=value["battle_lounge_win"]/value["battle_lounge"];
                msg+="ラウンジ試合数:"+value["battle_lounge"]+" 勝利数:"+value["battle_lounge_win"]+" 勝率:"+String(rate).substr(0,5)+"\n";
            //    $('input[name=battle_lounge]').val(data["battle_lounge"]);
            //    $('input[name=battle_lounge_win]').val(data["battle_lounge_win"]);
            }
            if(value["rank_match"]>0)
            {
                rate=value["rank_match_win"]/value["rank_match"];
                msg+="ランクマ試合数:"+value["rank_match"]+" 勝利数:"+value["rank_match_win"]+" 勝率:"+String(rate).substr(0,5)+"\n";
            
            //$('input[name=rank_match]').val(data["rank_match"]);
            }
            if(value["casual_match"]>0)
            {            
               rate=value["casual_match_win"]/value["casual_match"];
                msg+="カジュ"+"アル試合数:"+value["casual_match"]+" 勝利数:"+value["casual_match_win"]+" 勝率:"+String(rate).substr(0,5)+"\n";
            
            //$('input[name=casual_match]').val(data["casual_match"]);
            //$('input[name=casual_match_win]').val(data["casual_match_win"]);
            
            }
            msg+="総括:\n"
            msg+="現在のLP:"+value["lp_end"];
            if((value["lp_end"]-value["lp_start"])>0)
                msg+=" 増減:+"+(value["lp_end"]-value["lp_start"])+"\n";
            else
            msg+=" 増減:"+(value["lp_end"]-value["lp_start"])+"\n";
            
            msg+="期間:"+$('input[name=start_date]').val()+"~"+$('input[name=end_date]').val()+"\n\n";
 
            $('textarea[name=body]').val(msg+$('textarea[name=body]').val());
         });             
        })
　　.fail(function(xhr, textStatus, errorThrown) {
        alert(xhr.status);
       });

    }
    
    function ajax_action_get_lp(obj)
    {
        if(obj.data().character=="0")
    {
                alert("キャラクターを選択してください");
        return;
    }

        // APIを呼び出してDBに保存
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        
            type : obj.get_type(),
            url : obj.get_url(),
            data:obj.data(),
            dataType: 'json',
        }).done(function(data, textStatus, jqXHR) {
        
        var msg="";
        msg+=data["character"]+'\n';
        msg+="現在のLP:"+$('input[name=lp]').val();
            if(($('input[name=lp]').val()-data["latest_lp"]>0))
                    msg+=" LP増減:+"+($('input[name=lp]').val()-data["latest_lp"])+"\n";
            else 
                    msg+=" LP増減:"+($('input[name=lp]').val()-data["latest_lp"])+"\n";


            
            $('textarea[name=body]').val(msg+$('textarea[name=body]').val());
            
        })
　　.fail(function(xhr, textStatus, errorThrown) {
        alert(xhr.status);
       });

    }
    
     function ajax_action_get_title_count(obj)
    {
    
    
        // APIを呼び出してDBに保存
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        
            type : obj.get_type(),
            url : obj.get_url(),
            data:obj.data(),
            dataType: 'json',
        }).done(function(data, textStatus, jqXHR) {
            
            $('input[name=title]').val($('[name=category_id] option:selected').text()+"Vol"+(data["count"]+1));
            
        })
　　.fail(function(xhr, textStatus, errorThrown) {
        alert(xhr.status);
       });

    }
 
  
});