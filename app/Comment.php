<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  //提交评论api
  public function add()
  {
    //检查是否登录
    if (!user_ins()->is_logged_in())
      return ['status' => 0, 'msg' => 'login required'];
    //把user_id存入
    $this->user_id = session('user_id');
    //检查评论是否含有内容
    if (!rq('content'))
      return ['status' => 0, 'msg' => 'comment content required'];
    //检查是否存在question_id or answer_id
    if (
      (!rq('question_id') && !rq('answer_id')) ||
      (rq('question_id') && rq('answer_id'))
    )
      return ['status' => 0, 'msg' => 'question_id or answer_id required'];
    if (rq('question_id')) {
      //评论问题
      $question = question_ins()->find(rq('question_id'));
      //检查问题是否存在
      if (!$question)
        return ['status' => 0, 'msg' => 'question not exist'];
      $this->question_id = rq('question_id');
    } else {
      //评论答案
      $answer = answer_ins()->find(rq('answer_id'));
      //检查答案是否存在
      if (!$answer)
        return ['status' => 0, 'msg' => 'answer not exist'];
      $this->answer_id = rq('answer_id');
    }
//        检查是否在回复评论
    if (rq('reply_to')) {
      $target = $this->find(rq('reply_to'));
//            return $target->user_id;//自身id
//            return session('user_id');//自身id
      //检查目标评论是否存在
      if (!$target)
        return ['status' => 0, 'msg' => 'target comment not exist'];
      //检查是否在评论自己
      if ($target->user_id == session('user_id'))
        return ['status' => 0, 'msg' => 'canno我t reply to yourself'];
      $this->reply_to = rq('reply_to');
    }
    //保存数据
    $this->content = rq('content');
    return $this->save() ?
      ['status' => 1, 'id' => $this->id] :
      ['status' => 0, 'msg' => 'db insert failed'];

  }

  public function read()
  {
    if (!rq('question_id') && !rq('answer_id'))
      return ['status' => 0, 'question_id or answer_id required'];

    if (rq('question_id')) {
      $question = question_ins()->with('user')->find(rq('question_id'));
      if (!$question) return [
        'status' => 0, 'question not exists'];
      $data = $this
        ->with('user')
        ->where('question_id', rq('question_id'))
        ->get();
    } else {
      $answer = answer_ins()->with('user')->find(rq('answer_id'));
      if (!$answer)
        return ['status' => 0, 'answer not exists'];
      $data = $this
        ->with('user')
        ->where('answer_id', rq('answer_id'))
        ->get();
    }
    return ['status' => 1, 'data' => $data->keyBy('id')];
  }

  public function remove()
  {
    if (!user_ins()->is_logged_in())
      return ['status' => 0, 'msg' => 'login required'];
    if (!rq('id'))
      return ['status' => 0, 'msg' => 'id required'];
    $comment = $this->find(rq('id'));
    if (!$comment)
      return ['status' => 0, 'msg' => 'comment not found'];
    if ($comment->user_id != session('user_id'))
      return ['status' => 0, 'msg' => 'permission denied'];
    $this->where('reply_to', rq('id'))->delete();

    return $comment->delete() ?
      ['status' => 1] :
      ['status' => 0, 'db insert failed'];
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
