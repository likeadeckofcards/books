<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-blue-600 border-b border-blue-600">
                <div class="w-6 inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>

                Return to Book List
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3>{{ $book->title }}</h3>
            <h4>Author: {{ $book->author }}</h4>
            <h5>Published On: {{ $book->published_on->format('m/j/Y') }}</h5>
        </div>
    </div>
</x-app-layout>
