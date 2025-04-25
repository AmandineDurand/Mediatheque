<?php

namespace App\Tests\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UtilisateurControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/utilisateur/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Utilisateur::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'utilisateur[nomUtil]' => 'Testing',
            'utilisateur[prenomUtil]' => 'Testing',
            'utilisateur[emailUtil]' => 'Testing',
            'utilisateur[roles]' => 'Testing',
            'utilisateur[nbContentieux]' => 'Testing',
            'utilisateur[motDePasse]' => 'Testing',
            'utilisateur[documentsAimes]' => 'Testing',
            'utilisateur[notifications]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateur();
        $fixture->setNomUtil('My Title');
        $fixture->setPrenomUtil('My Title');
        $fixture->setEmailUtil('My Title');
        $fixture->setRoles('My Title');
        $fixture->setNbContentieux('My Title');
        $fixture->setMotDePasse('My Title');
        $fixture->setDocumentsAimes('My Title');
        $fixture->setNotifications('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateur();
        $fixture->setNomUtil('Value');
        $fixture->setPrenomUtil('Value');
        $fixture->setEmailUtil('Value');
        $fixture->setRoles('Value');
        $fixture->setNbContentieux('Value');
        $fixture->setMotDePasse('Value');
        $fixture->setDocumentsAimes('Value');
        $fixture->setNotifications('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'utilisateur[nomUtil]' => 'Something New',
            'utilisateur[prenomUtil]' => 'Something New',
            'utilisateur[emailUtil]' => 'Something New',
            'utilisateur[roles]' => 'Something New',
            'utilisateur[nbContentieux]' => 'Something New',
            'utilisateur[motDePasse]' => 'Something New',
            'utilisateur[documentsAimes]' => 'Something New',
            'utilisateur[notifications]' => 'Something New',
        ]);

        self::assertResponseRedirects('/utilisateur/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomUtil());
        self::assertSame('Something New', $fixture[0]->getPrenomUtil());
        self::assertSame('Something New', $fixture[0]->getEmailUtil());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getNbContentieux());
        self::assertSame('Something New', $fixture[0]->getMotDePasse());
        self::assertSame('Something New', $fixture[0]->getDocumentsAimes());
        self::assertSame('Something New', $fixture[0]->getNotifications());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateur();
        $fixture->setNomUtil('Value');
        $fixture->setPrenomUtil('Value');
        $fixture->setEmailUtil('Value');
        $fixture->setRoles('Value');
        $fixture->setNbContentieux('Value');
        $fixture->setMotDePasse('Value');
        $fixture->setDocumentsAimes('Value');
        $fixture->setNotifications('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/utilisateur/');
        self::assertSame(0, $this->repository->count([]));
    }
}
