<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('Admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('Test123');
        $admin->setnom('NomAdmin');
        $admin->setprenom('PrenomAdmin');
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('User@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('Test123');
        $user->setNom('NomUser');
        $user->setPrenom('PrenomUser');
        $manager->persist($user);

        $manager->flush();
    }
}
