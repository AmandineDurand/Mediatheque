<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/avis')]
final class AvisController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($avi->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_document_show', ['id' => $avi->getDocument()->getId()]);
        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'formAvis' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($avi->getUtilisateur() !== $user) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer un avis qui ne vous appartient pas.');
            return $this->redirectToRoute('app_document_index');
        }

        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
            $this->addFlash('success', 'Votre avis a été supprimé.');
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
