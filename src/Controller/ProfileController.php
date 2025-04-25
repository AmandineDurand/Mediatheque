<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Entity\Possede;
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
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $abonnementInfo = null;
        $warningMessage = null;
        
        if (in_array('ROLE_ADHERENT', $user->getRoles())) {
            // Récupérer directement l'abonnement via le repository
            $possedeRepository = $entityManager->getRepository(Possede::class);
            $possede = $possedeRepository->findOneBy(['utilisateur' => $user]);
            
            if ($possede) {
                $type = $possede->getAbonnement()->getTypeabo();
                $dateDebut = $possede->getDateDebut();

                $dateFin = (clone $dateDebut);
                if ($type === 'annuel') {
                    $dateFin->modify('+1 year');
                } elseif ($type === 'mensuel') {
                    $dateFin->modify('+1 month');
                }

                $currentDate = new \DateTime();
                $diff = $currentDate->diff($dateFin);
                $remainingDays = $diff->days;

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
        ]);
    }
    // public function index(): Response
    // {
    //     $user = $this->getUser();
    //     // dd($user, get_class($user));

    //     $abonnementInfo = null;

    //     if (in_array('ROLE_ADHERENT', $user->getRoles())) {
    //         $abonnements = $user->getAbonnements();
    //         if (!$abonnements->isEmpty()) {
    //             $possede = $abonnements->first();
            
    //             if ($possede) {
    //                 $dateDebut = $possede->getDateDebut();
    //                 $dateFin = (clone $dateDebut)->modify('+1 year');
    //                 $type = $possede->getAbonnement()->getTypeAbo();

    //                 $abonnementInfo = [
    //                     'type' => $type,
    //                     'debut' => $dateDebut,
    //                     'fin' => $dateFin
    //                 ];
    //             }
    //         }
    //     }

    //     return $this->render('profile/index.html.twig', [
    //         'user' => $user,
    //         'abonnement' => $abonnementInfo,
    //     ]);
    // }

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
    // #[Route(name: 'app_utilisateur_index', methods: ['GET'])]
    // public function index(UtilisateurRepository $utilisateurRepository): Response
    // {
    //     return $this->render('utilisateur/index.html.twig', [
    //         'utilisateurs' => $utilisateurRepository->findAll(),
    //     ]);
    // }
}
