<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contract Agreement</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            line-height: 1.4;
            padding: 20px;
            color: #000;
        }
        h1 {
            font-size: 20px;
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        p, li {
            margin: 3px 0;
        }
        ul {
            padding-left: 15px;
        }
        .signature-block {
            margin-top: 10px;
            border-top: 1px dashed #aaa;
            padding-top: 5px;
        }
        .signature-block img {
            max-height: 60px;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #888;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h1>CONTRACT AGREEMENT</h1>

    <h2>Contract Title</h2>
    <p><strong>{{ $contractName ?? 'contract Name' }}</strong></p>

    <h2>Parties Involved</h2>
    <p><strong>Company:</strong> {{ Auth::user()->fullname ?? '[Company]' }}<br>
       <strong>Email:</strong> {{ Auth::user()->personal_email ?? '[Email]' }}</p>

    @if(!empty($contract->emails) && is_array($contract->emails))
        <p><strong>Clients:</strong></p>
        <ul>
            @foreach($contract->emails as $email)
                <li>{{ $email }}</li>
            @endforeach
        </ul>
    @else
        <p><strong>Clients:</strong> [Client Emails Here]</p>
    @endif

    @if($contract->status == 'active')
        <p><strong>Date of Agreement:</strong> {{ \Carbon\Carbon::parse($contract->agreement_date ?? now())->format('F j, Y') }}</p>
    @endif

    <h2>Scope of Work</h2>
    <p>{{ $contract->notes ?? 'Not specified.' }}</p>

    <h2>Contract Duration</h2>
    <p><strong>Start:</strong> {{ \Carbon\Carbon::parse($contract->start_date ?? now())->format('F j, Y') }}<br>
       <strong>End:</strong> {{ \Carbon\Carbon::parse($contract->end_date ?? now())->format('F j, Y') }}<br>
       <strong>Renewal:</strong> {{ $contract->renewal_terms ?? 'None' }}</p>

    <h2>Payment Terms</h2>
    <p><strong>Value:</strong> {{ $contract->total_value ?? '[Amount]' }}<br>
       <strong>Schedule:</strong> {{ $contract->payment_schedule ?? '[Schedule]' }}<br>
       <strong>Method:</strong> {{ $contract->payment_method ?? '[Payment Method]' }}<br>
       <strong>Penalty:</strong> {{ $contract->late_penalty ?? 'None' }}</p>

    <h2>Confidentiality Clause</h2>
    <p>{{ $contract->confidentiality_clause ?? 'Both parties agree to maintain confidentiality of disclosed information.' }}</p>

    <h2>Termination Clause</h2>
    <p>{{ $contract->termination_clause ?? 'Contract may be terminated by either party with cause or agreement.' }}</p>

    <h2>Liability Clause</h2>
    <p>{{ $contract->liability_clause ?? 'Liability limited to contract value.' }}</p>

    <h2>Dispute Resolution</h2>
    <p>{{ $contract->dispute_clause ?? 'Disputes will be resolved via arbitration or Ghanaian courts.' }}</p>

    @if($contract->status == 'active')
        <h2>Signatures</h2>
        @foreach($signatures as $signature)
            @if($signature->contract_id == $contract->id)
                <div class="signature-block">
                    <p><strong>{{ $signature->signer_name ?? '[Name]' }}</strong><br>
                       {{ $signature->signer_email ?? '[Email]' }}<br>
                       @if($signature->signed_at)
                           Signed: {{ \Carbon\Carbon::parse($signature->signed_at)->format('F j, Y') }}
                       @endif
                    </p>
                    @if(!empty($signature->signature_base64))
                        <img src="{{ $signature->signature_base64 }}" alt="Signature">
                    @else
                        <p><em>No signature image available</em></p>
                    @endif
                </div>
            @endif
        @endforeach
    @endif

    <div class="footer">
        &copy; {{ date('Y') }} {{ Auth::user()->fullname ?? 'Company Name' }}. All rights reserved.
    </div>
</body>
</html>
