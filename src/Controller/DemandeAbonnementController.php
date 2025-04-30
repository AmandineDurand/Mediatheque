<?php

namespace App\Controller;

use App\Entity\DemandeAbonnement;
use App\Entity\Fichier;
use App\Form\DemandeAbonnementType;
use App\Repository\DemandeAbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/demande-abonnement')]
final class DemandeAbonnementController extends AbstractController
{
    #[Route('/nouvelle', name: 'app_demande_abonnement_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_INSCRIT')]
    public function new(Request $request, EntityManagerInterface $entityManager, DemandeAbonnementRepository $repository): Response
    {
        //Note : on vérifie si l'utilisateur a déjà une demande en attente
        $demandeExistante = $repository->findOneBy([
            'utilisateur' => $this->getUser(),
            'statut' => 'en_attente'
        ]);

        if ($demandeExistante) {
            $this->addFlash('warning', 'Vous avez déjà une demande d\'abonnement en attente.');
            return $this->redirectToRoute('app_profile');
        }

        $demande = new DemandeAbonnement();
        $demande->setUtilisateur($this->getUser());
        $demande->setDateSoumission(new \DateTime());

        $form = $this->createForm(DemandeAbonnementType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande d\'abonnement a été soumise avec succès. Elle sera traitée dans les plus brefs délais.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/demandeAbo.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}