@extends('layouts.app')

@section('content')
    <div class="ui success message visible" style="margin-top: 0">
        <i class="close icon"></i>

        <div class="header">
            Welcome back!
        </div>

        <p>You are logged in!</p>
    </div>

    <a href="{{ route('films.index') }}">Films</a>

    @push('scripts')
        <script>
            $('.message .close').click(function () {
                $(this).parent().remove();
            });
        </script>
    @endpush
@endsection
