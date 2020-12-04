<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'volume_id' => $this->faker->regexify('[a-zA-Z0-9]{12}'),
            'title' => $this->faker->words($this->faker->numberBetween(1, 6), true),
            'author' => $this->faker->name,
            'published_on' => $this->faker->dateTimeBetween(),
        ];
    }
}
