<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Move a book up in a list adjusts other books down
     *
     * @return void
     */
    public function testAddANewBook()
    {
        $user = User::factory()->create();
        $volumeId = 'HcqPDwAAQBAJ'; // Laravel: Up & Running

        $response = $this->actingAs($user)->post(route('api.books.store'), ['volume_id' => $volumeId]);

        $response->assertStatus(200);
        $this->assertEquals(1, $user->books()->count());
        $newBook = $user->books()->latest()->first();

        $this->assertEquals($newBook->title, 'Laravel: Up & Running');
        $this->assertEquals($newBook->author, 'Matt Stauffer');
        $this->assertEquals($newBook->published_on->format('Y-m-d'), '2019-04-01');
    }

    /**
     * Test books order when sorting on title
     *
     * @return void
     */
    public function testBooksOrderWithTitle()
    {
        $user = User::factory()->create();
        $books = Book::factory()
            ->count(3)
            ->state(new Sequence(
                ['volume_id' => 'abcd12345678', 'title' => 'The man without a shoe'],
                ['volume_id' => 'dbca12345678', 'title' => 'Woman in the shoe'],
                ['volume_id' => 'reot12345678', 'title' => 'Little Bo Peep']
            ))
            ->create();

        $user->books()->attach($books->mapWithKeys(fn($book, $index) => [$book->id => ['order' => $index + 1]]));

        $response = $this->actingAs($user)->get(route('api.books.index', ['order' => 'title']), ['newOrder' => 1]);

        $response->assertStatus(200);
        $response->assertJson([
            ['volume_id' => 'reot12345678'],
            ['volume_id' => 'abcd12345678'],
            ['volume_id' => 'dbca12345678'],
        ]);
    }

    /**
     * Test books order when sorting on title
     *
     * @return void
     */
    public function testBooksOrderWithAuthor()
    {
        $user = User::factory()->create();
        $books = Book::factory()
            ->count(3)
            ->state(new Sequence(
                ['volume_id' => 'abcd12345678', 'author' => 'Themsaid'],
                ['volume_id' => 'dbca12345678', 'author' => 'Taylor Otwell'],
                ['volume_id' => 'reot12345678', 'author' => 'Ninja Parade']
            ))
            ->create();

        $user->books()->attach($books->mapWithKeys(fn($book, $index) => [$book->id => ['order' => $index + 1]]));

        $response = $this->actingAs($user)->get(route('api.books.index', ['order' => 'author']));

        $response->assertStatus(200);
        $response->assertJson([
            ['volume_id' => 'reot12345678'],
            ['volume_id' => 'dbca12345678'],
            ['volume_id' => 'abcd12345678'],
        ]);
    }

    /**
     * Test books order when sorting on title
     *
     * @return void
     */
    public function testBooksOrder()
    {
        $user = User::factory()->create();
        $books = Book::factory()
            ->count(3)
            ->state(new Sequence(
                ['volume_id' => 'dbca12345678'],
                ['volume_id' => 'reot12345678'],
                ['volume_id' => 'abcd12345678'],
            ))
            ->create();

        $user->books()->attach($books->mapWithKeys(fn($book, $index) => [$book->id => ['order' => $index + 1]]));

        $response = $this->actingAs($user)->get(route('api.books.index'));

        $response->assertStatus(200);
        $response->assertJson([
            ['volume_id' => 'dbca12345678'],
            ['volume_id' => 'reot12345678'],
            ['volume_id' => 'abcd12345678'],
        ]);
    }

    /**
     * Move a book up in a list adjusts other books down
     *
     * @return void
     */
    public function testChangeBookOrderUp()
    {
        $user = User::factory()->create();
        $books = Book::factory()->count(4)->create();

        $user->books()->attach($books->mapWithKeys(fn($book, $index) => [$book->id => ['order' => $index + 1]]));
        $movedBook = $books->last();

        $response = $this->actingAs($user)->patch(route('api.books.update', $movedBook->id), ['newOrder' => 1]);

        $response->assertStatus(200);
        $newBooks = $user->books()->get();

        $books->each(function($book, $index) use ($newBooks, $movedBook) {
            if($book->id === $movedBook->id) {
                $this->assertTrue($newBooks->where('id', $book->id)->first()->pivot->order === 1);
            } else {
                $this->assertTrue($newBooks->where('id', $book->id)->first()->pivot->order === $index + 1);
            }
        });
    }

    /**
     * Move a book down in a list adjusts other books up
     *
     * @return void
     */
    public function testChangeBookOrderDown()
    {
        $user = User::factory()->create();
        $books = Book::factory()->count(4)->create();

        $user->books()->attach($books->mapWithKeys(fn($book, $index) => [$book->id => ['order' => $index + 1]]));
        $movedBook = $books->first();

        $response = $this->actingAs($user)->patch(route('api.books.update', $movedBook->id), ['newOrder' => 4]);

        $response->assertStatus(200);
        $newBooks = $user->books()->get();

        $books->each(function($book, $index) use ($newBooks, $movedBook) {
            if($book->id === $movedBook->id) {
                $this->assertTrue($newBooks->where('id', $book->id)->first()->pivot->order === 4);
            } else {
                $this->assertTrue($newBooks->where('id', $book->id)->first()->pivot->order === $index + 1);
            }
        });
    }

    /**
     * Delete a book
     *
     * @return void
     */
    public function testRemoveABook()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $user->books()->attach($book->id, ['order' => 1]);

        $response = $this->actingAs($user)->delete(route('api.books.destroy', $book->id));

        $response->assertStatus(200);
        $this->assertEquals(0, $user->books()->count());
    }
}
