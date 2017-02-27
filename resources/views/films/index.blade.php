@extends('layouts.app')

@section('content')
    <div class="ui divided items">
        @foreach ($films as $film)
            <div class="item">
                <div class="content">
                    <div class="header is-flex">
                        <span class="flex">{{ $film->title }}</span>

                        <div class="ui right floated">
                            <form action="{{ route('dislikes.store') }}" method="POST" class="ui right floated">
                                {{ csrf_field() }}

                                <input type="hidden" name="film_id" value="{{ $film->id }}">

                                <button type="submit" class="none">
                                    <i class="thumbs {{ Auth::user()->dislikes->contains('film_id', $film->id) ? '' : 'outline' }} down icon"></i>
                                    {{ $film->dislikes->count() }}
                                </button>
                            </form>

                            <form action="{{ route('likes.store') }}" method="POST" class="ui right floated" style="margin-right: 1rem">
                                {{ csrf_field() }}

                                <input type="hidden" name="film_id" value="{{ $film->id }}">

                                <button type="submit" class="none">
                                    <i class="thumbs {{ Auth::user()->likes->contains('film_id', $film->id) ? '' : 'outline' }} up icon"></i>
                                    {{ $film->likes->count() }}
                                </button>
                            </form>
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

        {{ $films->links() }}
    </div>
@endsection