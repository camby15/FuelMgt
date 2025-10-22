Reward Redemption Confirmation

Hello {{ $customer->first_name }},

You've successfully redeemed your points for:

{{ $reward->name }}
{{ $reward->description }}

Points used: {{ $redemption->points_used }}
Redemption ID: {{ $redemption->id }}

Your current points balance is: {{ $customer->pointsBalance($reward->loyalty_program_id) }}

Thank you for being a valued customer!
