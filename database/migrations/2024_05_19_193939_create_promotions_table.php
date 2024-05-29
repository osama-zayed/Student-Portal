<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->unsigned();
            $table->integer('from_semester_num')->unsigned();
            $table->integer('to_semester_num')->unsigned();
            $table->integer('from_specialization_id')->unsigned();
            $table->integer('to_specialization_id')->unsigned();
            $table->integer('academic_year')->unsigned();
            $table->integer('academic_year_new')->unsigned();
            $table->timestamps();
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('from_semester_num')->references('id')->on('semester_numbers')->onDelete('cascade');
            $table->foreign('to_semester_num')->references('id')->on('semester_numbers')->onDelete('cascade');
            $table->foreign('from_specialization_id')->references('id')->on('specializations')->onDelete('cascade');
            $table->foreign('to_specialization_id')->references('id')->on('specializations')->onDelete('cascade');
            $table->foreign('academic_year')->references('id')->on('school_years')->onDelete('cascade');
            $table->foreign('academic_year_new')->references('id')->on('school_years')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
