@extends('welcome.layout')

@section('content')
    @include('welcome.header')
    <main>
        @include('welcome.features')
        @include('welcome.exhibition')
        @include('welcome.integrate')
        @include('welcome.pricing')
        @include('welcome.promo')
        @include('welcome.testimonials')
        @include('welcome.footer')
        @include('welcome.company')
    </main>
@endsection
