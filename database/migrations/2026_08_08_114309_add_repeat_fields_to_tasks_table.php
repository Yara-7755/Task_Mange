<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'repeat_type')) {
                $table->string('repeat_type')->default('none');
            }
            if (!Schema::hasColumn('tasks', 'repeat_interval_minutes')) {
                $table->integer('repeat_interval_minutes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'repeat_type')) {
                $table->dropColumn('repeat_type');
            }
            if (Schema::hasColumn('tasks', 'repeat_interval_minutes')) {
                $table->dropColumn('repeat_interval_minutes');
            }
        });
    }
};
