<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UtilisateurRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route(name: 'app_admin_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('app_admin_user_index');
    }

    #[Route('/utilisateurs', name: 'app_admin_user_index', methods: ['GET'])]
    public function utilisateurs(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();
        
        return $this->render('admin/utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/commandes/{id}/valider-retrait', name: 'admin_commandes_valider_retrait')]
    public function validerRetrait(Commande $commande, EntityManagerInterface $em): Response
    {
        $commande->setDateRetrait(new \DateTime());

        $em->flush();

        return $this->redirectToRoute('admin_commandes');
    }

    #[Route('/commandes/{id}/valider-rendu', name: 'admin_commandes_valider_rendu')]
    public function validerRendu(Commande $commande, EntityManagerInterface $em): Response
    {
        $commande->setDateRendu(new \DateTime());

        // A chaque rendu, on remet 1 au stock de chaque document
        foreach ($commande->getDocuments() as $document) {
            $document->setStockDoc($document->getStockDoc() + 1);
        }

        $em->flush();

        return $this->redirectToRoute('admin_commandes');
    }

    #[Route('/commandes', name: 'admin_commandes')]
    public function commandes(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll();

        return $this->render('commande/admin.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commandes/{id}', name: 'admin_commandes_show', methods: ['GET'])]
    public function showAdmin(Commande $commande): Response
    {
        return $this->render('admin/commandes/show.html.twig', [
            'commande' => $commande,
        ]);
    }
}