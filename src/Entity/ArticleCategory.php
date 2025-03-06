<?php

namespace App\Entity;

class ArticleCategory
{
    public const CATEGORIES = [
        'mathematiques' => 'Mathématiques',
        'informatique' => 'Informatique',
        'francais' => 'Français',
        'anglais' => 'Anglais',
        'arabe' => 'Arabe',
        'sciences' => 'Sciences',
        'sport' => 'Sport',
        'arts' => 'Arts',
        'musique' => 'Musique',
        'chimie' => 'Chimie',
        'physique' => 'Physique'
    ];

    public static function getCategories(): array
    {
        return self::CATEGORIES;
    }

    public static function getCategoryLabel(string $category): ?string
    {
        return self::CATEGORIES[$category] ?? null;
    }

    public static function isValidCategory(string $category): bool
    {
        return isset(self::CATEGORIES[$category]);
    }
} 