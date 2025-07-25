<?php

namespace Database\Factories;

use App\Models\Modal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submodal>
 */
class SubmodalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'submodal-' . uniqid();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'modal_id' => Modal::inRandomOrder()->first()->id,
        ];
    }
}