var markerdata=[];
var markers=[];
var lines=[];
var now_maker;
var flag;
var now_latLng;


$(document).ready(
    function(){ 
        $('#move').hide();
        // 現在地の取得
        navigator.geolocation.getCurrentPosition(function(position) {
        // 緯度経度の取得
        latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        // 地図の作成
        map = new google.maps.Map(document.getElementById('map'), {
            center: latLng,
            zoom: 17
        });
        
        map.addListener('click', function(e) {
            getClickLatLng(e.latLng, map);
        });
    
        // マーカーの追加
       //marker = new google.maps.Marker({
    //      position: latLng,
      //     map: map
      //});
      flag=false;
      
        },
    function(error) {
            alert('位置情報取得に失敗しました '+error.message);
    });
      $('#now').on('click',function(){
          if(flag==false){
          navigator.geolocation.getCurrentPosition(function(position) {
        // 緯度経度の取得
        latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
      now_marker = new google.maps.Marker({
          position: latLng,
           map: map
      });
        now_latLng=latLng;
        
      },
      function(error){
          alert('位置情報取得に失敗しました '+error.message);
      });
      flag=true;
      $('#move').show();
          }
        else
        {
            now_marker.setMap(null);
            flag=false;
            $('#move').hide();
        }
      });
      
      $('#move').on('click',function(){
         
          now_marker.setMap(null);
          
          navigator.geolocation.getCurrentPosition(function(position) {
        // 緯度経度の取得
        latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
      now_marker = new google.maps.Marker({
          position: latLng,
           map: map
      });
        now_latLng=latLng;
        map.panTo(now_latLng); 
      },
      function(error){
          alert('位置情報取得に失敗しました '+error.message);
      });
      
     });
    
        
    $('#clear').on('click',function(){
   
    resetMap(null);
    initArray();

    $("#distance").text("0km");
    
    });
    
    $('#back').on('click',function(){
       
       resetMap(null);
       
       lines.pop();
       markers.pop();
       markerdata.pop();
       
        resetMap(map);
        markerSetting();
        
       map.panTo(markers[markers.length-1].position);
        
        $("#distance").text(distance(markers)+"km");
        
    });
    
    $('#memo').on('click',function(){
    
        if(markers.length>0)
        {
            tmp="";
            for(i=0;i<markers.length;i++)
            {
                tmp=tmp+'|'+markers[i].position.lat()+','+markers[i].position.lng();
            }
        
            tmp=tmp.substring(1);

            $('#data').val(tmp);
            
            mapdata_registar();
            
                var obj = $('#select').children();
                var check=false;
                for( var i=0; i<obj.length; i++ ){
                    if ($('#name').val() == obj.eq(i).text() ) {
                        check=true;
                        break;
                    }
                }
                
                if(check==false)
                {
                    $('#select').append($('<option>').html($('#name').val()).val($('#name').val()));
                }
            
        }
        else
        {
            alert('マーカーを設置してください');
        }
        
    });
    
    $("#set").on('click',function(){
        $('#name').val($("#select option:selected").val());
       mapdata_get();
    });

    $("#delete").on('click',function(){
       mapdata_delete();
       
      $("#select option:selected").remove();
    });

        //データを格納
     //   markerdata.push(latLng);
 //   },
 //   function(error) {
 //           alert('位置情報取得に失敗しました '+error.message);
 //   });
});
function now()
{
    
}
function resetMap(hoge)
{
        for(i=0;i<markers.length;i++)
    {
        markers[i].setMap(hoge);
        
    }
    
    for(i=0;i<lines.length;i++)
    {
          lines[i].setMap(hoge);
    }
    
    
}

function initArray()
{
    
        lines=[];
        markers=[];
        markerdata=[];
    
}

function getClickLatLng(latLng, map) 
{
    // マーカーを設置
    var marker = new google.maps.Marker({
        position: latLng,
        map:map
      });
      

    // データを格納
    markerdata.push(marker.position);
    markers.push(marker);
    // マーカーを設置
    map.panTo(marker.position);
    markerSetting();


	line = new google.maps.Polyline({
		path: markerdata,
		strokeColor: "#FF0000",
		strokeOpacity: .7,
		strokeWeight: 5
	});
	
    lines.push(line);
	line.setMap(map);  
	
$("#distance").text(distance(markers)+"km");
}
function markerSetting()
{
     for(i=0;i<markers.length;i++)
    {
        if(i !=0 & i!=markers.length-1 )    
        markers[i].setMap(null);
        
    }   
}
function distance(markers)
{
    D=0;
     if(markers.length>1)
     {
         
         for(i=0;i<markers.length-1;i++)
        {
            D1=Math.cos(markers[i].position.lat()*Math.PI/180)*
            Math.cos(markers[i+1].position.lat()*Math.PI/180)*
            Math.cos(markers[i+1].position.lng()*Math.PI/180-markers[i].position.lng()*Math.PI/180);
            
            D2=Math.sin(markers[i].position.lat()*Math.PI/180)*
            Math.sin(markers[i+1].position.lat()*Math.PI/180);    

            D=D+6371*Math.acos(D1+D2)*1000;

        }
        
        D=Math.round(D)/1000;
     }
    
    return D;
}

class webApi_mapdata_delete {
       constructor( type, url,ok_msg) {
       
       this.type='POST';
       this.url='map/deletedata';
       this.ok_msg='削除しました';
       
   }

    data()
    {
        var data = {
            name : $("#select option:selected").val()
       };
            return data;
    }
    
    get_ng_msg()
    {
        return 'データ削除に失敗しました';
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

class webApi_mapdata_registar {
       constructor( type, url,ok_msg) {
       
       this.type='POST';
       this.url='map/postdata';
       this.ok_msg='登録しました';
       
   }

    data()
    {
        var data = {
            name : $('#name').val(),
            data : $('#data').val()
       };
            return data;
    }
    
    get_ng_msg()
    {
        return 'データ登録に失敗しました';
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

class webApi_mapdata_get {
       constructor( type, url,ok_msg) {
       
       this.type='GET';
       this.url='map/getdata';
       this.ok_msg='取得しました';
       
   }

    data()
    {
        var data = {
            name : $("#select option:selected").val()
       };
            return data;
    }
    
    get_ng_msg()
    {
        return 'データ取得に失敗しました';
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

    function mapdata_get()
    {
        
    resetMap(null);
        initArray();

        
        var obj= new webApi_mapdata_get();
             // APIを呼び出してDBに保存
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        
            type : obj.get_type(),
            url : obj.get_url(),
            data : obj.data(),
            dataType: 'json',
            async: false,
            success: function(data) {
        if(data != null) {
                     
                       $('#data').val(data["data"]);
             
                               splitData=$('#data').val().split('|');
                        
                        for(i=0;i<splitData.length;++i)
                        {
        
                            tmp_data=splitData[i].split(",");
                        
                            latLng=new google.maps.LatLng(tmp_data[0],tmp_data[1]);
                          
                            getClickLatLng(latLng, map);
                        
                            
                        }     
                                      
                        }
                }
    
        });
        
    }

    function mapdata_registar()
    {
        
        var obj= new webApi_mapdata_registar();
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
    

    
        alert(obj.get_ok_msg());
    

        })
　　.fail(function(xhr, textStatus, errorThrown) {

    alert(obj.get_ng_msg());
           
        });

    }
 
     function mapdata_delete()
    {
        
        var obj= new webApi_mapdata_delete();
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
    

    
        alert(obj.get_ok_msg());
    

        })
　　.fail(function(xhr, textStatus, errorThrown) {

    alert(obj.get_ng_msg());
           
        });

    }
   
