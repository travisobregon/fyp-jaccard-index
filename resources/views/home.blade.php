@extends('layouts.app')

@section('content')
    <div class="ui success message visible" style="margin-top: 0">
        <i class="close icon"></i>

        <div class="header">
            Welcome back!
        </div>

        <p>You are logged in!</p>
    </div>

    <div class="is-flex">
        <a class="flex" href="{{ route('films.index') }}">Films</a>
        <a class="flex" href="/suggestions">Suggestions</a>
        <a href="/metrics">Metrics</a>
    </div>
@endsection

@push('scripts')
    <script>
        $('.message .close').click(function () {
            $(this).parent().remove();
        });
    </script>
@endpush
