<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Form\ParticipationType;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipationController extends AbstractController
{
    #[Route('/participation/{id}', name: 'app_participation', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        // Fetch the event based on the ID
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found.');
        }

        // Create a new Participation entity
        $participation = new Participation();
        $participation->setEvent($event);

        // Create the form
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->redirectToRoute('event_list'); // Change this to your event list route
        }

        return $this->render('participation/register.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }
}