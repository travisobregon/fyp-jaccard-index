@extends('layouts.app')

@section('content')
    <div style="text-align: center">
        <h1 class="ui header">Mean Absolute Error: {{ $meanAbsoluteError }}</h1>

        <h1 class="ui header">Root Mean Square Error: {{ $rootMeanSquareError }}</h1>
    </div>
@endsection