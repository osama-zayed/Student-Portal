<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('specialization_id')->unsigned();
            $table->integer('semester_num')->unsigned();
            $table->integer('semester_tasks_id')->unsigned();
            $table->float('academic_work_grade')->nullable();
            $table->float('final_exam_grade')->nullable();
            $table->float('final_grade')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('semester_tasks_id')->references('id')->on('semester_tasks');
            $table->foreign('semester_num')->references('id')->on('semester_numbers');
            $table->foreign('specialization_id')->references('id')->on('specializations')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('results');
    }
}
