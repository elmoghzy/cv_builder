#!/bin/bash

echo "🚀 CV Builder - Google OAuth Setup"
echo "=================================="
echo ""

# Check if .env file exists
if [ ! -f .env ]; then
    echo "❌ .env file not found!"
    exit 1
fi

echo "📝 Please enter your Google OAuth credentials from Google Cloud Console:"
echo ""

# Get Client ID
read -p "🔑 Enter your Google Client ID: " CLIENT_ID
if [ -z "$CLIENT_ID" ]; then
    echo "❌ Client ID cannot be empty!"
    exit 1
fi

# Get Client Secret
read -p "🔐 Enter your Google Client Secret: " CLIENT_SECRET
if [ -z "$CLIENT_SECRET" ]; then
    echo "❌ Client Secret cannot be empty!"
    exit 1
fi

# Get App URL (optional)
read -p "🌐 Enter your app URL (default: http://127.0.0.1:8001): " APP_URL
if [ -z "$APP_URL" ]; then
    APP_URL="http://127.0.0.1:8001"
fi

echo ""
echo "🔄 Updating .env file..."

# Update .env file
sed -i "s/GOOGLE_CLIENT_ID=.*/GOOGLE_CLIENT_ID=${CLIENT_ID}/" .env
sed -i "s/GOOGLE_CLIENT_SECRET=.*/GOOGLE_CLIENT_SECRET=${CLIENT_SECRET}/" .env
sed -i "s|GOOGLE_REDIRECT_URI=.*|GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback|" .env

echo "✅ Google OAuth credentials updated successfully!"
echo ""
echo "📋 Current settings:"
echo "   Client ID: ${CLIENT_ID}"
echo "   Client Secret: ${CLIENT_SECRET:0:10}..."
echo "   Redirect URI: ${APP_URL}/auth/google/callback"
echo ""
echo "🎯 Next steps:"
echo "   1. Make sure your Google Cloud Console has the redirect URI: ${APP_URL}/auth/google/callback"
echo "   2. Run: php artisan config:clear"
echo "   3. Run: php artisan serve --host=127.0.0.1 --port=8001"
echo "   4. Test login at: ${APP_URL}/login"
echo ""
echo "🔗 Google Cloud Console: https://console.cloud.google.com/apis/credentials"
