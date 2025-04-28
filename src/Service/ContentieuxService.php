<?php

namespace App\Service;
use App\Enum\TypeCont;
use App\Entity\Contentieux;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class ContentieuxService
{
    private EntityManagerInterface $entityManager;
    private CommandeRepository $commandeRepository;

    public function __construct(EntityManagerInterface $entityManager, CommandeRepository $commandeRepository)
    {
        $this->entityManager = $entityManager;
        $this->commandeRepository = $commandeRepository;
    }

    public function createContentieuxForExpiredOrders(): void
    {
        $commandes = $this->commandeRepository->findExpiredOrders();

        foreach ($commandes as $commande) {
            // Créer un contentieux
            $contentieux = new Contentieux();
            $contentieux->setDatecont(new DateTime());
            $contentieux->setTypecont(TypeCont::Retard);
            $contentieux->setNbdoc(count($commande->getDocuments())); // Nombre de documents dans la commande
            $contentieux->setCommande($commande);

            $this->entityManager->persist($contentieux);
            $this->entityManager->flush();
            
            // Ajouter le contentieux à l'utilisateur (à la commande ou autre)
            $utilisateur = $commande->getUtilisateur();
            $utilisateur->addContentieux($contentieux); // Ajoute le contentieux à l'utilisateur (ajoute une méthode d'addition dans l'entité Utilisateur)
            $this->entityManager->flush();
        }
    }
}
