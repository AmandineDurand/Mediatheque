<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Service\CommandeService;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// #[Route('/commande')]
final class CommandeController extends AbstractController
{
    #[Route('/panier', name: 'voir_panier')]
    public function voirPanier(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $commande = $em->getRepository(Commande::class)->findOneBy([
            'utilisateur' => $user,
            'dateCom' => null,
        ]);

        return $this->render('commande/panier.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/panier/valider', name: 'valider_panier')]
    public function validerPanier(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $commande = $em->getRepository(Commande::class)->findOneBy([
            'utilisateur' => $user,
            'dateCom' => null,
        ]);

        if (!$commande || count($commande->getDocuments()) === 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('voir_panier');
        }

        $commande->setDatecom(new \DateTime()); // Marquer la commande validée

        foreach ($commande->getDocuments() as $document) {
            // Décrémenter le stock de 1
            $stockActuel = $document->getStockdoc();
    
            if ($stockActuel > 0) {
                $document->setStockdoc($stockActuel - 1);
            } else {
                // Si stock = 0, afficher une erreur ou ignorer selon ce que tu veux
                $this->addFlash('danger', 'Le document ' . $document->getTitredoc() . ' est en rupture de stock.');
                // Tu pourrais aussi décider d'annuler toute la validation ici si besoin
            }
        }

        $em->flush();
        $this->addFlash('success', 'Votre commande a été validée !');
        return $this->redirectToRoute('liste_commandes');
    }

    #[Route('/mes-commandes', name: 'liste_commandes')]
    public function listeCommandes(CommandeService $commandeService, CommandeRepository $commandeRepository): Response
    {
        $user = $this->getUser();

        $commandes =  $commandeRepository->findBy([
            'utilisateur' => $user,
        ]);

        $commandeService->verifierCommandes($commandes);

        return $this->render('commande/liste.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/mes-commandes/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $com): Response
    {   
        $user = $this->getUser();

        if ($com->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException('Accès interdit à cette commande.');
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $com,
        ]);
    }
}
