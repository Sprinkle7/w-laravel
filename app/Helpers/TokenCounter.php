<?php

namespace App\Helpers;

class TokenCounter
{
    /**
     * Approximate token count for a given string based on OpenAI's rules.
     *
     * @param string $text
     * @return int
     */
    public static function countTokens(string $text): int
    {
        // Normalize text (handle whitespace and special cases)
        $normalizedText = preg_replace('/\s+/', ' ', trim($text)); // Replace multiple spaces with a single space
        $normalizedText = preg_replace('/[\r\n]+/', ' ', $normalizedText); // Remove newlines

        // Split the text into words and subwords
        $tokens = self::tokenize($normalizedText);

        return count($tokens);
    }

    /**
     * Tokenize the input text into approximate tokens.
     *
     * @param string $text
     * @return array
     */
    private static function tokenize(string $text): array
    {
        // Split text into words and special characters
        $words = preg_split('/(\s+|[^a-zA-Z0-9])/u', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $tokens = [];
        foreach ($words as $word) {
            // Handle subword splitting for large words
            $tokens = array_merge($tokens, self::splitIntoSubwords($word));
        }

        return $tokens;
    }

    /**
     * Split long words into subwords (approximate BPE logic).
     *
     * @param string $word
     * @return array
     */
    private static function splitIntoSubwords(string $word): array
    {
        $subwords = [];

        // Example logic: split every 4 characters (adjust as needed for accuracy)
        $length = 4;
        for ($i = 0; $i < mb_strlen($word); $i += $length) {
            $subwords[] = mb_substr($word, $i, $length);
        }

        return $subwords;
    }
}
