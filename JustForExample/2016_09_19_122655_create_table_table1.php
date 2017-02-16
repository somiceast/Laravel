<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('table1', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('article')->nullable();
            //指定字段name类型为string且可以为空（默认为 不能为空
            $table->string('username')->unique();
            //指定字段username类型为string,指定长度为12,且不想让该字段重复（用户名不能重复）
        });
        Schema::rename('table1','able1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('able1');
    }
}
