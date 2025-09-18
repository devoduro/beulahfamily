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
        Schema::table('member_ministry', function (Blueprint $table) {
            // Check if columns exist and add them if they don't
            if (!Schema::hasColumn('member_ministry', 'role')) {
                $table->enum('role', ['member', 'leader', 'assistant_leader', 'coordinator'])->default('member');
            }
            if (!Schema::hasColumn('member_ministry', 'joined_date')) {
                $table->date('joined_date')->default(now());
            }
            if (!Schema::hasColumn('member_ministry', 'left_date')) {
                $table->date('left_date')->nullable();
            }
            if (!Schema::hasColumn('member_ministry', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('member_ministry', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_ministry', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('member_ministry', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('member_ministry', 'joined_date')) {
                $table->dropColumn('joined_date');
            }
            if (Schema::hasColumn('member_ministry', 'left_date')) {
                $table->dropColumn('left_date');
            }
            if (Schema::hasColumn('member_ministry', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('member_ministry', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
