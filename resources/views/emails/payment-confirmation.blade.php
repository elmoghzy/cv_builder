<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - CV Builder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #10b981;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
        }
        .success-icon {
            font-size: 48px;
            color: #10b981;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .payment-details {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .highlight {
            color: #10b981;
            font-weight: bold;
        }
        .status-badge {
            background-color: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CV Builder</div>
            <div class="success-icon">✅</div>
            <h1>Payment Successful!</h1>
            <span class="status-badge">CONFIRMED</span>
        </div>

        <div class="content">
            <p>Dear <span class="highlight">{{ $payment->user->name }}</span>,</p>
            
            <p>Thank you for your payment! Your transaction has been successfully processed, and your CV is now being generated.</p>

            <div class="payment-details">
                <h3 style="margin-top: 0; color: #10b981;">Payment Details</h3>
                
                <div class="detail-row">
                    <span>Transaction ID:</span>
                    <span><strong>{{ $payment->transaction_id }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span>CV Title:</span>
                    <span>{{ $payment->cv->title }}</span>
                </div>
                
                <div class="detail-row">
                    <span>Payment Method:</span>
                    <span>PayMob</span>
                </div>
                
                <div class="detail-row">
                    <span>Payment Date:</span>
                    <span>{{ $payment->paid_at ? $payment->paid_at->format('F j, Y \a\t g:i A') : now()->format('F j, Y \a\t g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span>Amount Paid:</span>
                    <span><strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong></span>
                </div>
            </div>

            <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 5px; padding: 15px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #f59e0b;">⏳ What happens next?</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Your CV is being generated in the background</li>
                    <li>You'll receive another email with the download link within 5 minutes</li>
                    <li>The download link will be available for 7 days</li>
                    <li>You can also access your CV from your dashboard</li>
                </ul>
            </div>

            <p>If you don't receive the CV download email within 10 minutes, please check your spam folder or contact our support team.</p>

            <p>Thank you for choosing CV Builder for your professional CV needs!</p>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $payment->user->email }}</p>
            <p>Transaction ID: {{ $payment->transaction_id }}</p>
            <p>If you have any questions about this payment, please contact us at support@cvbuilder.com</p>
            <p>&copy; {{ date('Y') }} CV Builder. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
