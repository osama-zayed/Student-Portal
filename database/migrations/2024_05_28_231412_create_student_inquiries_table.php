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
        Schema::create('student_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id'); // معرف الطالب
            $table->string('subject'); // موضوع الاستفسار
            $table->text('message'); // رسالة الاستفسار
            $table->string('status')->default('pending'); // حالة الاستفسار (pending, resolved, closed)
            $table->string('inquirie_type'); // حالة الاستفسار (pending, resolved, closed)
            $table->timestamp('resolved_at')->nullable(); // تاريخ حل الاستفسار
            $table->text('reply_message')->nullable(); // رسالة الاستفسار
            $table->timestamps();
        
            // إضافة الروابط الخارجية
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_inquiries');
    }
};
