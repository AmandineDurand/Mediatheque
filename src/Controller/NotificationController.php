<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Form\NotificationType;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/notifications')]
final class NotificationController extends AbstractController
{
    #[Route(name: 'app_admin_notification_index', methods: ['GET'])]
    public function index(NotificationRepository $notificationRepository): Response
    {
        return $this->render('admin/notification/index.html.twig', [
            'notifications' => $notificationRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'app_admin_notification_new', methods: ['GET', 'POST'])]
    public function nouveau(Request $request, EntityManagerInterface $entityManager): Response
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notification);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_notification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/notification/new.html.twig', [
            'notification' => $notification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_admin_notification_edit', methods: ['GET', 'POST'])]
    public function modifier(Request $request, Notification $notification, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_notification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/notification/edit.html.twig', [
            'notification' => $notification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_notification_delete', methods: ['POST'])]
    public function delete(Request $request, Notification $notification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notification->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($notification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_notification_index', [], Response::HTTP_SEE_OTHER);
    }
}