@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="columns">
            <div class="column is-half-desktop is-clipped">
                @include('partials.league.results')
            </div>
        </div>
    </div>

@endsection