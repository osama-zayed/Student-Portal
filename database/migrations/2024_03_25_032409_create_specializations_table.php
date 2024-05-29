<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {//جدول التخصص
        Schema::create('specializations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('college_id')->unsigned();
            $table->double('Price');
            $table->integer('Number_of_years_of_study');
            $table->integer('Number_of_semester_of_study');
            $table->string('educational_qualification');
            $table->double('lowest_acceptance_rate');
            $table->foreign('college_id')->references('id')->on('colleges'); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
};
