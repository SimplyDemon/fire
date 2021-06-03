<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $title = $this->faker->unique()->sentence();
        $slug  = Str::slug( $title, '-' );

        return [
            'title'       => $title,
            'slug'        => $slug,
            'description' => $this->faker->optional( $weight = 0.8 )->sentence(),
            'price'       => $this->faker->randomFloat( 2, 0.01 ),
        ];
    }
}
