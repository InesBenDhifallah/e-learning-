<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/module')]
final class ModuleController extends AbstractController
{
    #[Route(name: 'app_module_index', methods: ['GET'])]
public function index(ModuleRepository $moduleRepository): Response
{
    $user = $this->getUser(); // Récupère l'utilisateur connecté

    if ($this->isGranted('ROLE_TEACHER')) {
        // Si l'utilisateur est un professeur, il ne voit que ses modules
        $modules = $moduleRepository->findModulesByTeacher($user);
    } else {
        // Sinon, il voit tous les modules (admin, autres rôles)
        $modules = $moduleRepository->findAll();
    }

    return $this->render('module/index.html.twig', [
        'modules' => $modules,
    ]);
}


    #[Route('/new', name: 'app_module_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Les professeurs ne peuvent pas créer de modules.');
        }

        $module = new Module();
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('app_module_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('module/new.html.twig', [
            'module' => $module,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_module_show', methods: ['GET'])]
    public function show(Module $module): Response
    {
        return $this->render('module/show.html.twig', [
            'module' => $module,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_module_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Module $module, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Les professeurs ne peuvent pas modifier les modules.');
        }

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_module_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('module/edit.html.twig', [
            'module' => $module,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_module_delete', methods: ['POST'])]
    public function delete(Request $request, Module $module, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Les professeurs ne peuvent pas supprimer les modules.');
        }

        if ($this->isCsrfTokenValid('delete'.$module->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($module);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_module_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
