<?php

namespace Database\Factories;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modals>
 */
class ModalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'modal-' . uniqid();
        return [
            'name' => $name,
         
            'sub_category_id' => SubCategory::inRandomOrder()->first()->id,
        ];
    }
}