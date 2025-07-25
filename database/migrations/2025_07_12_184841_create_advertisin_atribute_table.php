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
        Schema::create('advertising_attribute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertising_id')->constrained('advertisings');
            $table->foreignId('attribute_id')->constrained('attributes');
            $table->text('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertising_attribute');
    }
};
