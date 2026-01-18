<?php

namespace App\Console\Commands;

use App\Services\GeminiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestGeminiAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Gemini API configuration and connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Gemini API Configuration...');
        $this->newLine();

        // Check configuration
        $apiKey = config('gemini.api_key');
        $apiUrl = config('gemini.api_url');
        $model = config('gemini.model');

        $this->info('Configuration:');
        $this->line('  API Key: ' . (!empty($apiKey) ? '✓ Set (' . substr($apiKey, 0, 10) . '...)' : '✗ Not set'));
        $this->line('  API URL: ' . $apiUrl);
        $this->line('  Model: ' . $model);
        $this->newLine();

        if (empty($apiKey)) {
            $this->error('❌ GEMINI_API_KEY is not set in your .env file!');
            $this->warn('Please add: GEMINI_API_KEY=your_api_key_here');
            $this->warn('Then run: php artisan config:clear');
            return 1;
        }

        // Test the service
        $geminiService = new GeminiService();

        if (!$geminiService->isConfigured()) {
            $this->error('❌ Gemini service reports it is not configured!');
            return 1;
        }

        $this->info('Testing API connection with sample CV text...');
        $this->newLine();

        // Sample CV text for testing
        $sampleCVText = "John Doe
Software Engineer
5 years of experience in PHP and Laravel
Bachelor's degree in Computer Science
Skills: PHP, Laravel, JavaScript, MySQL
Certifications: Laravel Certified Developer";

        $this->line('Sample CV Text:');
        $this->line($sampleCVText);
        $this->newLine();

        $this->info('Sending request to Gemini API...');
        
        $score = $geminiService->analyzeCV($sampleCVText);

        if ($score !== null) {
            $this->newLine();
            $this->info('✅ Success!');
            $this->line('CV Score: ' . $score . '/10');
            
            // Color code the score
            if ($score >= 8) {
                $this->info('Score is excellent!');
            } elseif ($score >= 6) {
                $this->comment('Score is good.');
            } else {
                $this->warn('Score needs improvement.');
            }
        } else {
            $this->newLine();
            $this->error('❌ Failed to get score from Gemini API');
            $this->warn('Check the logs for more details:');
            $this->line('  tail -f storage/logs/laravel.log');
            return 1;
        }

        $this->newLine();
        $this->info('Test completed!');

        return 0;
    }
}
