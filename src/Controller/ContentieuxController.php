<?php

namespace App\Controller;

use App\Entity\Contentieux;
use App\Form\ContentieuxType;
use App\Repository\ContentieuxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contentieux')]
final class ContentieuxController extends AbstractController
{
    #[Route(name: 'app_contentieux_index', methods: ['GET'])]
    public function index(ContentieuxRepository $contentieuxRepository): Response
    {
        return $this->render('contentieux/index.html.twig', [
            'contentieuxes' => $contentieuxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contentieux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contentieux = new Contentieux();
        $form = $this->createForm(ContentieuxType::class, $contentieux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contentieux);
            $entityManager->flush();

            return $this->redirectToRoute('app_contentieux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contentieux/new.html.twig', [
            'contentieux' => $contentieux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contentieux_show', methods: ['GET'])]
    public function show(Contentieux $contentieux): Response
    {
        return $this->render('contentieux/show.html.twig', [
            'contentieux' => $contentieux,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contentieux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contentieux $contentieux, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContentieuxType::class, $contentieux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contentieux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contentieux/edit.html.twig', [
            'contentieux' => $contentieux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contentieux_delete', methods: ['POST'])]
    public function delete(Request $request, Contentieux $contentieux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contentieux->getIdcont(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contentieux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contentieux_index', [], Response::HTTP_SEE_OTHER);
    }
}
