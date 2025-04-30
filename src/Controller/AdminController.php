<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UtilisateurRepository;
use App\Repository\CommandeRepository;
use App\Repository\AvisRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use App\Entity\Contentieux;
use App\Form\ContentieuxType;
use App\Enum\TypeCont;
use App\Entity\Avis;
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

        //Note : à chaque rendu, on remet 1 au stock de chaque document
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
    public function showAdmin(Commande $commande, EntityManagerInterface $em): Response
    {
        $contentieuxList = $em->getRepository(Contentieux::class)
                                    ->findBy(['commande' => $commande]);
                                    
        return $this->render('admin/commandes/show.html.twig', [
            'commande' => $commande,
            'contentieuxList' => $contentieuxList,
        ]);
    }

    #[Route('/commandes/{id}/contentieux', name: 'admin_add_contentieux', methods: ['GET', 'POST'])]
    public function addContentieux(Commande $commande, Request $request, EntityManagerInterface $entityManager): Response
    {
        $maintenant = new \DateTime();
        $documents = $commande->getDocuments();
        $nbDocumentsCommande = count($documents); 

        if ($commande->getDateCom() === null || ($commande->getDateRetrait() !== null && $commande->getDateRendu() !== null)) {
            return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
        }

        if ($commande->getDateRetrait() !== null && $commande->getDateRendu() === null) {
            if ($commande->getDateRetrait() < $maintenant->modify('-1 month')) {
                $statut = 'expirée';
            } else {
                $statut = 'en cours';
            }
        }

        //On créer un contentieux automatique si la commande est expirée
        if ($statut === 'expirée') {
            $contentieux = new Contentieux();
            $contentieux->setCommande($commande);
            $contentieux->setTypecont(TypeCont::Retard);
            $contentieux->setDatecont(new \DateTime());
            $contentieux->setNbdoc(count($commande->getDocuments()));

            $entityManager->persist($contentieux);
            $entityManager->flush();

            $this->addFlash('success', 'Contentieux créé automatiquement pour retard.');

            return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
        }

        //Sinon, on affiche le formulaire pour ajouter un contentieux de type dégradation
        $contentieux = new Contentieux();
        $contentieux->setCommande($commande);
        $contentieux->setTypecont(TypeCont::Degradation);
        $contentieux->setDatecont(new \DateTime());

        $form = $this->createForm(ContentieuxType::class, $contentieux, [
            'nbDocMax' => $nbDocumentsCommande,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contentieux);
            $entityManager->flush();

            $utilisateur = $commande->getUtilisateur();
            $nbDoc = $contentieux->getNbdoc() ?? 0;
            $utilisateur->setNbContentieux($utilisateur->getNbContentieux() + $nbDoc);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Contentieux ajouté avec succès');
            return $this->redirectToRoute('admin_commandes_show', ['id' => $commande->getId()]);
        }

        return $this->render('admin/contentieux/new.html.twig', [
            'form' => $form->createView(),
            'commande' => $commande,
            'documents' => $documents,
        ]);
    }

    #[Route('/avis', name: 'admin_avis_index')]
    public function avis(AvisRepository $avisRepository): Response
    {
        return $this->render('admin/avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }

    #[Route('/avis/{id}/supprimer', name: 'admin_avis_delete', methods: ['POST'])]
    public function adminDelete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_BIBLIOTHECAIRE')) {
            $this->addFlash('danger', 'Accès refusé. Seul un administrateur peut faire cette action.');
            return $this->redirectToRoute('app_document_index');
        }

        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
            $this->addFlash('success', 'Avis supprimé avec succès par l\'administrateur.');
        }

        return $this->redirectToRoute('admin_avis_index', [], Response::HTTP_SEE_OTHER);
    }
}