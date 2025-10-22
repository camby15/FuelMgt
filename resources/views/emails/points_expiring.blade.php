@extends('layouts.email')

@section('title', '⚠️ Your Points Are About to Expire')

@section('content')
    <div class="header">⚠️ Your Points Are About to Expire</div>
    <div class="subtext">Hello {{ $customer->first_name }},</div>

    <div class="info">
        <p>We wanted to let you know that <strong>{{ $points }}</strong> of your loyalty points in our <strong>{{ $program->name }}</strong> program will expire on {{ $expiryDate->format('F j, Y') }}.</p>
        
        <p>Don't let them go to waste! Redeem them now for exciting rewards.</p>
    </div>

    <a href="{{ route('loyalty.program', $program->id) }}" class="btn" style="background-color: #e67e22;">🎁 Redeem Your Points Now</a>

    <div class="footer">
        Thank you for being a valued customer!<br />
        <a href="{{ route('loyalty.program', $program->id) }}">View available rewards</a>
    </div>
@endsection