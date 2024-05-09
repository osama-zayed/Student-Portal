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
        Schema::create('incidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('incident_number'); // رقم البلاغ
            $table->integer('crime_type_id')->unsigned();
            $table->date('incident_date'); // تاريخ البلاغ
            $table->integer('department_id')->unsigned();
            
            $table->time('incident_time'); // زمن وقوعها
            $table->date('date_occurred'); // تاريخ وقوعها
            $table->string('incident_location'); // مكان وقوعها
            $table->text('reasons_and_motives'); // الأسباب والدوافع
            $table->text('tools_used'); // الأدوات المستخدمة
            $table->integer('number_of_victims'); // عدد الضحايا
            $table->integer('number_of_perpetrators'); // عدد الجناة
            $table->string('incident_status'); // حالة البلاغ
            $table->text('incident_description'); // شرح البلاغ
            $table->string('incident_image')->nullable(); // صورة البلاغ
            $table->text('notes')->nullable(); // ملاحظات
            $table->unsignedBigInteger('main_incident_id')->nullable(); // رقم البلاغ الرئيسي
    
            $table->foreign('department_id')->references('id')->on('departments'); // مركز الشرطة مفتاح أجنبي من جدول departments
            $table->foreign('crime_type_id')->references('id')->on('crimes'); // نوع الجريمة مفتاح أجنبي من جدول crimes
            $table->foreign('main_incident_id')->references('id')->on('incidents'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
