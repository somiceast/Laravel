<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//request
function err($msg = null)
{
    return ['status' => 0, 'msg' => $msg];
}

function suc($data_to_add = [])
{
    $data = ['status' => 1,'data' => []];
    if($data_to_add)
        $data['data'] =
          $data_to_add;
//          array_merge($data['data'], $data_to_merge);
    return $data;
}


function paginate($page =1, $limit = 16){
    $limit = $limit ?: 16;
    $skip = ($page ? $page - 1 : 0) * $limit;
    return[$limit, $skip];
}

function rq($key=null, $default = null){
    if(!$key)return Request::all();
    return Request::get($key, $default);
}

function user_ins(){
    return new App\User;
}
function question_ins(){
    return new App\Question;
}
function answer_ins(){
    return new App\Answer;
}
function comment_ins(){
    return new App\Comment();
};

function  is_logged_in(){
    //检查是否有userid
    //如果session中存在user_id就返回user_id,否则返回false
    return session('user_id') ?: false;
};



Route::any('/',function (){
    return view('index');
});


Route::any(
    'test1', ['uses' => 'StudentController@test1']);
Route::get('api', function () {
    return ['version' => '科科'];
});
Route::any('api/signup', function () {
    //实例化新model
     return user_ins()->signup();
});
Route::any('api/login', function () {
    return user_ins()->login();
});
Route::any('api/logout', function () {
    return user_ins()->logout();
});

Route::any('api/user/change_password', function () {
    return user_ins()->change_password();
});
Route::any('api/user/reset_password', function () {
    return user_ins()->reset_password();
});
Route::any('api/user/validate_reset_password', function () {
    return user_ins()->validate_reset_password();
});

Route::any('api/user/exists', function (){
    return user_ins()->exists();
});
Route::any('api/user/read', function () {
    return user_ins()->read();
});

Route::any('api/question/add', function () {
    return question_ins()->add();
});
Route::any('api/question/change', function () {
    return question_ins()->change();
});
Route::any('api/question/read', function () {
    return question_ins()->read();
});
Route::any('api/question/remove', function () {
    return question_ins()->remove();
});

Route::any('api/answer/add', function () {
    return answer_ins()->add();
});
Route::any('api/answer/change', function () {
    return answer_ins()->change();
});
Route::any('api/answer/read', function () {
    return answer_ins()->read();
});
Route::any('api/answer/remove',function (){
    return answer_ins()->remove();
});
  Route::any('api/answer/vote',function (){
    return answer_ins()->vote();
});


Route::any('api/comment/read', function () {
    return comment_ins()->read();
});
Route::any('api/comment/add', function () {
    return comment_ins()->add();
});
Route::any('api/comment/remove', function () {
    return comment_ins()->remove();
});
Route::any('api/timeline', 'CommonController@timeline');

Route::any('tpl/page/home', function (){
    return view('page.home');
});
Route::any('tpl/page/signup', function (){
    return view('page.signup');
});
Route::any('tpl/page/login', function (){
    return view('page.login');
});
Route::any('tpl/page/question_add', function (){
    return view('page.question_add');
});
Route::any('tpl/page/question_detail', function (){
    return view('page.question_detail');
});

Route::any('tpl/page/user', function (){
    return view('page.user');
});







Route::any('api/test', function () {
    dd(user_ins()->is_logged_in());
});
