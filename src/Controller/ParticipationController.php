<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/participation')]
class ParticipationController extends AbstractController
{
    #[Route('/join/{id}', name: 'app_participation_join', methods: ['POST'])]
    public function join(Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to participate.');
            return $this->redirectToRoute('app_event_index');
        }

        // Check if user is already participating
        $existingParticipation = $entityManager->getRepository(Participation::class)->findOneBy([
            'event' => $event,
            'user' => $user
        ]);

        if ($existingParticipation) {
            $this->addFlash('warning', 'You are already participating in this event.');
            return $this->redirectToRoute('app_participation_list', ['id' => $event->getId()]);
        }

        // Handle participation logic
        if ($event->getPrix() > 0) {
            $this->addFlash('info', 'This event requires payment. Please proceed to checkout.');
            return $this->redirectToRoute('app_payment', ['eventId' => $event->getId()]);
        }

        // Create a new participation
        $participation = new Participation();
        $participation->setEvent($event);
        $participation->setUser($user);
        $participation->setCreatedAt(new \DateTime());

        $entityManager->persist($participation);
        $entityManager->flush();

        $this->addFlash('success', 'You have successfully joined the event!');

        // ✅ Redirect to the list of participants after joining
        return $this->redirectToRoute('app_participation_list', ['id' => $event->getId()]);
    }

    #[Route('/cancel/{id}', name: 'app_participation_cancel', methods: ['POST'])]
    public function cancel(Participation $participation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user || $participation->getUser() !== $user) {
            $this->addFlash('error', 'Unauthorized action.');
            return $this->redirectToRoute('app_event_index');
        }

        $entityManager->remove($participation);
        $entityManager->flush();

        $this->addFlash('success', 'You have successfully canceled your participation.');

        // ✅ Redirect to the list of participants after canceling
        return $this->redirectToRoute('app_participation_list', ['id' => $participation->getEvent()->getId()]);
    }

    #[Route('/list/{id}', name: 'app_participation_list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(Event $event): Response
    {
        return $this->render('participation/list.html.twig', [
            'event' => $event,
            'participants' => $event->getParticipations(),
        ]);
    }
}
