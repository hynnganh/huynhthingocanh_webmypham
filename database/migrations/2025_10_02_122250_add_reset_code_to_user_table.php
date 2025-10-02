<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('reset_code')->nullable()->after('status');
            $table->timestamp('reset_code_expire')->nullable()->after('reset_code');
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['reset_code', 'reset_code_expire']);
        });
    }

};
