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
            $contentieux = new Contentieux();
            $contentieux->setDatecont(new DateTime());
            $contentieux->setTypecont(TypeCont::Retard);
            $contentieux->setNbdoc(count($commande->getDocuments()));
            $contentieux->setCommande($commande);

            $this->entityManager->persist($contentieux);
            $this->entityManager->flush();
            
            $utilisateur = $commande->getUtilisateur();
            $utilisateur->addContentieux($contentieux);
            $this->entityManager->flush();
        }
    }
}
