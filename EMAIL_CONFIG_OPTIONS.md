# Email Configuration Options for Survey Connect MR

## Option 1: Use Log Driver (Development)
Add these lines to your .env file:
```
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@surveyconnectmr.com
MAIL_FROM_NAME="Survey Connect MR"
```

## Option 2: Use Gmail SMTP (Production)
Add these lines to your .env file:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Survey Connect MR"
```

## Option 3: Use Custom SMTP Server
Add these lines to your .env file:
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Survey Connect MR"
```

## Option 4: Disable Email Sending (Testing)
The system will now log email attempts instead of sending them.
Check the logs at: storage/logs/laravel.log

## After updating .env file:
1. Run: php artisan config:cache
2. Run: php artisan config:clear
3. Test vendor approval process
