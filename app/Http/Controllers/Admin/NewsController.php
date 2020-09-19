<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
// Illuminate\Requestは、Laravelに備え付けである。無条件でActionに渡る。$requestはRequestのインスタンス。
use App\Http\Controllers\Controller;
use App\News;
use Carbon\Carbon;
use App\History;

class NewsController extends Controller
{
  public function add()
  {
      return view('admin.news.create');
  }

  public function create(Request $request)
  {

      // 以下を追記
      // Varidationを行う
      $this->validate($request, News::$rules);
      //NewsControllerのクラスがインスタンス化されて、それがリクエストを受け取った
      //私というインスタンスが保有しているvalidateという関数を読んで下さい、という意味。
      
      //News::は、Newsクラスの中の、クラスメンバーを呼び出す。
      //News.phpに記載。staticにより、どのインスタンスからでも同じ規則で検証を行う。

      $news = new News;
      //newsというクラスの新しいインスタンス(new News)を、$newsに格納する。
      
      $form = $request->all();
      //↑フォームに書かれた(画面に入力された情報)だけを配列にする。

      // フォームから画像が送信されてきたら、保存して、$news->image_path に画像のパスを保存する
      if (isset($form['image'])) {
        $path = $request->file('image')->store('public/image');
        $news->image_path = basename($path);
      } else {
          $news->image_path = null;
      }

      // フォームから送信されてきた_tokenを削除する
      unset($form['_token']);
      // フォームから送信されてきたimageを削除する
      unset($form['image']);

      // データベースに保存する
      $news->fill($form);
      $news->save();
      //fillは、連想配列の中身を埋める(ブラウザから飛んできたtitleやbodyなどの情報を埋めこむ)　saveは保存する。

      return redirect('admin/news/create');
  }


public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      
      if ($cond_title != '') {
          $posts = News::where('title', $cond_title)->get();
      } else {
          $posts = News::all();
      }
      return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
  }

  // 以下を追記

  public function edit(Request $request)
  {
      // News Modelからデータを取得する
      $news = News::find($request->id);
      if (empty($news)) {
        abort(404);    
      }
      return view('admin.news.edit', ['news_form' => $news]);
  }


public function update(Request $request)
  {
      // Validationをかける
      $this->validate($request, News::$rules);
      // News Modelからデータを取得する
      $news = News::find($request->id);
      // 送信されてきたフォームデータを格納する
      $news_form = $request->all();
      if (isset($news_form['image'])) {
        $path = $request->file('image')->store('public/image');
        $news->image_path = basename($path);
        unset($news_form['image']);
      } elseif (isset($request->remove)) {
        $news->image_path = null;
        unset($news_form['remove']);
      }
      unset($news_form['_token']);
      // 該当するデータを上書きして保存する
      $news->fill($news_form)->save();
      
      $history = new History;
      $history->news_id = $news->id;
      $history->edited_at = Carbon::now();
      $history->save();

      

      return redirect('admin/news');
  }
  
  public function delete(Request $request)
  {
      // 該当するNews Modelを取得
      $news = News::find($request->id);
      // 削除する
      $news->delete();
      return redirect('admin/news/');
  }  
  
}