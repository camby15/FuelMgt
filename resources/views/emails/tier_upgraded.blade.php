@extends('layouts.email')

@section('content')
    <h1>Congratulations on Your Tier Upgrade!</h1>
    <p>Hello {{ $customer->first_name }},</p>
    
    <p>You've been upgraded from <strong>{{ $oldTier->name }}</strong> to <strong>{{ $newTier->name }}</strong> in our <strong>{{ $program->name }}</strong> loyalty program!</p>
    
    <h2>Your New Benefits:</h2>
    <p>{!! nl2br(e($newTier->benefits)) !!}</p>
    
    <p>Keep up the great work to maintain your status and enjoy even more rewards!</p>
    
    <p><a href="{{ route('loyalty.program', $program->id) }}">View your rewards</a></p>
@endsection