<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('virtual_accounts', function (Blueprint $table) {
            //
            $table->dropColumn('secret_key');
            $table->text('secret_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_accounts', function (Blueprint $table) {
            //
            $table->dropColumn('secret_key');
            $table->string('secret_key');
        });
    }
};
