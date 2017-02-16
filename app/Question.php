<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //创建问题
    public function add(){
        //调用user_ins中的方法
//        dd(rq());
        //检查是否登录
        if (!user_ins()->is_logged_in())
            return err('您没登陆');//login required
        //检查是否存在标题
        if(!rq('title'))
            return err('您没标题');//required title
        //存入数据库
        $this->title = rq('title');
        $this->user_id = session('user_id');

        //如果存在描述就添加描述
        if(rq('desc'))
            $this->desc = rq('desc');

        //保存
        return $this->save() ?
            ['status' => 1,'id'=> $this -> id]:
            ['status' => 0,'msg'=>"db insert failed"];
    }

    //更新问题
    public function change(){
        if (!user_ins()->is_logged_in())
            return ['status'=> 0, 'msg' => '您没登陆'];//login required
        //判断请求中是否有questionid
        if(!rq('id'))
            return ['status' => 0, 'msg' => '未得到该问题id'];
        //找到id
        $question = $this->find(rq('id'));

        //判断问题是否存在
        if(!$question)
            return ['status' => 0, 'msg' => '该问题id不存在'];

        //验证修改问题用户id是否为提问者id:获取指定id的model
        if($question->user_id != session('user_id'))
            return ['status' => 0, 'msg' => '您没找到该问题id的权限'];
        if(rq('title'))
            $question->title = rq('title');
        if(rq('desc'))
            $question->desc = rq('desc');
        //保存数据
        return $question->save() ?
            ['status' => 1]:
            ['status' => 0,'msg'=>"db update failed"];
    }
    public function read()
    {
      //请求参数中是否有id 如果有id直接返回id所在行

      if (rq('id')){
        $r = $this
          ->with('answers_with_user_info')
          ->find(rq('id'));
        return [
          'status' => 1,
          'data' => $r];
      }

      if (rq('user_id')) {
        $user_id = rq('user_id') === 'self' ?
          session('user_id') :
          rq('user_id');
        return $this->read_by_user_id($user_id);
      }
        //反馈列表
//		dd($this);
        //每页默认为15个，如果用户自定义，则采用用户要的参数
        //limit条件
//        $limit = rq('limit') ?: 15;
        //skip条件 用于分页
//        $skip = (rq('page') ? rq('page') -1 : 0)* $limit;
        list($limit,$skip) =
            paginate(rq('pages'),rq('limit'));

//        构建query并返回collection数据，laravel会自动转化数据为json
        $r = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get();//get()方法简化了get到的collection,get中的参数限制了输出
//            ->get(['id','created_at']);//只输出id和created_at
                //->keyBy();//keyBy()方法提供了数组中的排布序列，有了键
        return ['status' => 1, 'data' => $r];
    }


  public function read_by_user_id($user_id)
  {
    $user = user_ins()->find($user_id);
    if(!$user){
      return err('user not exists');
    }
    $r = $this->where('user_id', $user_id)->get()->keyBy('id');
    return suc($r -> toArray());
  }

    //删除问题api
    public function remove()
    {
//        if (!user_ins()->is_logged_in())
//            return ['status'=> 0, 'msg' => '您没登陆'];
      $this->check_login();
      return 'Only admin has the right to delete questions';
    }

    public function check_login()
    {
        //检查用户身份登录
        if (!user_ins()->is_logged_in())
            return ['status'=> 0, 'msg' => '您没登陆'];//login required
        //检查传参是否有id
        if (!rq('id'))
            return ['status'=>0, 'msg'=>'id is required'];
        //获取传参id所对应的model
        $question = $this->find(rq('id'));
        if(!$question)
            return['status'=>0, 'msg'=>'id is required'];
        //检查当前用户是否为问题所有者
        if (session('user_id') != $question->user_id)
            return ['status'=>0, 'msg'=>'id is required'];
        return $question->delete() ?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'db delete failed']
            ;
    }



    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function answers_with_user_info()
    {
        return $this
            ->answers()
          ->with('user')
            ->with('users');
    }
}
