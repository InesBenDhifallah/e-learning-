<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TranslateController extends AbstractController
{
    private HttpClientInterface $httpClient;
    private const SUPPORTED_LANGUAGES = ['en', 'fr', 'es', 'de', 'ar', 'zh'];

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    // Route pour afficher la page 'About Us'
    #[Route('/about_us', name: 'about_us')]
    public function about_us()
    {
        return $this->render('translate.html.twig');
    }

    // Route pour effectuer la traduction
    #[Route('/translate/{target_language}', methods: ['GET', 'POST'])]
    public function translate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $texts = $data['texts'] ?? [];
        $targetLanguage = $data['target_language'] ?? 'fr';

        if (empty($texts)) {
            return new JsonResponse(['error' => 'No texts provided'], 400);
        }

        if (!in_array($targetLanguage, self::SUPPORTED_LANGUAGES)) {
            return new JsonResponse(['error' => "Unsupported target language: $targetLanguage"], 400);
        }

        $translatedTexts = [];
        foreach ($texts as $text) {
            if (!empty($text)) {
                $translatedTexts[] = $this->translateText($text, $targetLanguage);
            } else {
                $translatedTexts[] = '';
            }
        }

        return new JsonResponse(['translated_texts' => $translatedTexts]);
    }

    // Fonction pour envoyer la requête de traduction à l'API
    private function translateText(string $text, string $targetLanguage): string
    {
        $response = $this->httpClient->request('GET', 'https://translate.googleapis.com/translate_a/single', [
            'query' => [
                'client' => 'gtx',
                'sl' => 'auto',
                'tl' => $targetLanguage,
                'dt' => 't',
                'q' => $text
            ]
        ]);

        $content = $response->toArray();
        return $content[0][0][0] ?? '';
    }

    // Route pour changer la langue de l'application
    #[Route('/language/{_locale}', name: 'change_language', requirements: ['_locale' => 'en|fr|ar'])]
    public function changeLanguage(string $_locale, Request $request): RedirectResponse
    {
        // Change la locale
        $request->getSession()->set('_locale', $_locale);

        // Redirige vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
