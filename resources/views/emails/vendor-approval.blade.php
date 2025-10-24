<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Registration Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .credentials-box {
            background: white;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .login-button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Congratulations!</h1>
        <h2>Your Vendor Registration Has Been Approved</h2>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $vendor->vendor_name }}</strong>,</p>
        
        <p>We are pleased to inform you that your vendor registration has been <strong>approved</strong>! Welcome to the Survey Connect MR platform.</p>
        
        <div class="credentials-box">
            <h3>🔑 Your Login Credentials</h3>
            <p><strong>Username:</strong> {{ $username }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
            <p><strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
        </div>
        
        <h3>📋 What You Can Do Now:</h3>
        <ul>
            <li>✅ Access your vendor dashboard</li>
            <li>✅ View assigned projects</li>
            <li>✅ Track participant data</li>
            <li>✅ Monitor quota usage</li>
            <li>✅ Generate reports</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="login-button">🚀 Login to Your Dashboard</a>
        </div>
        
        <h3>📞 Need Help?</h3>
        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <p>Thank you for joining Survey Connect MR!</p>
        
        <p>Best regards,<br>
        <strong>Survey Connect MR Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Survey Connect MR. All rights reserved.</p>
    </div>
</body>
</html>
