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
        Schema::create('basic_elements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('board');
            $table->string('small_marks_date')->nullable();
            $table->string('small_marks_time')->nullable();
            $table->string('small_marks_people')->nullable();
            $table->string('small_marks_other')->nullable();
            $table->string('on_name')->nullable(); //若選擇其他的開啟，動作名稱設置
            $table->string('off_name')->nullable(); //若選擇其他的關閉，動作名稱設置
            $table->string('type');
            $table->string('switches')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_elements');
    }
};
