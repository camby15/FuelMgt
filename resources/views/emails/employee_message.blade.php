<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HR Message</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 8px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center">
                            <img src="https://i.imgur.com/23Q8lZE.png" alt="Company Logo" style="width: 120px; display: block; margin-bottom: 20px;">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 style="color: #333333; text-align: center; font-size: 24px; margin-bottom: 10px;">New Message from HR</h2>
                            <p style="color: #555555; font-size: 16px; text-align: center; margin-bottom: 30px;">
                                You have received a message from the HR department.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; border-radius: 6px; padding: 20px; margin-bottom: 20px;">
                    
                                <tr>
                                    <td style="color: #555555; line-height: 1.6;">{!! nl2br(e($body)) !!}</td>
                                </tr>
                            </table>

                            @if (!empty($attachmentUrl))
                            <div style="text-align: center; margin: 20px 0;">
                                <a href="{{ $attachmentUrl }}" style="display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 20px; border-radius: 5px; font-weight: bold;">
                                    📎 Download Attachment
                                </a>
                            </div>
                            @endif

                            <p style="text-align: center; font-size: 12px; color: #888888; margin-top: 30px;">
                                This is an automated message from the HR system of <strong>{{ $companyName ?? 'your company' }}</strong>.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
