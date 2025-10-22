@extends('layouts.email')

@section('title', '✅ Reward Redemption Confirmation')

@section('content')
    <div class="header">✅ Reward Redemption Confirmation</div>
    <div class="subtext">Hello {{ $customer->first_name }},</div>

    <div class="info">
        <p>You've successfully redeemed your points for:</p>
        
        <h3 style="color: #069a9a; margin-bottom: 5px;">{{ $reward->name }}</h3>
        <p style="font-style: italic; margin-top: 0;">{{ $reward->description }}</p>
        
        <p><strong>Points used:</strong> {{ $redemption->points_used }}</p>
        <p><strong>Redemption ID:</strong> {{ $redemption->id }}</p>
        
        <p><strong>Current points balance:</strong> {{ $customer->pointsBalance($reward->loyalty_program_id) }}</p>
    </div>

    <div class="footer">
        Thank you for being a valued customer!<br />
        <a href="{{ route('loyalty.program', $reward->loyalty_program_id) }}">View your rewards history</a>
    </div>
@endsection