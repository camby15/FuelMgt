@extends('layouts.email')

@section('title', '🎉 You\'ve Earned Points!')

@section('content')
    <div class="header">🎉 You've Earned Points!</div>
    <div class="subtext">Hello {{ $customer->first_name }},</div>

    <div class="info">
        <p>You've just earned <strong>{{ $points }}</strong> loyalty points in our <strong>{{ $program->name }}</strong> program!</p>
        
        @if($transaction)
        <p>Transaction: {{ $transaction->description }}</p>
        @endif
        
        <p>Your current points balance is: {{ $customer->pointsBalance($program->id) }}</p>
    </div>

    <a href="{{ route('loyalty.program', $program->id) }}" class="btn">🏆 View Your Rewards</a>

    <div class="footer">
        Thank you for your loyalty!<br />
        <a href="{{ route('loyalty.program', $program->id) }}">View program details</a>
    </div>
@endsection