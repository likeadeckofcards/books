<?php

namespace App\Http\Controllers;

use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BookSearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Client $client)
    {
        $client->setDeveloperKey(config('services.google.api_key'));
        $service = new \Google_Service_Books($client);

        $results = $service->volumes->listVolumes($request->get('search'), [
            'orderBy' => 'relevance',
            'startIndex' => 20 * ($request->get('page', 1) - 1),
            'maxResults' => 20
        ]);

        return response()->json(collect($results->getItems())->map(function($item) {
            return [
                'id' => Arr::get($item, 'id'),
                'title' => Arr::get($item, 'volumeInfo.title'),
                'author' => Arr::first(Arr::get($item, 'volumeInfo.authors')),
            ];
        }));
    }
}
