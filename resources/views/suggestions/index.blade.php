@extends('layouts.app')

@section('content')
    <h1 class="ui header">Suggestions</h1>

    @if (count($films))
        <div class="ui divided items">
            @foreach ($films as $film)
                <div class="item">
                    <div class="content">
                        <div class="header is-flex">
                            <span class="flex">{{ $film->title }}</span>

                            <div class="ui right floated">
                                <div class="ui star rating" data-film-id="{{ $film->id }}" data-rating="{{ $film->stars }}" data-max-rating="5"></div>
                            </div>
                        </div>

                        <div class="meta">
                            <span class="language">{{ $film->language->name }}</span>
                            <span class="release_year">{{ $film->release_year }}</span>
                        </div>

                        <div class="description">
                            <p>{{ $film->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>None</p>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('js/films.js') }}"></script>
@endpush
