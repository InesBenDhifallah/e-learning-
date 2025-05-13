<?php

namespace App\Service;

class BadWordsFilter
{
    private array $badWords = [
        'merde', 'putain', 'connard', 'salope', 'pute', 'enculé',
        'fuck', 'shit', 'bitch', 'asshole', 'bastard',
        'connasse',
        'bite',
        'couille',
        // Ajoutez d'autres mots selon vos besoins
    ];

    public function hasBadWords(string $text): bool
    {
        $text = mb_strtolower($text);
        foreach ($this->badWords as $word) {
            if (str_contains($text, $word)) {
                return true;
            }
        }
        return false;
    }

    public function filter(string $text): string
    {
        $text = mb_strtolower($text);
        foreach ($this->badWords as $word) {
            $replacement = str_repeat('*', mb_strlen($word));
            $text = str_ireplace($word, $replacement, $text);
        }
        return $text;
    }

    public function getBadWordsFound(string $text): array
    {
        $found = [];
        $text = mb_strtolower($text);
        foreach ($this->badWords as $word) {
            if (str_contains($text, $word)) {
                $found[] = $word;
            }
        }
        return $found;
    }
} 