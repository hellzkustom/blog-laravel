$(function () {
   
           $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   
    
  //  const target_modal_edit='#category_submit';
//    const target_modal_delete='#category_delete';
    
    
class webApi{

   constructor( type, url,ok_msg ) {
       
       this.type='POST';
       this.url='category/edit';
       this.ok_msg='<span>正常に処理が完了しました</span>';

       
   }
    data()
    {
      var category_id = $('input[name=category_id]').val();
      category_id = (category_id) ? category_id : null;

        var data={
                id            : $('input[name=category_id]').val(),//)? $('input[name=category_id]').val() : null,
                name       : $('input[name=name]').val(),
                display_order: $('input[name=display_order]').val(),
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
    
    get_ng_msg(xhr){
    
            var error_message = '';
            
            $.each(xhr.responseJSON.errors, function(element, message_array) {
                    
               $.each(message_array, function(index, message) {
                    
                  error_message += message + '<br>';
               });
            });

        
        return '<span> '+ error_message+'</span>';
    }
}



class webApi_category_delete extends webApi{
    
   constructor( type, url,ok_msg) {
       
       super(type);
       this.url='category/delete';
       this.ok_msg='<span>削除しました</span>';
       
   }

    data()
    {
        var data = {
            id : $('input[name=category_id]').val(),
       };
            return data;
    }
    
    get_ng_msg(xhr)
    {
        return '<span>削除に失敗しました</span>';
    }

}



class webApi_comment_edit extends webApi{
    
       constructor( type, url,ok_mesg ) {
       
       super(type);
       this.url='../../comment/post';
       this.ok_msg='<span>正常に処理が完了しました</span>';

       
   }
    data()
    {
        var article_id = $('input[name=article_id]').val();
        article_id = (article_id) ? article_id : null;
    
        var data = {
            id          : article_id,
            name        : $('input[name=name]').val(),
            body        : $('textarea[name=body]').val(),
            user_id     :$('input[name=user_id]').val(),
            
        };
        return data;
    }
    
}

    $('#category_submit').on('click', category_edit_action);
     $('#category_delete').on('click', category_delete_action);
     
    $('#comment_submit').on('click', comment_edit_action);
    
    
    //$('#img_submit').on('click', img_post_action);


    // 保存ボタン押下時
   function category_edit_action(){

        var obj = new webApi();
    ajax_action(obj);
   }
    
    // 削除ボタン押下時
    function category_delete_action() {
        
       var obj = new webApi_category_delete();
    
        ajax_action(obj);
    }
    
    function comment_edit_action() {
        
       var obj = new webApi_comment_edit();
        ajax_action(obj);
    }


    function ajax_action(obj)
    {
        
        // APIを呼び出してDBに保存
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        
            type : obj.get_type(),
            url : obj.get_url(),
            data : obj.data(),
            dataType: 'json',
        }).done(function(data, textStatus, jqXHR) {
    
            // 正常時 結果表示
            $('#api_result').html(obj.get_ok_msg())
                .removeClass()
                .addClass('alert alert-success show');
            // 少しせこいが、リロードして変更が反映された画面を表示する
         //   location.reload();  


        })
　　.fail(function(xhr, textStatus, errorThrown) {

             //エラーメッセージ表示
            $('#api_result').html(obj.get_ng_msg(xhr))
               .removeClass()
                .addClass('alert alert-danger show');
        });

    }
    
    

});

