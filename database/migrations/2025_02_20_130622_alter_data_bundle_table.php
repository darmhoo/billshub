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
        Schema::create('data_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');

        });
        Schema::table('data_bundles', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('data_type_id')->nullable();
            $table->foreign('data_type_id')
                ->references('id')
                ->on('data_types');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_bundles', function (Blueprint $table) {
            //
            $table->dropForeign('data_bundles_data_type_id_foreign');
            $table->dropColumn('data_type_id');
        });
        Schema::dropIfExists('data_types');
    }
};
