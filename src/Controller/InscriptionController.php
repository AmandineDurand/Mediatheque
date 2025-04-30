<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('accueil');
        }
        
        $user = new Utilisateur();
        $form = $this->createForm(InscriptionFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rawPassword = $form->get('mdp')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $rawPassword); //Note : hachage du mot de passe
            $user->setMotDePasse($hashedPassword);

            $user->setRoles(['ROLE_INSCRIT']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('connexion');
        }

        return $this->render('inscription/inscription.html.twig', [
            'inscriptionForm' => $form->createView(),
        ]);
    }
}