<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTeacherTable extends Migration
{
    public function up()
    {
        Schema::create('course_teacher', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teacher_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->timestamps();
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_teacher');
    }
}