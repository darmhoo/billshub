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
        //
        Schema::table('data_types', function (Blueprint $table) {
            //
            $table->foreignId('network_id')
            ->nullable()
            ->references('id')
            ->on('networks')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('data_types', function (Blueprint $table) {
            //
            $table->dropForeign('data_types_network_id_foreign');
            $table->dropColumn('network_id');
        });
    }
};
