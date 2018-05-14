@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="container">
            <div class="content text-center">
                <div class="title m-b-md">
                    Laravel
                    <p class="versioninfo">Version {{ app()->version() }}</p>
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>

                @include('partials.message')

                <div class="foundation_button_test">
                    <p class="framwork_title">Bulma 0.6.2</p>
                    <p class="framwork_title">Bulma Extension 1.0.13</p>

                    <div class="block">
                        <a class="button is-primary">Primary</a>
                        <a class="button is-info">Info</a>
                        <a class="button is-success">Success</a>
                        <a class="button is-warning">Warning</a>
                        <a class="button is-danger">Danger</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection