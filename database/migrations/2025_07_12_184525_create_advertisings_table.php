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
        Schema::create('advertisings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title')->default(''); // Added title column for advertising
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('purpose', ['sell', 'buy'])->nullable();
            $table->string('status')->default('active'); // Added status column for advertising status
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories');
            $table->foreignId('modal_id')->nullable()->constrained('modals');
            $table->foreignId('submodal_id')->nullable()->constrained('submodals');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisings');
    }
};
