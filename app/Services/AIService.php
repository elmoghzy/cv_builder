<?php

namespace App\Services;

use Gemini;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Enhances a given text section using AI.
     *
     * @param string $text
     * @return string
     */
    public function enhanceSection(string $text): string
    {
        if (empty($text)) {
            return "";
        }

        try {
            $client = Gemini::client(config('gemini.api_key'));

            $prompt = "As an expert resume writer, please rewrite and enhance the following text to make it more professional, impactful, and suitable for a high-quality CV. Focus on using action verbs and quantifying achievements where possible. Return only the enhanced text, without any introductory phrases. Here is the text: \"{$text}\"";

            $result = $client->geminiPro()->generateContent($prompt);

            return $result->text();
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            // Return a generic error or the original text
            return 'Error: Could not connect to the AI service.';
        }
    }

    /**
     * Analyzes the entire CV data for ATS compliance and overall quality.
     *
     * @param array $cvData
     * @return string
     */
    public function analyzeCvForAts(array $cvData): string
    {
        if (empty($cvData)) {
            return "";
        }
        
        try {
            $client = Gemini::client(config('gemini.api_key'));
            $cvJson = json_encode($cvData, JSON_PRETTY_PRINT);
            $prompt = "You are an expert HR professional and resume reviewer with deep knowledge of Applicant Tracking Systems (ATS).
Please analyze the following CV data (in JSON format) and provide a concise report in markdown format with:
1.  An overall ATS compatibility score (out of 100).
2.  A list of 3-5 key strengths of the CV.
3.  A list of the top 3-5 actionable recommendations for improvement to make it more ATS-friendly and appealing to recruiters.
Present the report in a clear, easy-to-read format.

CV Data:
{$cvJson}";

            $result = $client->geminiPro()->generateContent($prompt);

            return $result->text();
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return 'Error: Could not connect to the AI service.';
        }
    }
}
