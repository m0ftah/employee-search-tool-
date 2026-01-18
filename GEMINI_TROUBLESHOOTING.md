# Gemini API Troubleshooting Guide

If CV scoring is not working, follow these steps to diagnose and fix the issue.

## Quick Checklist

1. ✅ API key is set in `.env` file
2. ✅ Config cache is cleared
3. ✅ API key is valid and active
4. ✅ Resume file is uploaded
5. ✅ Logs are checked for errors

## Step-by-Step Troubleshooting

### Step 1: Verify API Key in .env

Open your `.env` file and check:

```env
GEMINI_API_KEY=your_actual_api_key_here
```

**Common Issues:**
- ❌ Missing `GEMINI_API_KEY` line
- ❌ API key has extra spaces: `GEMINI_API_KEY= AIzaSy...` (remove space after `=`)
- ❌ API key is commented out: `# GEMINI_API_KEY=...`
- ❌ Wrong variable name: `GEMINI_KEY` instead of `GEMINI_API_KEY`

**Fix:**
```env
GEMINI_API_KEY=AIzaSyDdnxwuIVlAJfOd-miYOh5Nwn85DyuiD0U
```

### Step 2: Clear Configuration Cache

After updating `.env`, you MUST clear the config cache:

```bash
php artisan config:clear
```

**Why?** Laravel caches configuration files. If you don't clear the cache, it will still use the old (empty) value.

### Step 3: Test the API Connection

Run the test command to verify everything is working:

```bash
php artisan gemini:test
```

This will:
- Check if API key is configured
- Test the API connection
- Show you the score result
- Display any errors

**Expected Output:**
```
Testing Gemini API Configuration...

Configuration:
  API Key: ✓ Set (AIzaSyDdnx...)
  API URL: https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent
  Model: gemini-2.0-flash

Testing API connection with sample CV text...

✅ Success!
CV Score: 7.5/10
```

### Step 4: Check Laravel Logs

If the test fails, check the logs for detailed error messages:

```bash
tail -f storage/logs/laravel.log
```

Look for lines containing:
- `Gemini CV Analysis Started`
- `Gemini API error`
- `Gemini API exception`

**Common Error Messages:**

#### "Gemini API key is not configured"
- **Cause**: API key is empty or not set
- **Fix**: Add `GEMINI_API_KEY` to `.env` and run `php artisan config:clear`

#### "401 Unauthorized"
- **Cause**: Invalid API key
- **Fix**: Get a new API key from [Google AI Studio](https://makersuite.google.com/app/apikey)

#### "403 Forbidden"
- **Cause**: API key doesn't have permission for Gemini API
- **Fix**: Enable Gemini API in Google Cloud Console

#### "429 Too Many Requests"
- **Cause**: Rate limit exceeded
- **Fix**: Wait a few minutes and try again

#### "Could not extract score from Gemini response"
- **Cause**: API returned unexpected format
- **Fix**: Check logs for the actual response text

### Step 5: Verify Resume Upload

CV scoring only works if:
1. ✅ Resume file is uploaded during registration
2. ✅ File is PDF, DOC, or DOCX format
3. ✅ File size is under 10MB
4. ✅ File is not corrupted

**Test:**
- Try registering a new candidate with a resume
- Check if `resume_path` is saved in the database
- Verify the file exists in `storage/app/public/resumes/`

### Step 6: Manual Testing

You can manually test CV scoring by creating a test candidate:

```php
// In tinker: php artisan tinker
use App\Services\CVTextExtractorService;
use App\Services\GeminiService;

$extractor = new CVTextExtractorService();
$cvText = $extractor->extractText('resumes/your-resume.pdf');

$geminiService = new GeminiService();
$score = $geminiService->analyzeCV($cvText);

echo "Score: " . $score;
```

## Common Issues and Solutions

### Issue: Score is always null

**Possible Causes:**
1. API key not set or invalid
2. Config cache not cleared
3. API request failing silently
4. Resume text extraction failing

**Solution:**
1. Run `php artisan gemini:test` to diagnose
2. Check logs: `tail -f storage/logs/laravel.log`
3. Verify API key in Google AI Studio
4. Clear config: `php artisan config:clear`

### Issue: "API key is not configured" warning

**Solution:**
```bash
# 1. Add to .env
echo "GEMINI_API_KEY=your_key_here" >> .env

# 2. Clear cache
php artisan config:clear

# 3. Test
php artisan gemini:test
```

### Issue: API returns error 400

**Possible Causes:**
- Invalid request format
- Model name incorrect
- API URL incorrect

**Solution:**
Check your `.env`:
```env
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent
GEMINI_MODEL=gemini-2.0-flash
```

### Issue: Score extraction fails

**Possible Causes:**
- API response format changed
- Prompt not working correctly
- Response doesn't contain a number

**Solution:**
1. Check logs for the actual API response
2. The response should contain a number (0-10) in the first line
3. If not, the prompt might need adjustment

## Debug Mode

To enable detailed logging, check `config/logging.php` and ensure log level is set to `debug`:

```php
'level' => env('LOG_LEVEL', 'debug'),
```

Then check logs:
```bash
tail -f storage/logs/laravel.log | grep -i gemini
```

## Getting a New API Key

If your API key is invalid or expired:

1. Go to [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Sign in with your Google account
3. Click "Create API Key" or "Get API Key"
4. Copy the new key
5. Update `.env`:
   ```env
   GEMINI_API_KEY=your_new_key_here
   ```
6. Clear cache:
   ```bash
   php artisan config:clear
   ```
7. Test:
   ```bash
   php artisan gemini:test
   ```

## Verification Commands

Run these commands to verify everything:

```bash
# 1. Check if API key is in config
php artisan tinker
>>> config('gemini.api_key')
# Should show your API key (not empty)

# 2. Test API connection
php artisan gemini:test

# 3. Check logs
tail -f storage/logs/laravel.log
```

## Still Not Working?

If none of the above solutions work:

1. **Check API Key Status:**
   - Go to [Google AI Studio](https://makersuite.google.com/app/apikey)
   - Verify the key is active
   - Check if there are any restrictions

2. **Test API Directly:**
   ```bash
   curl -X POST \
     "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=YOUR_API_KEY" \
     -H 'Content-Type: application/json' \
     -d '{
       "contents": [{
         "parts": [{
           "text": "Test"
         }]
       }]
     }'
   ```

3. **Check Network/Firewall:**
   - Ensure your server can reach `generativelanguage.googleapis.com`
   - Check if there's a firewall blocking outbound requests

4. **Review Full Logs:**
   ```bash
   cat storage/logs/laravel.log | grep -A 20 "Gemini"
   ```

## Quick Fix Script

Run this to quickly fix common issues:

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test API
php artisan gemini:test
```

---

**Need More Help?**
- Check `GEMINI_API_SETUP.md` for setup instructions
- Review `storage/logs/laravel.log` for detailed errors
- Verify API key at [Google AI Studio](https://makersuite.google.com/app/apikey)
