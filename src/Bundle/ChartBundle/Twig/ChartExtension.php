<?php

namespace App\Bundle\ChartBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ChartExtension extends AbstractExtension
{
    private array $options;
    private array $colors;

    public function __construct(
        array $options = ['responsive' => true, 'maintainAspectRatio' => false],
        array $colors = [
            '#3B82F6',
            '#10B981',
            '#F59E0B',
            '#EF4444',
            '#8B5CF6',
            '#EC4899',
            '#6366F1'
        ]
    ) {
        $this->options = $options;
        $this->colors = $colors;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('chart_options', [$this, 'getChartOptions']),
            new TwigFunction('chart_colors', [$this, 'getChartColors']),
        ];
    }

    public function getChartOptions(): array
    {
        return array_merge([
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom'
                ]
            ]
        ], $this->options);
    }

    public function getChartColors(): array
    {
        return $this->colors;
    }
} 