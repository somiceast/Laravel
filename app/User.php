<?php

namespace App;

use Request;
use Hash;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function signup()
    {
        /*
         * 参考语法：
         *dd(Request::get('username'));
        dd(Request::has('username'));
        dd(Request::all());
        return 'signup';
         */
        /*
         * 注册api
         *
         * 检查用户名、密码是否为空-否
         * 检查用户名是否存在（重复-否
         * 加密密码
         * 存入数据库
         */
        $has_username_and_password = $this->has_username_and_password();
        if (!$has_username_and_password)
            return ['status' => 0, 'msg' => '用户名和密码皆不可为空'];
        $username = $has_username_and_password[0];
        $password = $has_username_and_password[1];

        $user_exists = $this
            ->where('username', $username)
            ->exists();

        if ($user_exists)
            return ['status' => 0, 'msg' => '用户名已存在'];

        //加密密码除了Hash::make($password)还能bcrypt($password)
        $hashed_password = Hash::make($password);

        $user = $this;
        $user->password = $hashed_password;
        $user->username = $username;
        if ($user->save())
            return ['status' => 1, 'id' => $user->id];
        else return ['status' => 0, 'msg' => 'db insert failed'];
    }

    /*
     * 登陆
     */

    public function login()
    {
//        dd(session('abc','cde'));

        //检查用户名和密码是否存在
        $has_username_and_password = $this->has_username_and_password();
        if (!$has_username_and_password) {
            return ['status' => 0, 'msg' => '用户名和密码皆不可为空'];
        }
        $username = $has_username_and_password[0];
        $password = $has_username_and_password[1];

        //在数据库中查找是否有该username，如果有返回查询结果第一条
        $user = $this->where('username', $username)->first();

        if(!$user)
            return ['status' => 0, 'msg' => '用户名不存在'];
        //检查密码是否正确
        $hashed_password = $user->password;
        if(!Hash::check($password, $hashed_password))
            return ['status' => 0, 'msg' => '密码不正确'];

        //将用户信息写入session
        session()->put('username', $user->username);
        session()->put('user_id', $user->id);

        //打印
        //dd(session()->all());

        return ['status' => 1, 'id' => $user->id];
    }

    /*获取用户信息*/
    public function read()
    {
        if(!rq('id'))
            return err('required id');

      if (rq('id') === 'self') {
        if (!$this->is_logged_in())
          return err('login required');
        $id = session('user_id');
      } else
        $id = rq('id');


      $get = ['id','username','avatar_url','intro'];

        $user = $this->find($id,$get);
        $data = $user->toArray();

        $answer_count = answer_ins()->where('user_id',$id)->count();
        $question_count = question_ins()->where('user_id',$id)->count();

        $data['answer_count'] = $answer_count;
        $data['question_count'] = $question_count;
      /*
       * //        $answer_count= $user->answers()->count();
//        $question_count= $user->questions()->count();
//        $status = ['status' =>1           ,$data];

       *
//       */
        return suc($data);
    }



    public function has_username_and_password()
    {
        $username = rq('username');
        $password = rq('password');
        /*检查用户名和密码是否为空*/
        if ($username && $password)
            return [$username, $password];
        return false;
    }

    //登出api
    public function logout(){
        //flush彻底清空session
//        session()->flush();
        //pull

        //删除username
        session()->forget('username');
        //删除user_id
        session()->forget('user_id');
//        session()->put('username',null);
//        session()->put('user_id',null);
//        dd(session()->all());
        return ['status' => 1];
    }

    //检测用户是否登陆
    public function  is_logged_in(){
        //检查是否有userid
        //如果session中存在user_id就返回user_id,否则返回false
        return session('user_id') ?: false;
    }

    //修改密码api
    public function change_password()
    {
        //检查登录
        if(!$this->is_logged_in())
            return [
                'status' => 0,
                'msg' => 'login required'];
        //检查参数
        if(!rq('old_password') ||
            !rq('new_password'))
            return [
                'status' => 0,
                'msg' => 'old_password and new_password
                 are required'];
        //检查密码是否正确
        $user = $this->find(session('user_id'));

        if(!Hash::check(rq('old_password'), $user->password))
            return ['status' => 0, 'msg' => 'invalid old_password'];
//        将加密后的密码存进去
        $user->password = bcrypt(rq('new_password'));
       return $user->save() ?
        ['status' => 1]:
        ['status' => 0,
            'msg' => 'db change failed'];
    }

    //重置密码：忘记密码
    public function reset_password()
    {
        /*判断是否为电脑*/
        $this->check_robot();

        if(!rq('phone'))
            return err('phone is required');

        $user = $this->where('phone',rq('phone'))->first();

        if(!$user)
            return err('invalid phone number');

        $captcha = $this->generate_captcha();
        $user->phone_captcha = $captcha;
        if ($user->save()) {
            $this->send_sms();
            ['status' => 1] ;
        }
         return err('db update failed');
    }

//    生成验证码
    public function generate_captcha(){
        return rand(1000,9999);
    }
    //验证验证码
    public function validate_reset_password()
    {
        if($this->check_robot(2))
            return err('max frequency reached');

        if(!rq('phone') || !rq('phone_captcha'))
            return err('phone ande phone_captcha are required');
        $user = $this->where([
            'phone' => rq('phone'),
            'phone_captcha' => rq('phone_captcha')
        ])->first();

        if(!$user)
            return err('invalid phone or invalid phone');
        $user->password = bcrypt(rq('new_password'));
        if( $user->save()) {
            //为下一次机器人调用做准备
            $this->update_robot_time();
           return ['status' => 1];
            }else{
            err('db update failed');
        }
    }

    public function send_sms()
    {
//        $phone,$phone_captcha
//        $SMS = new \ChuanglanSmsApi();
//        $SMS->sendSMS($phone,$phone_captcha);
        return true;
    }

    //检查机器人
    public function check_robot($time=10)
    {

        //如果session中我没有last_sms_time 说明接口从未被调用
        if(!session('last_action_time'))
            return false;
        //检查发送sms时间差大于$time
        $current_time = time();
        $last_active_time = session('last_action_time');
        $elapsed = $current_time - $last_active_time;
        return !($elapsed > $time);
    }

    //更新操作行为
    public function update_robot_time()
    {
        session()->set('last_action_time',time());
    }

    public function answers()
    {
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }
    public function questions()
    {
        return $this
            ->belongsToMany('App\Questions')
            ->withPivot('vote')//轴
            ->withTimestamps();
    }

    public function exists()
    {
        return suc(['count'=> $this->where(rq())->count()]);
    }
}
