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
            $table->integer('specialization_id')->unsigned();
            $table->integer('semester_num')->unsigned();
            $table->float('academic_work_grade')->nullable();
            $table->float('attendance')->nullable();
            $table->float('midterm_grade')->nullable();
            $table->float('practicality_grade')->nullable();
            $table->float('final_grade')->nullable();
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('semester_num')->references('id')->on('semester_numbers');
            $table->foreign('specialization_id')->references('id')->on('specializations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('semester_tasks');
    }
}