<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUser($email, $roles, $password, $prenom = null, $nom = null, $fonction = null, $telephone = null)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($password);
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setFonction($fonction);
        $user->setTelephone($telephone);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getUser($id)
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }
}
