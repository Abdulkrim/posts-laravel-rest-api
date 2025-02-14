<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // إضافة فهارس
            $table->index('title'); // فهرس عادي
            $table->index('user_id'); // فهرس عادي
            $table->index(['user_id', 'created_at']); // فهرس مركب
            $table->fullText('body'); // بحث نصي كامل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // إزالة الفهارس
            $table->dropIndex(['title']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropFullText(['body']);
        });
    }
};
