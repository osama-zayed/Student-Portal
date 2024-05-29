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
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(); // اسم العام الدراسي
            $table->date('start_date')->nullable()->format('Y-m'); // تاريخ بداية العام الدراسي
            $table->date('end_date')->nullable()->format('Y-m'); // تاريخ نهاية العام الدراسي
            $table->boolean('is_current')->default(false); // هل هذا العام الدراسي الحالي
            $table->string('UniversityCalendar')->nullable(); // التقويم الجامعي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_years');
    }
};
