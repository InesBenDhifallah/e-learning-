<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminMediaController extends AbstractController
{
    #[Route('/admin/media/browse', name: 'admin_media_browse')]
    public function browse(Request $request): Response
    {
        // Pour l'instant, retournons une réponse simple
        return new JsonResponse([
            'resourceType' => 'Images',
            'fileName' => '',
            'url' => ''
        ]);
    }

    #[Route('/admin/media/upload', name: 'admin_media_upload', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('upload');
        
        if (!$uploadedFile) {
            return new JsonResponse([
                'uploaded' => 0,
                'error' => ['message' => 'No file uploaded.']
            ]);
        }

        try {
            // Définir le dossier d'upload
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/';
            
            // Créer le dossier s'il n'existe pas
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Générer un nom de fichier unique
            $fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
            
            // Déplacer le fichier
            $uploadedFile->move($uploadDir, $fileName);

            // Retourner la réponse
            return new JsonResponse([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => '/uploads/' . $fileName
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'uploaded' => 0,
                'error' => ['message' => $e->getMessage()]
            ]);
        }
    }
} 