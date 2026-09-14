<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->dropColumn(['name', 'email', 'identity_number', 'position', 'school']);
        });
    }

    public function down(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('position')->nullable();
            $table->string('school')->nullable();
        });
    }
};
