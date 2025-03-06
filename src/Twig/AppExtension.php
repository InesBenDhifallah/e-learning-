<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('file_exists', [$this, 'fileExists']),
        ];
    }

    public function fileExists($path)
    {
        return file_exists($path);
    }
} 