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
         Schema::create('advertising_subattribute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertising_id')->constrained('advertisings')->onDelete('cascade');
            $table->foreignId('subattribute_id')->nullable()->constrained('subattributes')->onDelete('cascade');
            $table->string('value')->nullable(); // للـ attributes بدون subattribute
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertising_subattribute');
    }
};
// 