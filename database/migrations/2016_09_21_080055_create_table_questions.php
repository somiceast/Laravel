<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            //题目设定64
            $table->string('title', 64);
            //在表里面注释用comment()，对desc进行说明
            $table->text('desc')->nullable()->comment("description");
            //userid为外键
            $table->unsignedInteger('user_id');
            //状态，是否适合直接发布
            $table->string('status')->default("ok");

            $table->timestamps();
            //user_id 为 user表上,与本问题的id相互关联
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questions');
    }
}
