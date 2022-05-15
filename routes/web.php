<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//return view('welcome');
//});

\URL::forceScheme('https');

//投稿一覧
Route::get('/', 'FrontBlogController@index')->name('front_index');
//Route::get('/home', 'FrontBlogController@home')->name('front_home');

//投稿詳細
Route::get('/article/{id?}', 'FrontBlogController@article')->name('front_article');

//コメント追加
Route::post('/comment/post','FrontBlogController@commentPost')->name('commentPost');

//コメント削除
Route::post('/comment/delete','FrontBlogController@commentDelete')->name('commentDelete');

//コメント一覧
Route::get('/comment/list','FrontBlogController@commentList')->name('commentList');

//トレモ風景
Route::get('/dayily_post','TwitterController@dayily_post')->name('dayily_post');

//ラウンジ募集
Route::get('/sf5lounge','TwitterController@index')->name('sf5lounge');

//AdminiBlogControllerは、ログイン認証あり
Route::prefix('admin')->group(function(){
    //投稿
    Route::get('form/{id?}','AdminBlogController@form')->name('admin_form');
    //投稿送信
    Route::post('post','AdminBlogController@post')->name('admin_post');
    //投稿イメージ送信
    Route::post('post/image','AdminBlogController@postArticleImg')->name('admin_post_article_img');
    //ストリートファイターV結果集計
    Route::get('post/get_data_street_fighter_v','AdminBlogController@get_data_street_fighter_v')->name('admin_get_data_street_fighter_v');
    //最新LP取得
    Route::get('post/get_latest_lp','AdminBlogController@get_latest_lp')->name('admin_get_latest_lp');
    //カテゴリカウント
    Route::get('post/get_title_count','AdminBlogController@get_title_count')->name('admin_get_title_count');
    //投稿削除
    Route::post('delete', 'AdminBlogController@delete')->name('admin_delete');

    //投稿リスト
    Route::get('list','AdminBlogController@list')->name('admin_list');

    //カテゴリ
    Route::get('category', 'AdminBlogController@category')->name('admin_category');
    //カテゴリ編集
    Route::post('category/edit', 'AdminBlogController@editCategory')->name('admin_category_edit');
    //カテゴリ削除
    Route::post('category/delete', 'AdminBlogController@deleteCategory')->name('admin_category_delete');

    //自己紹介
    Route::get('introduction','AdminBlogController@introduction')->name('admin_introduction');
    //自己紹介編集
    Route::post('introduction/edit','AdminBlogController@editIntroduction')->name('admin_introduction_edit');
    //自己紹介イメージ送信
    Route::post('introduction/postimg','AdminBlogController@postMyImg')->name('admin_post_img');
    //自己紹介イメージ設定
    Route::post('introduction/setimg','AdminBlogController@setMyImg')->name('admin_set_img');
    //自己紹介イメージ削除
    Route::post('introduction/deleteimg','AdminBlogController@deleteImg')->name('admin_delete_img');
    
    //Map編集
    Route::get('introduction/map','MapdataController@map')->name('admin_map');
    //Mapデータ取得
    Route::get('introduction/map/getdata','MapdataController@get')->name('admin_map_get');
    //Mapデータ送信
    Route::post('introduction/map/postdata','MapdataController@post')->name('admin_map_post');
    //Mapデータ削除
    Route::post('introduction/map/deletedata','MapdataController@delete')->name('admin_map_delete');
    
    //Map閲覧
    Route::get('introduction/mapview','MapdataController@view')->name('admin_map_view');
    
    Route::get('logout','AdminBlogController@logout')->name('user_logout');
});

Auth::routes();
//route::get('/home', 'Auth\LoginController@redirectPath')->name('home');

Route::get('/home', 'HomeController@index')->name('home');
