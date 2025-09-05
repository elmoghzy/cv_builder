<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your CV is Ready for Download</title>
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
            border-bottom: 2px solid #3b82f6;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .download-button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .download-button:hover {
            background-color: #2563eb;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
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
            color: #3b82f6;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CV Builder</div>
            <h1>Your CV is Ready!</h1>
        </div>

        <div class="content">
            <p>Dear <span class="highlight">{{ $user->name }}</span>,</p>
            
            <p>Great news! Your CV "<strong>{{ $cv->title }}</strong>" has been successfully generated and is ready for download.</p>
            
            <div class="info-box">
                <h3>CV Details:</h3>
                <ul>
                    <li><strong>Title:</strong> {{ $cv->title }}</li>
                    <li><strong>Template:</strong> {{ $cv->template->name }}</li>
                    <li><strong>Generated:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</li>
                    <li><strong>Download Expires:</strong> {{ now()->addDays(7)->format('F j, Y') }}</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ $downloadUrl }}" class="download-button">
                    📄 Download Your CV (PDF)
                </a>
            </div>

            <div class="info-box">
                <h4>📋 What's Next?</h4>
                <ul>
                    <li>Download your ATS-compliant CV in PDF format</li>
                    <li>Start applying to your dream jobs</li>
                    <li>Track your applications and follow up</li>
                    <li>Update your LinkedIn profile with your new information</li>
                </ul>
            </div>

            <div class="info-box">
                <h4>💡 Tips for Job Searching:</h4>
                <ul>
                    <li>Tailor your CV for each specific job application</li>
                    <li>Use keywords from the job description</li>
                    <li>Keep your CV updated with latest experiences</li>
                    <li>Network with professionals in your field</li>
                </ul>
            </div>

            <p>If you need to make any changes to your CV, you can always log in to your account and create a new version.</p>

            <p>Best of luck with your job search!</p>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $user->email }}</p>
            <p>If you have any questions, please contact us at support@cvbuilder.com</p>
            <p>&copy; {{ date('Y') }} CV Builder. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
