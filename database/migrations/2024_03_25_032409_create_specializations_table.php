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
        Schema::create('specialization', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('college_id')->unsigned();
            $table->double('Price');
            $table->integer('Number_of_years_of_study');
            $table->foreign('college_id')->references('id')->on('college'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crimes');
    }
};