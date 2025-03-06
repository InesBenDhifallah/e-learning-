<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TranslationService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function translate(string $text, string $targetLang): string
    {
        try {
            // Utiliser Google Translate API sans clé (méthode alternative)
            $response = $this->httpClient->request(
                'GET',
                'https://translate.googleapis.com/translate_a/single',
                [
                    'query' => [
                        'client' => 'gtx',
                        'sl' => 'fr',
                        'tl' => $this->normalizeLanguageCode($targetLang),
                        'dt' => 't',
                        'q' => $text
                    ]
                ]
            );

            $result = $response->toArray();

            if (!empty($result[0])) {
                $translatedText = '';
                foreach ($result[0] as $part) {
                    if (isset($part[0])) {
                        $translatedText .= $part[0];
                    }
                }
                return $translatedText;
            }

            throw new \Exception('Traduction non disponible');
        } catch (\Exception $e) {
            return "Erreur de traduction. Veuillez réessayer plus tard.";
        }
    }

    private function normalizeLanguageCode(string $lang): string
    {
        $langMap = [
            'en' => 'en',
            'ar' => 'ar',
            'english' => 'en',
            'arabic' => 'ar',
            'fr' => 'fr'
        ];

        return $langMap[strtolower($lang)] ?? 'en';
    }
} 