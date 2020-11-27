<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booksQuery = request()->user()->books();

        if(in_array(request()->get('order'),  ['title', 'author'])) {
            $booksQuery->orderBy(request()->get('order'));
        } else {
            $booksQuery->orderBy('book_user.order');
        }

        return response()->json($booksQuery->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {
        $book = Book::firstOrNew([
            'volume_id' => $request->volume_id,
        ], []);

        if(empty($book->title)) {
            $client->setDeveloperKey(config('services.google.api_key'));
            $service = new \Google_Service_Books($client);

            $volume = $service->volumes->get($request->volume_id);

            $book->title = Arr::get($volume, 'volumeInfo.title');
            $book->author = Arr::first(Arr::get($volume, 'volumeInfo.authors'));
            $book->published_on = Carbon::parse(Arr::get($volume, 'volumeInfo.publishedDate'));

            $book->save();
        }

        $maxOrder = $request->user()->books()->max('book_user.order') ?? 0;

        $request->user()->books()->syncWithoutDetaching([$book->id => ['order' => $maxOrder + 1]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $user = $request->user();
        $newOrder = $request->newOrder;
        $currentOrder = $user->books()->where('book_id', $book->id)->first()->pivot->order;

        if($newOrder < $currentOrder) {
            $startOrder = $newOrder;
            $endOrder = $currentOrder;
            $method = 'increment';
        } else {
            $startOrder = $currentOrder;
            $endOrder = $newOrder;
            $method = 'decrement';
        }

        DB::table('book_user')
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereBetween('order', [$startOrder, $endOrder])
            ->{$method}('order');

        $user->books()->syncWithoutDetaching([$book->id => ['order' => $newOrder]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $user = request()->user();
        $order = $user->books()->where('book_id', $book->id)->first()->pivot->order;

        $user->books()->detach($book->id);

        DB::table('book_user')->where('user_id', $user->id)
            ->where('order', '>', $order)
            ->decrement('order');
    }
}
