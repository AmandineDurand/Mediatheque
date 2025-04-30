<?php

namespace App\Tests\Unit\Service;

use App\Entity\Commande;
use App\Entity\Document;
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

class CommandeServiceTest extends TestCase
{
    private CommandeService $commandeService;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject */
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->commandeService = new CommandeService($this->entityManager);
    }

    public function testVerifierCommandesNonRetirees(): void
    {
        $document1 = $this->createMock(Document::class);
        $document1->expects($this->once())
            ->method('getStockDoc')
            ->willReturn(2);
        $document1->expects($this->once())
            ->method('setStockDoc')
            ->with(3);

        $document2 = $this->createMock(Document::class);
        $document2->expects($this->once())
            ->method('getStockDoc')
            ->willReturn(1);
        $document2->expects($this->once())
            ->method('setStockDoc')
            ->with(2);

        //On créer une commande non retirée et ancienne (> 7 jours)
        $commandeAncienne = new Commande();
        $dateCom = new DateTime();
        $dateCom->modify('-10 days');
        $commandeAncienne->setDateCom($dateCom);
        
        //Ici, on ajoute les documents à la commande
        $reflectionCommande = new \ReflectionClass(Commande::class);
        $documentsProperty = $reflectionCommande->getProperty('documents');
        $documentsProperty->setAccessible(true);
        $documentsProperty->setValue($commandeAncienne, new ArrayCollection([$document1, $document2]));

        //On créer maintenant une commande récente (< 7 jours)
        $commandeRecente = new Commande();
        $dateComRecente = new DateTime();
        $dateComRecente->modify('-3 days'); // 3 jours dans le passé
        $commandeRecente->setDateCom($dateComRecente);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->commandeService->verifierCommandes([$commandeAncienne, $commandeRecente]);
    }

    public function testVerifierCommandesDejaRetirees(): void
    {
        //Ici, on créer une commande déjà retirée
        $commandeRetiree = new Commande();
        $dateCom = new DateTime();
        $dateCom->modify('-10 days');
        $commandeRetiree->setDateCom($dateCom);
        $commandeRetiree->setDateRetrait(new DateTime('-5 days'));

        //On s'assure que le document ne devrait pas être remis en stock car la commande a été retirée
        $document = $this->createMock(Document::class);
        $document->expects($this->never())
            ->method('setStockDoc');

        //On ajoute ensuite le document à la commande
        $reflectionCommande = new \ReflectionClass(Commande::class);
        $documentsProperty = $reflectionCommande->getProperty('documents');
        $documentsProperty->setAccessible(true);
        $documentsProperty->setValue($commandeRetiree, new ArrayCollection([$document]));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->commandeService->verifierCommandes([$commandeRetiree]);
    }

    public function testVerifierCommandesVides(): void
    {
        //Pour finir, on teste avec un tableau vide de commandes
        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->commandeService->verifierCommandes([]);
    }
}