<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
  protected $guarded = array('id');

  public static $rules = array(
    'news_id' => 'required',
    'edited_at' => 'required',
  );
    
  public function news() {
    return $this->belongsTo('App\News');
  }

//編集履歴は、NewsModelに帰属する、という意味。履歴の中から、自分の編集した記事を引っ張る。

}