<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('completed');
            $table->unsignedInteger('repeat_interval_hours')->nullable()->after('repeat_type');
            $table->boolean('repeated')->default(false)->after('repeat_interval_hours');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'repeat_interval_hours', 'repeated']);
        });
    }
};
