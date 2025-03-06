<?php

namespace App\Service;

use Exercise\HTMLPurifierBundle\HTMLPurifier;

class ContentFilterService
{
    private $badWordsFilter;
    private $purifier;

    public function __construct(
        BadWordsFilter $badWordsFilter,
        HTMLPurifier $purifier = null
    ) {
        $this->badWordsFilter = $badWordsFilter;
        $this->purifier = $purifier;
    }

    public function sanitizeAndFilter(string $content): array
    {
        $result = [
            'isClean' => true,
            'content' => $content,
            'messages' => []
        ];

        try {
            // Vérifier les bad words
            if ($this->badWordsFilter->hasBadWords($content)) {
                $badWords = $this->badWordsFilter->getBadWordsFound($content);
                $result['isClean'] = false;
                $result['messages'][] = 'Le contenu contient des mots inappropriés';
                return $result;
            }

            // Nettoyer le HTML si HTMLPurifier est disponible
            if ($this->purifier) {
                $content = $this->purifier->purify($content);
            } else {
                $content = strip_tags($content, '<p><br><a>');
            }

            $result['content'] = $content;
            return $result;

        } catch (\Exception $e) {
            $result['isClean'] = false;
            $result['messages'][] = $e->getMessage();
            return $result;
        }
    }
} 