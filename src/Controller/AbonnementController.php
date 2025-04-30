<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Possede;
use App\Entity\Notification;
use App\Entity\DemandeAbonnement;
use App\Form\AbonnementType;
use App\Enum\StatutDemande;
use App\Repository\AbonnementRepository;
use App\Repository\DemandeAbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/abonnements')]
final class AbonnementController extends AbstractController
{
    #[Route(name: 'app_admin_abonnement_index', methods: ['GET'])]
    public function index(AbonnementRepository $abonnementRepository, DemandeAbonnementRepository $demandeRepository): Response
    {
        return $this->render('admin/abonnement/index.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
            'demandes' => $demandeRepository->findBy([], ['dateSoumission' => 'DESC']),
        ]);
    }

    #[Route('/nouveau', name: 'app_admin_abonnement_new', methods: ['GET', 'POST'])]
    public function nouveau(Request $request, EntityManagerInterface $entityManager): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($abonnement);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_admin_abonnement_edit', methods: ['GET', 'POST'])]
    public function modifier(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_abonnement_delete', methods: ['POST'])]
    public function supprimer(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/valider', name: 'app_admin_abonnement_valider')]
    public function valider(DemandeAbonnement $demande, EntityManagerInterface $em): Response
    {
        $demande->setStatut(StatutDemande::Approuvee);
        
        $utilisateur = $demande->getUtilisateur();
        $utilisateur->setRoles(['ROLE_ADHERENT']);

        $possede = new Possede();
        $possede->setUtilisateur($demande->getUtilisateur());
        $possede->setAbonnement($demande->getAbonnement());
        $possede->setDateDebut(new \DateTime());

        $em->persist($possede);
        $em->persist($utilisateur);
        $em->flush();

        $notification = new Notification();
        $notification->setObjetnotif('Votre demande d\'abonnement a été approuvée.');
        $notification->setContenunotif('Bonjour, Votre demande d\'abonnement a été approuvée.');
        $notification->addUtilisateur($utilisateur);
        $em->persist($notification);
        $em->flush();

        $this->addFlash('success', 'Demande approuvée, rôle mis à jour.');
        return $this->redirectToRoute('app_admin_abonnement_index');
    }

    #[Route('/{id}/refuser', name: 'app_admin_abonnement_refuser')]
    public function refuser(DemandeAbonnement $demande, EntityManagerInterface $em): Response
    {
        $demande->setStatut(StatutDemande::Refusee);

        $notification = new Notification();
        $notification->setObjetnotif('Votre demande d\'abonnement a été refusée.');
        $notification->setContenunotif('Bonjour, Votre demande d\'abonnement a été refusée.');
        $notification->addUtilisateur($demande->getUtilisateur());
        $em->persist($notification);

        $em->flush();

        $this->addFlash('info', 'Demande refusée.');
        return $this->redirectToRoute('app_admin_abonnement_index');
    }
}
