<?php

namespace App\Service;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

class CommandeService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function verifierCommandes(array $commandes): void
    {
        $now = new \DateTime();

        foreach ($commandes as $commande) {
            if ($commande->getDateCom() && !$commande->getDateRetrait()) {
                $dateComPlus7 = (clone $commande->getDateCom())->modify('+7 days');
                if ($now > $dateComPlus7) {
                    $this->annulerCommande($commande);
                }
            }
        }

        $this->em->flush();
    }

    private function annulerCommande(Commande $commande): void
    {
        foreach ($commande->getDocuments() as $document) {
            $document->setStockDoc($document->getStockDoc() + 1);
        }
    }
}
