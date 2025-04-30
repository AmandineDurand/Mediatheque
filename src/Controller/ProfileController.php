<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Entity\Possede;
use App\Enum\TypeAbo;
use App\Repository\NotificationRepository;
use App\Repository\DemandeAbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mon-profil')]
final class ProfileController extends AbstractController
{
    #[Route(name: 'app_profile')]
    #[IsGranted('ROLE_INSCRIT')]
    public function index(EntityManagerInterface $entityManager, DemandeAbonnementRepository $demandeAbonnementRepository): Response
    {
        $user = $this->getUser();
        $abonnementInfo = null;
        $warningMessage = null;

        $derniereDemande = $demandeAbonnementRepository->findOneBy([
            'utilisateur' => $this->getUser(),
        ], ['dateSoumission' => 'DESC']);        
        
        if (in_array('ROLE_ADHERENT', $user->getRoles())) {
            $possedeRepository = $entityManager->getRepository(Possede::class);
            $possede = $possedeRepository->findOneBy(['utilisateur' => $user]);
            
            if ($possede) {
                $type = $possede->getAbonnement()->getTypeabo();
                $dateDebut = $possede->getDateDebut();

                $dateFin = clone $dateDebut;

                if ($type instanceof TypeAbo) {
                    $typeValue = $type->value;
                } else {
                    $typeValue = $type;
                }
                
                if ($typeValue === 'annuel') {
                    $dateFin->modify('+1 year');
                } elseif ($typeValue === 'mensuel') {
                    $dateFin->modify('+1 month');
                }

                $dateFin->setTime(23, 59, 59);
                
                $currentDate = new \DateTime();
                $remainingDays = $currentDate->diff($dateFin)->days;

                if ($remainingDays <= 5) {
                    $warningMessage = "Attention, votre abonnement expire dans $remainingDays jours.";
                }

                $prix = $possede->getAbonnement()->getPrixabo();
                $prixAffiche = $prix === 0 ? 'Gratuit' : $prix;

                $abonnementInfo = [
                    'type' => $type,
                    'debut' => $dateDebut,
                    'fin' => $dateFin,
                    'prix' => $prixAffiche
                ];
            }
        }
        
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'abonnement' => $abonnementInfo,
            'warningMessage' => $warningMessage,
            'derniereDemande' => $derniereDemande,
        ]);
    }

    #[Route('/modifier', name: 'app_profile_edit')]
    public function modifier(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/notifications', name: 'app_utilisateur_notifications')]
    public function notifications(NotificationRepository $notificationRepository): Response
    {
        $user = $this->getUser();

        $notifications = $notificationRepository->createQueryBuilder('n')
            ->join('n.utilisateurs', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('n.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('profile/notifications.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}
