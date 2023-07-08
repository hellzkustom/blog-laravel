<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class street_fighter_v extends Model
{
    protected $primaryKey='id';
    protected $fillable=['article_id','battle_lounge', 'battle_lounge_win',
    'rank_match','rank_match_win','casual_match','casual_match_win','lp','character'];
    protected $dates=['created_at','updated_at'];
    
    public function article()
      {
          return $this->belongsTo('App\Article');
      }
      
      
   
}
