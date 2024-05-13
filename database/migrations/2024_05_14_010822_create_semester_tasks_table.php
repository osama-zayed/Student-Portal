<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemesterTasksTable extends Migration
{
    public function up()
    {
        Schema::create('semester_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->integer('semester_num');
            $table->float('academic_work_grade');
            $table->float('attendance');
            $table->float('midterm_grade')->nullable();
            $table->float('final_exam_grade')->nullable();
            $table->string('status');
            $table->float('final_grade')->nullable();
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('semester_tasks');
    }
}