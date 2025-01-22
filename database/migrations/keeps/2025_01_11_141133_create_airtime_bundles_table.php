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
        Schema::create('airtime_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('account_type_id')
                ->references('id')
                ->on('account_types')
                ->onDelete('cascade');
            $table->foreignId('network_id')
                ->references('id')
                ->on('networks')
                ->onDelete('cascade');
            $table->foreignId('automate_id')
                ->references('id')
                ->on('automations')
                ->onDelete('cascade');
            $table->string('discount')->nullable();
            $table->string('plan_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtime_bundles');
    }
};
