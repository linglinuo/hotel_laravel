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
        Schema::create('device_datas', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->string('temp')->nullable();
            $table->string('humidity')->nullable();
            $table->string('ctrl_cmd')->nullable();
            $table->boolean('trigger')->default(false);
            $table->string('basic_element_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_datas');
    }
};
