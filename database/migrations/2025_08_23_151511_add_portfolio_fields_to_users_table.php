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
            $table->string('title')->nullable()->after('name');
            $table->text('bio')->nullable()->after('title');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('avatar');
            $table->string('github_url')->nullable()->after('linkedin_url');
            $table->string('phone', 20)->nullable()->after('github_url');
            $table->string('location')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['title', 'bio', 'avatar', 'linkedin_url', 'github_url', 'phone', 'location']);
        });
    }
};
