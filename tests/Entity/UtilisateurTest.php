<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testConstructeur(): void
    {
        $utilisateur = new Utilisateur();
        $this->assertContains("ROLE_USER", $utilisateur->getRoles());
        $this->assertEquals(0, $utilisateur->getNbContentieux());
    }
    
    public function testSetterGetter(): void
    {
        //On créer un utilisateur
        $utilisateur = new Utilisateur();
        
        //On renseigne les informations
        $utilisateur->setNomUtil('Dupont');
        $utilisateur->setPrenomUtil('Jean');
        $utilisateur->setEmailUtil('jean.dupont@example.com');
        $utilisateur->setMotDePasse('motdepasse123');
        $utilisateur->setNbContentieux(2);
        
        //On vérifie que tout est ok
        $this->assertEquals('Dupont', $utilisateur->getNomUtil());
        $this->assertEquals('Jean', $utilisateur->getPrenomUtil());
        $this->assertEquals('jean.dupont@example.com', $utilisateur->getEmailUtil());
        $this->assertEquals('motdepasse123', $utilisateur->getMotDePasse());
        $this->assertEquals(2, $utilisateur->getNbContentieux());
    }
    
    public function testRoles(): void
    {
        //On créer un utilisateur
        $utilisateur = new Utilisateur();
        $this->assertContains('ROLE_USER', $utilisateur->getRoles());
        
        //On change le rôle
        $utilisateur->setRoles(['ROLE_ADHERENT']);
        $roles = $utilisateur->getRoles();
        
        //On vérifie que tout est ok
        $this->assertContains('ROLE_USER', $roles);
        $this->assertContains('ROLE_ADHERENT', $roles);
    }
    
    public function testIncrementContentieux(): void
    {
        //On créer un utilisateur et on vérifie qu'il n'a aucun contentieux
        $utilisateur = new Utilisateur();
        $this->assertEquals(0, $utilisateur->getNbContentieux());
        
        //On ajoute un contentieux et on vérifie que les changements sont appliqués
        $utilisateur->setNbContentieux(1);
        $this->assertEquals(1, $utilisateur->getNbContentieux());
        
        //On ajoute un autre contentieux
        $utilisateur->setNbContentieux($utilisateur->getNbContentieux()+1);
        $this->assertEquals(2, $utilisateur->getNbContentieux());
    }
    
    public function testResetContentieux(): void
    {
        //On créer un utilisateur et on ajoute 5 contentieux
        $utilisateur = new Utilisateur();
        $utilisateur->setNbContentieux(5);
        
        //On remets à 0 ses conteniteux et on vérifie que tout est ok
        $utilisateur->setNbContentieux(0);
        $this->assertEquals(0, $utilisateur->getNbContentieux());
    }
}