<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name'=>fake()->name,
            'category_id'=>fake()->numberBetween(1,20),
            'description'=>fake()->realText,
            'status'=>fake()->numberBetween(1,3),
            'ratings'=>"".fake()->numberBetween(0,5)."",
            'price'=>fake()->numberBetween(1,100000),
            'stock'=>fake()->numberBetween(0,100)
        ];
    }
}
