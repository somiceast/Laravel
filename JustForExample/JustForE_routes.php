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
//

//和memberController结合,两种写法
//Route::get('member/info', 'MemberController@info');
//Route::get('member/info',['uses'=>'MemberController@info']);
//
//起别名
//Route::any('member/info',[
//   'uses' => 'MemberController@info',
//    'as' => 'memberinfo'//别名
//]);
//
////参数绑定+验证
//Route::get('member/{id}', 'MemberController@infoParameter')
//    ->where('id','[0-9]+');
//
///*
// * 输出视图
// */
//Route::get('member/infoview','memberController@infoView');
////输出默认视图
//Route::get('member/infoviewM','memberController@memberinfoView');
////输出默认视图带参数
//Route::get('member/infoviewMP','memberController@memberinfoViewPara');
//
///*
// * 模型
// */
//Route::get('member/infoviewMo','memberController@memberModel');
//
/*
 * facade数据库
 */
Route::any('test1', ['uses' => 'StudentController@test1']);

//Route::get('/', function () {
//    return view('welcome');
//});
////基础路由
//Route::get('basic1', function() {
//    return 'hello world';
//});
//Route::post('basic2', function() {
//    return 'hello2';
//});
////多请求路由
//Route::match(['get', 'post'], 'multy1',function (){
//    return 'multy1';
//});
//
//Route::any('multy2',function () {
//    return 'multy2';
//});
//
//Route::get('user={id}', function($id) {
//    return 'User-id-'.$id;
//});
//
////默认传参带问号
//Route::get('user={name?}', function($name = 'dfasdfdfasdf') {
//    return 'User-name-'.$name;
//});
//
////验证
//Route::get('user={name?}', function($name = 'dfasdf') {
//    return 'User-name-'.$name;
//})->where (['id' => '[0-9]','name'=>'[a-z=null]+']);
//
////多个参数
//Route::get('user/{id?}/{name?}', function($id=null, $name = null) {
//    return 'User-id-'.$id.'-name-'.$name;
//})->where (['id' => '[0-9]','name'=>'[a-z=null]+']);
//
//
////路由别名
//Route::get('user/member-center', ['as' => 'center',function($name = 'dfasdfdfasdf') {
//    return route('center');
//}]);
//
////路由群组
////prefix是前缀
//Route::group(['prefix' => 'member'], function (){
//    Route::match(['get', 'post'], 'multy1',function (){
//        return 'multy1';//这里运行时用XXXX/multy1报错，因为群组里设置了前缀，XXXX/member/multy1就ok了
//    });
//
//    Route::any('multy2',function () {
//        return 'multy2';
//    });
//
//});
//
////路由中的输出视图
//Route::get('view', function () {
//    return view('welcome');
//});