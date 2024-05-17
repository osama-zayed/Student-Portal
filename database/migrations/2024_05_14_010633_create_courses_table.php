<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('hours');
            $table->integer('specialization_id')->unsigned();
            $table->integer('semester_num');
            $table->integer('teachers_id')->unsigned()->nullable();
            $table->foreign('specialization_id')->references('id')->on('specializations');
            $table->foreign('teachers_id')->references('id')->on('teachers');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}