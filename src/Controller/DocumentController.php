<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Commande;
use App\Entity\Avis;
use App\Entity\Livre;
use App\Entity\Periodique;
use App\Entity\Sonore;
use App\Entity\Video;
use App\Enum\FormatVid;
use App\Enum\FormatSon;
use App\Enum\Frequence;
use App\Form\DocumentType;
use App\Form\AvisType;
use App\Repository\DocumentRepository;
use App\Repository\AuteurRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormError;

// #[Route('/document')]
final class DocumentController extends AbstractController
{
    #[Route('/document', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository, AuteurRepository $auteurRepository, CategorieRepository $categorieRepository, EntityManagerInterface $em, Request $request): Response
    {
        $search = $request->query->get('search');
        $type = $request->query->get('type');
        $auteur = $request->query->get('auteur');
        $categorie = $request->query->get('categorie');
        // $documents = $documentRepository->findAll();

        $auteurId = is_numeric($auteur) ? (int) $auteur : null;
        $categorieId = is_numeric($categorie) ? (int) $categorie : null;

        $documents = $documentRepository->findByFilters($search, $type, $auteurId, $categorieId);

        $user = $this->getUser();

        $panier = $em->getRepository(Commande::class)->findOneBy([
            'utilisateur' => $user,
            'dateCom' => null
        ]);

        $documentTypes = [];

        foreach ($documents as $document) {
            if ($document instanceof Livre) {
                $documentTypes[$document->getId()] = 'livre';
            } elseif ($document instanceof Periodique) {
                $documentTypes[$document->getId()] = 'periodique';
            } elseif ($document instanceof Sonore) {
                $documentTypes[$document->getId()] = 'sonore';
            } elseif ($document instanceof Video) {
                $documentTypes[$document->getId()] = 'video';
            } else {
                $documentTypes[$document->getId()] = 'inconnu';
            }
        }

        $uniqueTypes = array_unique(array_values($documentTypes));

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'document_types' => $documentTypes,
            'types' => $uniqueTypes,  
            'auteurs' => $auteurRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'panier' => $panier,
        ]);
    }

    #[Route('document/nouveau', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function nouveau(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = $request->query->get('type', 'livre');

        $document = match ($type) {
            'livre' => new Livre(),
            'periodique' => new Periodique(),
            'sonore' => new Sonore(),
            'video' => new Video(),
        };

        $form = $this->createForm(DocumentType::class, $document, [
            'is_edit' => false,
            'document_type' => $type,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $submittedType = $form->get('type')->getData();

            if ($submittedType !== $type) {
                $type = $submittedType;
                $document = match ($type) {
                    'livre' => new Livre(),
                    'periodique' => new Periodique(),
                    'sonore' => new Sonore(),
                    'video' => new Video(),
                };

                $form = $this->createForm(DocumentType::class, $document, [
                    'is_edit' => false,
                    'document_type' => $type,
                ]);
                $form->handleRequest($request);
            }

            if ($form->isValid()) {
                $hasErrors = false;

                switch ($type) {
                    case 'livre':
                        $isbn = $form->get('ISBN')->getData();
                        $nbPages = $form->get('nbPages')->getData();

                        if (!$isbn) {
                            $form->get('ISBN')->addError(new FormError("Le champ ISBN est requis."));
                            $hasErrors = true;
                        } else {
                            $document->setISBN($isbn);
                        }
                        if ($nbPages === null) {
                            $form->get('nbPages')->addError(new FormError("Le nombre de pages est requis."));
                            $hasErrors = true;
                        } else {
                            $document->setNbPages($nbPages);
                        }
                        break;

                    case 'periodique':
                        $frequenceString = $form->get('frequence')->getData();
                        $numero = $form->get('numero')->getData();

                        if (!$frequenceString) {
                            $form->get('frequence')->addError(new FormError("La fréquence est requise."));
                            $hasErrors = true;
                        } else {
                            $frequence = Frequence::from($frequenceString); //conversion string → enum
                            $document->setFrequence($frequence);
                        }
                        if ($numero === null) {
                            $form->get('numero')->addError(new FormError("Le numéro est requis."));
                            $hasErrors = true;
                        } else {
                            $document->setNumero($numero);
                        }
                        break;

                    case 'sonore':
                        $dureeSon = $form->get('dureeSon')->getData();
                        $formatSonString = $form->get('formatSon')->getData();

                        if ($dureeSon === null) {
                            $form->get('dureeSon')->addError(new FormError("La durée sonore est requise."));
                            $hasErrors = true;
                        } else {
                            $document->setDureeSon($dureeSon);
                        }
                        if (!$formatSonString) {
                            $form->get('formatSon')->addError(new FormError("Le format sonore est requis."));
                            $hasErrors = true;
                        } else {
                            $formatSon = FormatSon::from($formatSonString);
                            $document->setFormatSon($formatSon);
                        }
                        break;

                    case 'video':
                        $dureeVid = $form->get('dureeVid')->getData();
                        $formatVidString = $form->get('formatVid')->getData();

                        if ($dureeVid === null) {
                            $form->get('dureeVid')->addError(new FormError("La durée vidéo est requise."));
                            $hasErrors = true;
                        } else {
                            $document->setDureeVid($dureeVid);
                        }
                        if (!$formatVidString) {
                            $form->get('formatVid')->addError(new FormError("Le format vidéo est requis."));
                            $hasErrors = true;
                        } else {
                            $formatVid = FormatVid::from($formatVidString);
                            $document->setFormatVid($formatVid);
                        }
                        break;
                }

                if ($hasErrors) {
                    return $this->render('document/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $document->setTitreDoc($form->get('titreDoc')->getData());
                $document->setAnneeSortie($form->get('anneeSortie')->getData());
                $document->setResumeDoc($form->get('resumeDoc')->getData());
                $document->setStockDoc($form->get('stockDoc')->getData());
                $document->setAuteur($form->get('auteur')->getData());
                
                $categories = $form->get('categories')->getData();
                foreach ($categories as $categorie) {
                    $document->addCategory($categorie);
                }

                try {
                    $entityManager->persist($document);
                    $entityManager->flush();
                
                    return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    switch (get_class($document)) {
                        case \App\Entity\Livre::class:
                            $form->get('ISBN')->addError(new FormError("Un livre avec cet ISBN existe déjà, veuillez en saisir un autre"));
                            break;
                
                        case \App\Entity\Periodique::class:
                            $form->get('numero')->addError(new FormError("Un périodique avec ce numéro existe déjà, veuillez en saisir"));
                            break;
                
                        default:
                            $form->addError(new FormError("Ce document existe déjà."));
                            break;
                    }
                }
            } 
        }

        return $this->render('document/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('document/{id}', name: 'app_document_show', methods: ['GET', 'POST'])]
    public function montrer(Document $document, Request $request, EntityManagerInterface $em): Response
    {
        if ($document instanceof Livre) {
            $type = 'livre';
        } elseif ($document instanceof Periodique) {
            $type = 'periodique';
        } elseif ($document instanceof Sonore) {
            $type = 'sonore';
        } elseif ($document instanceof Video) {
            $type= 'video';
        } else {
            $type = 'inconnu';
        }

        $user = $this->getUser();

        $panier = $em->getRepository(Commande::class)->findOneBy([
            'utilisateur' => $user,
            'dateCom' => null
        ]);

        $avisExist = $em->getRepository(Avis::class)->findOneBy([
            'utilisateur' => $user,
            'document' => $document,
        ]);
    
        $form = null;

        if (!$avisExist) {
            $avis = new Avis();
            $avis->setDocument($document);
            $avis->setUtilisateur($user);
    
            $form = $this->createForm(AvisType::class, $avis);
        } else {
            $form = $this->createForm(AvisType::class, $avisExist);
        }
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            $notes = array_map(fn($avis) => $avis->getNote(), $document->getAvis()->toArray());
            $moyenne = count($notes) ? array_sum($notes) / count($notes) : null;

            $this->addFlash('success', 'Votre avis a été ajouté ou modifié.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        $notes = array_map(fn($avis) => $avis->getNote(), $document->getAvis()->toArray());
        $moyenne = count($notes) ? array_sum($notes) / count($notes) : null;

        return $this->render('document/show.html.twig', [
            'document' => $document,
            'type' => $type, 
            'panier' => $panier,
            'formAvis' => $form->createView(),
            'moyenne' => $moyenne,
            'avisExist' => $avisExist,
        ]);
    }

    #[Route('document/{id}/modifier', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function modifier(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $originalType = null;
        if ($document instanceof Livre) {
            $originalType = 'livre';
            $livreConcret = $document;
        } elseif ($document instanceof Periodique) {
            $originalType = 'periodique';
            $periodiqueConcret = $document;
        } elseif ($document instanceof Sonore) {
            $originalType = 'sonore';
            $sonoreConcret = $document;
        } elseif ($document instanceof Video) {
            $originalType = 'video';
            $videoConcret = $document;
        }

        $form = $this->createForm(DocumentType::class, $document, [
            'is_edit' => true,
            'document_type' => $originalType
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            switch ($originalType) {
                case 'livre':
                    $livreConcret->setISBN($form->get('ISBN')->getData());
                    $livreConcret->setNbPages($form->get('nbPages')->getData());
                    break;
                case 'periodique':
                    $periodiqueConcret->setFrequence($form->get('frequence')->getData());
                    $periodiqueConcret->setNumero($form->get('numero')->getData());
                    break;
                case 'sonore':
                    $sonoreConcret->setDureeSon($form->get('dureeSon')->getData());
                    $sonoreConcret->setFormatSon($form->get('formatSon')->getData());
                    break;
                case 'video':
                    $videoConcret->setDureeVid($form->get('dureeVid')->getData());
                    $videoConcret->setFormatVid($form->get('formatVid')->getData());
                    break;
            }

            try {
                $entityManager->flush();
                return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                switch (get_class($document)) {
                    case \App\Entity\Livre::class:
                        $form->get('ISBN')->addError(new FormError("Un livre avec cet ISBN existe déjà, veuillez en saisir un autre"));
                        break;
            
                    case \App\Entity\Periodique::class:
                        $form->get('numero')->addError(new FormError("Un périodique avec ce numéro existe déjà, veuillez en saisir"));
                        break;
            
                    default:
                        $form->addError(new FormError("Ce document existe déjà."));
                        break;
                }
            }
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
            'document_type' => $originalType
        ]);
    }

    #[Route('document/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function supprimer(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->getPayload()->getString('_token'))) {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {

            foreach ($document->getUtilisateursAimant() as $user) {
            $document->removeUtilisateursAimant($user);
            }

            foreach ($document->getCommandes() as $commande) {
                $document->removeCommande($commande);
            }

            foreach ($document->getCategories() as $categorie) {
                $document->removeCategory($categorie);
            }
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/favoris', name: 'app_favoris')]
    public function favoris(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        $documentsFavoris = $user->getDocumentsAimes();

        $documentTypes = [];

        foreach ($documentsFavoris as $document) {
            if ($document instanceof Livre) {
                $documentTypes[$document->getId()] = 'livre';
            } elseif ($document instanceof Periodique) {
                $documentTypes[$document->getId()] = 'periodique';
            } elseif ($document instanceof Sonore) {
                $documentTypes[$document->getId()] = 'sonore';
            } elseif ($document instanceof Video) {
                $documentTypes[$document->getId()] = 'video';
            } else {
                $documentTypes[$document->getId()] = 'inconnu';
            }
        }

        $panier = $em->getRepository(Commande::class)->findOneBy([
            'utilisateur' => $user,
            'dateCom' => null
        ]);
        
        return $this->render('document/favoris.html.twig', [
            'documents' => $documentsFavoris,
            'document_types' => $documentTypes, 
            'panier' => $panier
        ]);
    }

    #[Route('document/{id}/toggle-favori', name: 'app_toggle_favori')]
    public function toggleFavori(Document $document, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getDocumentsAimes()->contains($document)) {
            $user->removeDocumentsAime($document);
        } else {
            $user->addDocumentsAime($document);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_document_index', ['message' => 'Favori mis à jour']);
    }

    #[Route('/document/{id}/emprunter', name: 'document_emprunter')]
    public function emprunter(Document $document, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour emprunter un document.');
        }

        $commande = $em->getRepository(Commande::class)->findOneBy([
            'utilisateur' => $user,
            'dateCom' => null,
        ]);

        if (!$commande) {
            $commande = new Commande();
            $commande->setUtilisateur($user);
            $em->persist($commande);
        }

        if (count($commande->getDocuments()) >= 6) {
            $this->addFlash('error', 'Vous ne pouvez pas ajouter plus de 6 documents dans votre panier.');
            return $this->redirectToRoute('voir_panier');
        }

        if (!$commande->getDocuments()->contains($document)) {
            $commande->addDocument($document);
        }

        $em->flush();
        $this->addFlash('success', 'Document ajouté à votre panier.');
        return $this->redirectToRoute('app_document_index');
    }

    #[Route('/document/{id}/enlever', name: 'enlever_du_panier')]
    public function enleverDuPanier(Document $document, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $commande = $em->getRepository(Commande::class)->findOneBy([
            'utilisateur' => $user,
            'dateCom' => null
        ]);

        if ($commande && $commande->getDocuments()->contains($document)) {
            $commande->removeDocument($document);
        }

        $em->flush();

        return $this->redirectToRoute('app_document_index');
    }
}
