<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;


class SafeUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Basic validation
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $fail("This :attribute is not a valid URL");
        }

        // Check against Google Safe Browsing API (you will need to setup an API Key)
        $response = Http::get('https://safebrowsing.googleapis.com/v4/threatMatches:find', [
            'key' => config('services.google_safe_browsing.key'),
            'client' => [
                'clientId' => 'Dotline Test',
                'clientVersion' => '1.0.0'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes' => ['ANY_PLATFORM'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $value]
                ]
            ]
        ]);

        if ($response->json('matches')) {
            $fail('This :attribute is not safe');
        }
    }

    public function message()
    {
        return 'This :attribute is not safe or valid';
    }
}
