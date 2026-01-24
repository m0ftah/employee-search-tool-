<?php

namespace App\Console\Commands;

use App\Services\CVScoringService;
use Illuminate\Console\Command;

class TestAIProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test {--provider= : Override provider (gemini|deepseek)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the configured AI provider (Gemini or DeepSeek) for CV scoring';

    public function handle(): int
    {
        $override = $this->option('provider');
        if (is_string($override) && $override !== '') {
            config(['ai.provider' => $override]);
        }

        $provider = (string) config('ai.provider', 'gemini');

        $this->info('Testing AI Provider...');
        $this->line('  Provider: ' . $provider);

        if ($provider === 'openai') {
            $key = (string) config('openai.api_key');
            $this->line('  OPENAI_API_KEY: ' . (!empty($key) ? '✓ Set (' . substr($key, 0, 10) . '...)' : '✗ Not set'));
            $this->line('  Model: ' . (string) config('openai.model'));
            $this->line('  Base URL: ' . (string) config('openai.base_url'));
        } elseif ($provider === 'deepseek') {
            $key = (string) config('deepseek.api_key');
            $this->line('  DEEPSEEK_API_KEY: ' . (!empty($key) ? '✓ Set (' . substr($key, 0, 10) . '...)' : '✗ Not set'));
            $this->line('  Model: ' . (string) config('deepseek.model'));
            $this->line('  Base URL: ' . (string) config('deepseek.base_url'));
            $this->line('  OpenRouter Referer: ' . ((string) config('deepseek.openrouter_referer') ?: '-'));
            $this->line('  OpenRouter Title: ' . ((string) config('deepseek.openrouter_title') ?: '-'));
        } else {
            $key = (string) config('gemini.api_key');
            $this->line('  GEMINI_API_KEY: ' . (!empty($key) ? '✓ Set (' . substr($key, 0, 10) . '...)' : '✗ Not set'));
            $this->line('  Model: ' . (string) config('gemini.model'));
            $this->line('  API URL: ' . (string) config('gemini.api_url'));
        }

        $this->newLine();
        $this->info('Sending request with sample CV text...');

        $sampleCVText = "John Doe\nSoftware Engineer\n5 years of experience in PHP and Laravel\nBachelor's degree in Computer Science\nSkills: PHP, Laravel, JavaScript, MySQL\nCertifications: Laravel Certified Developer";

        $score = (new CVScoringService())->analyzeCV($sampleCVText);

        if ($score === null) {
            $this->newLine();
            $this->error('❌ Failed to get a score');
            $this->warn('Check logs: storage/logs/laravel.log');
            return 1;
        }

        $this->newLine();
        $this->info('✅ Success!');
        $this->line('CV Score: ' . $score . '/10');

        return 0;
    }
}

