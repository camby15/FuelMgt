@extends('layouts.email')

@section('title', '🎁 You\'ve Been Invited!')

@section('content')
    <div class="header">🎁 You've Been Invited!</div>
    <div class="subtext">Hello,</div>

    <div class="info">
        <p>{{ $customer->first_name }} has invited you to join our <strong>{{ $program->name }}</strong> loyalty program!</p>
        
        <p>Sign up now and you'll both earn <strong>{{ $referral->points_awarded }}</strong> bonus points!</p>
    </div>

    <a href="{{ route('referral.process', $referral->token) }}" class="btn" style="background-color: #9b59b6;">✨ Accept Your Invitation</a>

    <div class="footer">
        Thank you!<br />
        <a href="{{ route('referral.process', $referral->token) }}">Click here if the button above doesn't work</a>
    </div>
@endsection