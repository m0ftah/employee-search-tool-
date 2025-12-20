<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidate;
use App\Services\CVTextExtractorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class CandidateRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('candidate.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'education_level' => ['nullable', 'string', 'in:high_school,diploma,bachelor,master,phd'],
            'years_of_experience' => ['nullable', 'integer', 'min:0'],
            'skills' => ['nullable', 'string'],
            'certifications' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // 10MB max
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'candidate',
            'email_verified_at' => now(),
        ]);

        // Handle resume upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        // Process skills (convert comma-separated string to array)
        $skills = [];
        if ($request->filled('skills')) {
            $skills = array_map('trim', explode(',', $request->skills));
            $skills = array_filter($skills); // Remove empty values
        }

        // Analyze CV and get score if resume is uploaded
        $score = null;
        if ($resumePath) {
            try {
                $extractor = new CVTextExtractorService();
                $cvText = $extractor->extractText($resumePath);

                // Call Gemini API to analyze the CV
                $score = $this->analyzeCVWithGemini($cvText);
            } catch (Exception $e) {
                // Log the error but don't fail registration
                Log::error('CV analysis failed: ' . $e->getMessage());
            }
        }

        // Create the candidate profile
        $candidate = Candidate::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'location' => $request->location,
            'resume_path' => $resumePath,
            'education_level' => $request->education_level,
            'years_of_experience' => $request->years_of_experience,
            'skills' => $skills,
            'certifications' => $request->certifications,
            'bio' => $request->bio,
            'score' => $score,
        ]);

        // Assign candidate role if it exists
        try {
            $user->assignRole('candidate');
        } catch (Exception $e) {
            // Role might not exist, that's okay
        }

        // Log the user in
        auth()->login($user);

        return redirect('/admin')
            ->with('success', __('app.registration_successful'));
    }

    /**
     * Analyze CV text using Google Gemini API
     *
     * @param string $cvText Extracted text from CV
     * @return float|null Score from 0-10, or null if analysis fails
     */
    private function analyzeCVWithGemini(string $cvText): ?float
    {
        try {
            $apiKey = 'AIzaSyDdnxwuIVlAJfOd-miYOh5Nwn85DyuiD0U';
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent" ;

            $prompt = "أنت محلل سير ذاتية محترف. قم بتقييم السيرة الذاتية التالية من 10 نقاط بناءً على الإنجازات، الكلمات المفتاحية ذات الصلة، والتنسيق. يجب أن يكون الناتج هو **رقم واحد فقط** في السطر الأول، ولا شيء سواه. لا تكتب أي تفسير أو مقدمة أو تفاصيل.";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $apiKey,
            ])->post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            ['text' => $cvText],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Extract the score from the response
                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $text = trim($responseData['candidates'][0]['content']['parts'][0]['text']);

                    // Extract the first number from the response
                    if (preg_match('/(\d+(?:\.\d+)?)/', $text, $matches)) {
                        $score = (float) $matches[1];

                        // Ensure score is between 0 and 10
                        $score = max(0, min(10, $score));

                        return $score;
                    }
                }
            } else {
                Log::error('Gemini API error: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
        }

        return null;
    }
}

