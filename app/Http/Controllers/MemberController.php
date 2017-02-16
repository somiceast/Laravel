<?php
/**
 * Created by PhpStorm.
 * User: 芳荷
 * Date: 2016/9/18
 * Time: 11:02
 */
namespace App\Http\Controllers;

use App\Member;

class MemberController extends Controller {
    public function info(){
        return 'member-info'.route("memberinfo");
    }
    //带参数输出
    public function infoParameter($id){
        return 'member-info-id-'.$id;
    }
    //输出视图
    public function infoView(){
        return view('member-info');
    }
    //输出视图默认模板
    public function memberinfoView(){
        return view('member/info');
    }

    //输出视图默认模板 传给view变量
    public function memberinfoViewPara(){
        return view('member/info',[
            'name'=>'熊',
            'age'=>17//然后在视图编辑输出变量
        ]);
    }
    //调用模型
    public function memberModel(){
        //调用静态方法
        return Member::getMember();

    }
}//然后和路由连接起来