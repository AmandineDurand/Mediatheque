<?php

namespace App\Tests\Controller;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DocumentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/document/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Document::class);

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
        self::assertPageTitleContains('Document index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'document[titreDoc]' => 'Testing',
            'document[anneeSortie]' => 'Testing',
            'document[resumeDoc]' => 'Testing',
            'document[stockDoc]' => 'Testing',
            'document[utilisateursAimant]' => 'Testing',
            'document[commandes]' => 'Testing',
            'document[auteur]' => 'Testing',
            'document[categories]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Document();
        $fixture->setTitreDoc('My Title');
        $fixture->setAnneeSortie('My Title');
        $fixture->setResumeDoc('My Title');
        $fixture->setStockDoc('My Title');
        $fixture->setUtilisateursAimant('My Title');
        $fixture->setCommandes('My Title');
        $fixture->setAuteur('My Title');
        $fixture->setCategories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Document');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Document();
        $fixture->setTitreDoc('Value');
        $fixture->setAnneeSortie('Value');
        $fixture->setResumeDoc('Value');
        $fixture->setStockDoc('Value');
        $fixture->setUtilisateursAimant('Value');
        $fixture->setCommandes('Value');
        $fixture->setAuteur('Value');
        $fixture->setCategories('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'document[titreDoc]' => 'Something New',
            'document[anneeSortie]' => 'Something New',
            'document[resumeDoc]' => 'Something New',
            'document[stockDoc]' => 'Something New',
            'document[utilisateursAimant]' => 'Something New',
            'document[commandes]' => 'Something New',
            'document[auteur]' => 'Something New',
            'document[categories]' => 'Something New',
        ]);

        self::assertResponseRedirects('/document/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitreDoc());
        self::assertSame('Something New', $fixture[0]->getAnneeSortie());
        self::assertSame('Something New', $fixture[0]->getResumeDoc());
        self::assertSame('Something New', $fixture[0]->getStockDoc());
        self::assertSame('Something New', $fixture[0]->getUtilisateursAimant());
        self::assertSame('Something New', $fixture[0]->getCommandes());
        self::assertSame('Something New', $fixture[0]->getAuteur());
        self::assertSame('Something New', $fixture[0]->getCategories());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Document();
        $fixture->setTitreDoc('Value');
        $fixture->setAnneeSortie('Value');
        $fixture->setResumeDoc('Value');
        $fixture->setStockDoc('Value');
        $fixture->setUtilisateursAimant('Value');
        $fixture->setCommandes('Value');
        $fixture->setAuteur('Value');
        $fixture->setCategories('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/document/');
        self::assertSame(0, $this->repository->count([]));
    }
}
