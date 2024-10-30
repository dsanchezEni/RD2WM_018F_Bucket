<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher){

    }
    public function load(ObjectManager $manager): void
    {
        //Créer un administrateur
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@test.fr');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $password=$this->userPasswordHasher->hashPassword($userAdmin,'123456');
        $userAdmin->setPassword($password);
        $manager->persist($userAdmin);

        //Créer 10 Utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername("user$i");
            $user->setEmail("user$i@test.fr");
            $user->setRoles(['ROLE_USER']);
            $password=$this->userPasswordHasher->hashPassword($userAdmin,'123456');
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
