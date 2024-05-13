<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSpecializationTable extends Migration
{
    public function up()
    {
        Schema::create('course_specialization', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('specialization_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('semester_num');

            $table->timestamps();

            $table->foreign('specialization_id')->references('id')->on('specializations');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_specialization');
    }
}