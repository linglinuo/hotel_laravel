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
        Schema::table('users', function (Blueprint $table) {
            //加入google_account欄位到facebook_id欄位之後
            $table->string('google_account', 30)
                ->nullable();
            //建立索引
            $table->index(['google_account'], 'user_g_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //移除欄位
            $table->dropColumn('google_account');
        });
    }
};
