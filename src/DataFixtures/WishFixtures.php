<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_EN');

        //Je récupère l'ensemble des catégories présentes dans la BD.
        $categories = $manager->getRepository(Category::class)->findAll();

        for($i = 0; $i < 10; $i++){
            $wish = new Wish();
            $wish->setTitle($faker->word());
            $wish->setAuthor($faker->name());
            $wish->setDescription($faker->realText());
            //Permet d'aller chercher une catégorie de manière aléatoire dans le tableau de catégories.
            $wish->setCategory($faker->randomElement($categories));
            $wish->setDateCreated(
                \DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('-6 months', 'now')));
            $wish->setPublished($faker->numberBetween(0, 1));
            $manager->persist($wish);
        }
        $manager->flush();
    }

    /**
     * Méthode qui permet de savoir quelle dépendance il y a entre les fixtures.
     * Ici il faudra que le CategoryFixtures soit lancé en premier car on a besoin
     * des catégories pour les mettre dans un wish.
     * @return \class-string[]
     */
    public function getDependencies():array
    {
        return [CategoryFixtures::class];
    }
}
