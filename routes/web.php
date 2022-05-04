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


Route::get('/', 'FrontBlogController@index')->name('front_index');
//Route::get('/home', 'FrontBlogController@home')->name('front_home');
Route::get('/article/{id?}', 'FrontBlogController@article')->name('front_article');
Route::post('/comment/post','FrontBlogController@commentPost')->name('commentPost');
Route::post('/comment/delete','FrontBlogController@commentDelete')->name('commentDelete');
Route::get('/dayily_post','TwitterController@dayily_post')->name('dayily_post');
Route::get('/sf5lounge','TwitterController@index')->name('sf5lounge');


//AdminiBlogControllerは、ログイン認証あり
Route::prefix('admin')->group(function(){
    Route::get('form/{id?}','AdminBlogController@form')->name('admin_form');

    Route::post('post','AdminBlogController@post')->name('admin_post');
    Route::post('post/image','AdminBlogController@postArticleImg')->name('admin_post_article_img');
    
    Route::get('post/get_data_street_fighter_v','AdminBlogController@get_data_street_fighter_v')->name('admin_get_data_street_fighter_v');
    Route::get('post/get_latest_lp','AdminBlogController@get_latest_lp')->name('admin_get_latest_lp');
    Route::get('post/get_title_count','AdminBlogController@get_title_count')->name('admin_get_title_count');

    //ログイン認証
    Route::post('delete', 'AdminBlogController@delete')->name('admin_delete');
    Route::get('list','AdminBlogController@list')->name('admin_list');

    Route::get('category', 'AdminBlogController@category')->name('admin_category');
    Route::post('category/edit', 'AdminBlogController@editCategory')->name('admin_category_edit');
    Route::post('category/delete', 'AdminBlogController@deleteCategory')->name('admin_category_delete');

    Route::get('introduction','AdminBlogController@introduction')->name('admin_introduction');
    Route::post('introduction/edit','AdminBlogController@editIntroduction')->name('admin_introduction_edit');
    Route::get('logout','AdminBlogController@logout')->name('user_logout');
    
    Route::post('introduction/postimg','AdminBlogController@postMyImg')->name('admin_post_img');
    Route::post('introduction/setimg','AdminBlogController@setMyImg')->name('admin_set_img');

    Route::post('introduction/deleteimg','AdminBlogController@deleteImg')->name('admin_delete_img');
    Route::get('introduction/map','MapdataController@map')->name('admin_map');
    
    Route::get('introduction/map/getdata','MapdataController@get')->name('admin_map_get');
    Route::post('introduction/map/postdata','MapdataController@post')->name('admin_map_post');
    Route::post('introduction/map/deletedata','MapdataController@delete')->name('admin_map_delete');
    Route::get('introduction/mapview','MapdataController@view')->name('admin_map_view');
    //
});

Auth::routes();
//route::get('/home', 'Auth\LoginController@redirectPath')->name('home');

Route::get('/home', 'HomeController@index')->name('home');
