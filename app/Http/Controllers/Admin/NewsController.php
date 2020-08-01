<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function add()
  {
      return view('admin.news.create');
      // 何故、ｽﾗｯｼｭではなく.で区切っているのか？
      // →指定されたbladeファイルを画面に変換して返す
      // →resourcesの中のviewsの中のbladeファイルの位置を示す意味がある。
      // →Laravelではbladeファイルの位置を示すのに.を用いる。
      // →画面に表示させるためのもの。
  }
  
    public function create(Request $request)
  {
      // admin/news/createにリダイレクトする
      return redirect('admin/news/create');
      // redirectは、処理を終えた後に、Routingに処理を差し戻す。URLなのでｽﾗｯｼｭ区切り。
  }  
  
}
