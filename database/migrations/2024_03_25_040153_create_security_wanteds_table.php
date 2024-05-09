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
        Schema::create('security_wanteds', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number'); // رقم القيد
            $table->string('day'); // اليوم
            $table->date('registration_date'); // تاريخ القيد
            $table->string('wanted_name'); // اسم المطلوب
            $table->integer('age'); // العمر
            $table->string('event'); // بالع/حدث
            $table->string('gender'); // الجنس
            $table->string('marital_status'); // الحالة الاجتماعية
            $table->string('nationality'); // الجنسية
            $table->string('occupation'); // المهنة
            $table->string('place_of_birth'); // محل الميلاد
            $table->string('residence'); // السكن
            $table->string('previous_convictions'); // السوابق
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_wanteds');
    }
};
