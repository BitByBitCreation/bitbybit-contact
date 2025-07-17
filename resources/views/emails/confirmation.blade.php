<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation of your contact request</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0;
            margin-top: 20px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        h2 {
            color: #2575fc;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .check-icon {
            font-size: 48px;
            margin-bottom: 15px;
            color: #4caf50;
        }
        .message-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2575fc;
            margin: 15px 0;
        }
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .warning-box {
            background: #fff3e0;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #ff9800;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 14px;
            padding: 20px;
            background: #f8f9fa;
        }
        .contact-info {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
            margin: 15px 0;
        }
        .contact-info strong {
            color: #2575fc;
        }
        .response-time {
            display: inline-block;
            padding: 8px 16px;
            background: #4caf50;
            color: white;
            border-radius: 20px;
            font-weight: 600;
            margin: 10px 0;
        }
        .next-steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="check-icon">✓</div>
            <h1>Thank you for your message!</h1>
            <p>We have successfully received your contact request</p>
        </div>
        
        <div class="content">
            <div class="section">
                <h2>Confirmation</h2>
                <p>Dear {{ $data->sender }},</p>
                <p>We have received your contact request and will process it as soon as possible. This email serves as confirmation that your message has been successfully received.</p>
                
                <div class="response-time">
                    Response time: Usually within 24 hours
                </div>
            </div>
            
            <div class="section">
                <h2>Your information at a glance</h2>
                <div class="contact-info">
                    <strong>Name:</strong>
                    <span>{{ $data->sender }}</span>
                    
                    <strong>E-Mail:</strong>
                    <span>{{ $data->email }}</span>
                    
                    @if($data->subject)
                    <strong>Subject:</strong>
                    <span>{{ $data->subject }}</span>
                    @endif
                    
                    <strong>Date:</strong>
                    <span>{{ now()->format('d.m.Y H:i') }}</span>
                </div>
                
                @if($data->summary)
                <div class="message-summary">
                    <strong>Short summary:</strong><br>
                    {{ $data->summary }}
                </div>
                @endif
            </div>
            
            <div class="section">
                <h2>What happens next?</h2>
                <div class="next-steps">
                    <ol>
                        <li><strong>Processing:</strong> We will review your request and prepare a suitable response</li>
                        <li><strong>Feedback:</strong> You will receive a personal reply from us at {{ $data->email }}</li>
                        <li><strong>Further action:</strong> If necessary, we will discuss further steps with you</li>
                    </ol>
                </div>
            </div>
            
            <div class="section">
                <h2>Important instructions</h2>
                <div class="info-box">
                    <strong>📧 Didn't receive the email?</strong><br>
                    Please also check your spam folder. Add our email address to your contacts to receive future messages.
                </div>
                
                <div class="warning-box">
                    <strong>⚠️ Urgent enquiries:</strong><br>
                    For urgent matters, please contact us directly by phone or via our other contact options.
                </div>
            </div>
            
            <div class="section">
                <h2>Contact</h2>
                <p>If you have any questions about your request or need additional information, please feel free to contact us:</p>
                <div class="contact-info">
                    <strong>E-Mail:</strong>
                    <span>contact@domain.com</span>
                    
                    <strong>Phone:</strong>
                    <span>+49 (0) 123 456789</span>
                    
                    <strong>Business hours:</strong>
                    <span>Mon-Fri: 9:00 - 17:00</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was automatically generated. Please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} Your company | All rights reserved</p>
    </div>
</body>
</html>