<?php

namespace App\Controller;

use App\Service\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TranslationController extends AbstractController
{
    private $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    #[Route('/api/translate', name: 'api_translate', methods: ['POST'])]
    public function translate(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data || !isset($data['text'])) {
                throw new \Exception('Données invalides');
            }

            $text = trim($data['text']);
            if (empty($text)) {
                throw new \Exception('Le texte à traduire est vide');
            }

            // Traduire en anglais
            $english = $this->translationService->translate($text, 'en');
            
            // Traduire en arabe
            $arabic = $this->translationService->translate($text, 'ar');

            return $this->json([
                'english' => $english,
                'arabic' => $arabic
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }
} 