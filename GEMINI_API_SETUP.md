# Gemini API Setup Guide

This guide explains how to configure and update the Google Gemini API for CV analysis in the Employee Search Tool.

## Overview

The application uses Google Gemini API to automatically analyze and score candidate resumes. The API key and configuration are now stored in environment variables for better security and flexibility.

## Files Changed

1. **`config/gemini.php`** - Configuration file for Gemini API settings
2. **`app/Services/GeminiService.php`** - Service class for Gemini API interactions
3. **`app/Http/Controllers/CandidateRegistrationController.php`** - Updated to use the new service

## Setup Instructions

### Step 1: Get Your Gemini API Key

1. Go to [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Sign in with your Google account
3. Click "Create API Key"
4. Copy your API key (it will look like: `AIzaSy...`)

### Step 2: Add API Key to Environment Variables

Open your `.env` file and add the following:

```env
# Gemini API Configuration
GEMINI_API_KEY=your_api_key_here
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent
GEMINI_MODEL=gemini-2.0-flash
GEMINI_TIMEOUT=30
GEMINI_CONNECT_TIMEOUT=10
```

**Important**: Replace `your_api_key_here` with your actual API key from Step 1.

### Step 3: Update API Key (If Needed)

To update your API key in the future:

1. Get a new API key from Google AI Studio
2. Update the `GEMINI_API_KEY` value in your `.env` file
3. Clear the configuration cache:
   ```bash
   php artisan config:clear
   ```

### Step 4: Customize the CV Analysis Prompt (Optional)

You can customize how CVs are analyzed by modifying the prompt. Add this to your `.env`:

```env
GEMINI_CV_PROMPT=Your custom prompt here in Arabic or English
```

Or edit `config/gemini.php` directly to change the default prompt.

## Configuration Options

All configuration options are available in `config/gemini.php`:

| Option | Environment Variable | Default | Description |
|--------|---------------------|---------|-------------|
| API Key | `GEMINI_API_KEY` | `''` | Your Gemini API key (required) |
| API URL | `GEMINI_API_URL` | Gemini 2.0 Flash URL | API endpoint URL |
| Model | `GEMINI_MODEL` | `gemini-2.0-flash` | Gemini model to use |
| Timeout | `GEMINI_TIMEOUT` | `30` | Request timeout in seconds |
| Connect Timeout | `GEMINI_CONNECT_TIMEOUT` | `10` | Connection timeout in seconds |
| CV Prompt | `GEMINI_CV_PROMPT` | Arabic prompt | Prompt for CV analysis |

## Available Gemini Models

You can use different Gemini models by updating `GEMINI_MODEL` and `GEMINI_API_URL`:

### Gemini 2.0 Flash (Recommended - Fast)
```env
GEMINI_MODEL=gemini-2.0-flash
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent
```

### Gemini 1.5 Pro (More Accurate)
```env
GEMINI_MODEL=gemini-1.5-pro
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent
```

### Gemini 1.5 Flash (Balanced)
```env
GEMINI_MODEL=gemini-1.5-flash
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent
```

## Usage

The Gemini service is automatically used when:
- A candidate registers with a resume
- The resume is uploaded and processed
- The CV text is extracted
- The API analyzes the CV and returns a score (0-10)

### Manual Usage

You can also use the service manually in your code:

```php
use App\Services\GeminiService;

$geminiService = new GeminiService();

// Check if API is configured
if ($geminiService->isConfigured()) {
    $score = $geminiService->analyzeCV($cvText);
    // $score will be a float between 0-10 or null
}
```

## Troubleshooting

### API Key Not Working

1. **Verify the API key is correct**:
   - Check for extra spaces or characters
   - Ensure it starts with `AIzaSy`

2. **Check API key permissions**:
   - Make sure the API key has access to Gemini API
   - Check your Google Cloud Console for API restrictions

3. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```

### API Errors

Check the Laravel logs for detailed error messages:
```bash
tail -f storage/logs/laravel.log
```

Common errors:
- **401 Unauthorized**: Invalid API key
- **403 Forbidden**: API key doesn't have permission
- **429 Too Many Requests**: Rate limit exceeded
- **500 Internal Server Error**: API service issue

### Rate Limits

Gemini API has rate limits. If you exceed them:
- Wait before making more requests
- Consider implementing request queuing
- Upgrade your Google Cloud plan if needed

## Security Best Practices

1. **Never commit `.env` file** to version control
2. **Use different API keys** for development and production
3. **Restrict API key** to specific IPs in Google Cloud Console (for production)
4. **Rotate API keys** regularly
5. **Monitor API usage** in Google Cloud Console

## Testing

To test if the API is working:

1. Register a new candidate with a resume
2. Check the candidate's score in the admin panel
3. Check Laravel logs for any errors:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Support

For issues with:
- **Gemini API**: Check [Google AI Studio Documentation](https://ai.google.dev/docs)
- **Application**: Check Laravel logs and error messages
- **Configuration**: Verify `.env` file and run `php artisan config:clear`

## Example .env Configuration

```env
# Gemini API Configuration
GEMINI_API_KEY=AIzaSyDdnxwuIVlAJfOd-miYOh5Nwn85DyuiD0U
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent
GEMINI_MODEL=gemini-2.0-flash
GEMINI_TIMEOUT=30
GEMINI_CONNECT_TIMEOUT=10

# Optional: Custom CV Analysis Prompt
# GEMINI_CV_PROMPT=أنت محلل سير ذاتية محترف. قم بتقييم السيرة الذاتية التالية من 10 نقاط بناءً على الإنجازات، الكلمات المفتاحية ذات الصلة، والتنسيق. يجب أن يكون الناتج هو **رقم واحد فقط** في السطر الأول، ولا شيء سواه. لا تكتب أي تفسير أو مقدمة أو تفاصيل.
```

---

**Last Updated**: 2024  
**Version**: 1.0
