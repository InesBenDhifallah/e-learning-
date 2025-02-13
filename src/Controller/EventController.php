<?php
namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class EventController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/events', name: 'event_index')]
    public function index(): Response
    {
        $events = $this->entityManager->getRepository(Event::class)->findAll();
        
        return $this->render('event/event.html.twig', ['events' => $events]);
    }

    #[Route('/event/add', name: 'event_add')]
    public function add(Request $request): Response
    {
        $event = new Event(); 
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image instanceof UploadedFile) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                $image->move(
                    $this->getParameter('event_image_directory'),
                    $newFilename
                );

                $event->setImage($newFilename);
            }

            // time is stored in the database as a string
            $time = $event->getTime();
            if ($time instanceof \DateTimeInterface) {
                $event->setTime($time->format('H:i'));
            }

            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->addFlash('success', 'Event added successfully!');
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/{id}', name: 'event_show')]
    public function show(int $id): Response
    {
        $event = $this->entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }
    #[Route('/event/{id}/edit', name: 'event_edit')]
    public function edit(Request $request, Event $event): Response {
    $form = $this->createForm(EventType::class, $event);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->flush();
        $this->addFlash('success', 'Event updated successfully!');
        return $this->redirectToRoute('event_index');
    }

    return $this->render('event/add.html.twig', [
        'form' => $form->createView(),
        'editMode' => true,
    ]);
}

#[Route('/event/{id}/delete', name: 'event_delete', methods: ['POST'])]
public function delete(Request $request, Event $event): Response {
    if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
        $this->entityManager->remove($event);
        $this->entityManager->flush();
        $this->addFlash('success', 'Event deleted successfully!');
    }

    return $this->redirectToRoute('event_index');
}

}
