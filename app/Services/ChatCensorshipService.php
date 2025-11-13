<?php

namespace App\Services;

use App\Models\ChatbotSetting;
use App\Models\ChatCensoredWord;

class ChatCensorshipService
{
    /**
     * Built-in words and phrases that should be censored.
     * The list is intentionally not exhaustive but covers common profanity/NSFW words.
     */
    private const DEFAULT_WORDS = [
        'fuck',
        'shit',
        'asshole',
        'motherfucker',
        'bitch',
        'son of a bitch',
        'dick',
        'pussy',
        'bastard',
        'slut',
        'whore',
        'cunt',
        'bullshit',
        'cock',
        'jerkoff',
        'cum',
        'nipple',
        'porn',
        'xxx',
        'naked',
    ];

    private static ?bool $isEnabled = null;
    private static ?array $cachedWords = null;

    /**
     * Determine if censorship is enabled.
     */
    public static function isEnabled(): bool
    {
        if (self::$isEnabled === null) {
            $setting = ChatbotSetting::first();
            self::$isEnabled = $setting ? (bool) $setting->censorship_enabled : false;
        }

        return self::$isEnabled;
    }

    /**
     * Get the combined list of default and custom words.
     */
    public static function getWords(): array
    {
        if (self::$cachedWords === null) {
            $customWords = ChatCensoredWord::query()
                ->pluck('word')
                ->map(function ($word) {
                    return trim(mb_strtolower($word));
                })
                ->filter()
                ->unique()
                ->values()
                ->all();

            self::$cachedWords = array_unique(array_merge(
                array_map(fn ($word) => trim(mb_strtolower($word)), self::DEFAULT_WORDS),
                $customWords
            ));
        }

        return self::$cachedWords;
    }

    /**
     * Reset cached values (useful after updates).
     */
    public static function resetCache(): void
    {
        self::$cachedWords = null;
        self::$isEnabled = null;
    }

    /**
     * Mask a single word while keeping the first and last character visible.
     */
    public static function maskWord(string $word): string
    {
        $wordLength = mb_strlen($word);

        if ($wordLength <= 1) {
            return str_repeat('*', $wordLength);
        }

        if ($wordLength === 2) {
            return mb_substr($word, 0, 1) . '*';
        }

        return mb_substr($word, 0, 1)
            . str_repeat('*', max($wordLength - 2, 0))
            . mb_substr($word, -1, 1);
    }

    /**
     * Mask a full phrase, preserving whitespace and punctuation.
     */
    private static function maskPhrase(string $phrase): string
    {
        $parts = preg_split('/(\s+)/u', $phrase, -1, PREG_SPLIT_DELIM_CAPTURE);

        return collect($parts)->map(function ($part) {
            if (trim($part) === '') {
                return $part;
            }

            // Preserve trailing punctuation by masking the alphanumeric prefix only
            preg_match('/^([\p{L}\p{N}]+)(.*)$/u', $part, $matches);

            if (count($matches) === 3) {
                [$full, $word, $punctuation] = $matches;
                return self::maskWord($word) . $punctuation;
            }

            return self::maskWord($part);
        })->implode('');
    }

    /**
     * Apply censorship to the provided text if enabled.
     */
    public static function censorText(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return $text;
        }

        if (!self::isEnabled()) {
            return $text;
        }

        $words = self::getWords();

        if (empty($words)) {
            return $text;
        }

        $sanitized = $text;

        foreach ($words as $word) {
            if ($word === '') {
                continue;
            }

            $escaped = preg_quote($word, '/');
            // Allow spaces and handle multiple whitespace characters
            $pattern = str_replace('\ ', '\s+', $escaped);

            $regex = '/(' . $pattern . ')/iu';
            $sanitized = preg_replace_callback($regex, function (array $matches) {
                return self::maskPhrase($matches[0]);
            }, $sanitized);
        }

        return $sanitized;
    }

    /**
     * Helper to compute the masked representation regardless of current toggle value.
     */
    public static function maskedPreview(string $word): string
    {
        return self::maskPhrase($word);
    }

    /**
     * Determine if a word is already part of the default list.
     */
    public static function isDefaultWord(string $word): bool
    {
        $normalized = trim(mb_strtolower($word));
        return in_array($normalized, array_map(fn ($w) => trim(mb_strtolower($w)), self::DEFAULT_WORDS), true);
    }
}

