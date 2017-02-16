<?php
/**
 * Created by PhpStorm.
 * User: 芳荷
 * Date: 2016/9/18
 * Time: 16:26
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StudentController extends Controller {

    public function test1(){
//        查询*
//        $students = DB::select('select * from student');
//        var_dump($students);
        //dd($students);

//插入字段
//        $bool = DB::insert('insert into student(name, age) values(?,?)',[
//            '老王' ,42
//        ]);
//        var_dump($bool);
        //更新
//        $num = DB::update('update student set age = ? where name = ?',
//        [82,'老王']);
//        var_dump($num);
        //删除
//        $num = DB::delete('delete from student where id > ?',[0]);
//        var_dump($num);
//        dd($student);

//        $students = DB::select('select * from student where id > ?',[10]);
//        var_dump($students);
//        dd($students);
//        return 'test1连接成功';
    }
    public function query1(){

    }
}