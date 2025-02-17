<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/event')]
final class EventController extends AbstractController
{
    #[Route(name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Validate localisation for "presentiel" events
            if ($event->getType() === "presentiel" && !$event->getLocalisation()) {
                $this->addFlash('error', 'Localisation is required for presentiel events.');
                return $this->render('event/new.html.twig', [
                    'form' => $form->createView(),
                    'event' => $event
                ]);
            }

            // Handle image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $uploadDir = $this->getParameter('events_images_directory');
                
                // Ensure the directory exists
                $filesystem = new Filesystem();
                if (!$filesystem->exists($uploadDir)) {
                    $filesystem->mkdir($uploadDir, 0777);
                }

                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move($uploadDir, $newFilename);
                    $event->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image upload failed.');
                }
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Event created successfully.');
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Validate localisation for "presentiel" events
            if ($event->getType() === "presentiel" && !$event->getLocalisation()) {
                $this->addFlash('error', 'Localisation is required for presentiel events.');
                return $this->render('event/edit.html.twig', [
                    'form' => $form->createView(),
                    'event' => $event
                ]);
            }

            // Handle image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $uploadDir = $this->getParameter('events_images_directory');
                
                // Ensure the directory exists
                $filesystem = new Filesystem();
                if (!$filesystem->exists($uploadDir)) {
                    $filesystem->mkdir($uploadDir, 0777);
                }

                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move($uploadDir, $newFilename);
                    $event->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image upload failed.');
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Event updated successfully.');
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();

            $this->addFlash('success', 'Event deleted successfully.');
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
