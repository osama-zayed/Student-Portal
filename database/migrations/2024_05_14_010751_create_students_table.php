<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->unsignedBigInteger('personal_id')->unique();
            $table->unsignedBigInteger('academic_id')->unique();
            $table->string('phone_number');
            $table->string('relative_phone_number')->nullable();
            $table->string('date_of_birth');
            $table->string('place_of_birth');
            $table->string('gender');
            $table->float('high_school_grade');
            $table->integer('college_id')->unsigned();
            $table->integer('specialization_id')->unsigned();
            $table->string('password');
            $table->integer('semester_num')->default(1);
            $table->boolean("user_status")->default(true);

            $table->timestamps();
            $table->foreign('college_id')->references('id')->on('colleges');
            $table->foreign('specialization_id')->references('id')->on('specializations');
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}