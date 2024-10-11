<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
     @foreach ($errors->all() as $error)
         <div>{{$error}}</div>
     @endforeach
 @endif
            <form action="{{ route('url.shorten') }}" method="POST">
                @csrf
                <x-text-input name="url" class="w-full mb-4" placeholder="https://example.com" required />
                <x-primary-button>
                    Shorten URL
                </x-primary-button>
            </form>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- {{ dd($urls) }} --}}
            <x-url-table :urls="$urls" />
        </div>
    </div>
</x-app-layout>
