<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)
            ->afterCreating(function($user) {
                $user->books()->attach(Book::factory()->count(5)->create()->mapWithKeys(fn($book, $index) => [$book->id => ['order' => $index + 1]]));
            })
            ->create();
    }
}
