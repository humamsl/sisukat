<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('tutorials', function (Blueprint $table) {
            $table->index('status');
            $table->index('type');
        });

        Schema::table('instruments', function (Blueprint $table) {
            $table->index('status');
            $table->index('file_type');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->index('status');
            $table->index('uploaded_at');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('tutorials', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
        });

        Schema::table('instruments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['file_type']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['uploaded_at']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['action']);
        });
    }
};
