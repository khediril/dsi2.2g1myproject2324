<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->passwordHasher->hashPassword($user, "user" . $i))
                ->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->passwordHasher->hashPassword($user, "admin" . $i))
                ->setRoles(['ROLE_ADMIN']);

            $manager->persist($user);
        }
        $categs=[];
        for ($i = 1; $i <= 4; $i++) {
            $categ = new Category();
            $categ->setName("Categorie" . $i);
            $manager->persist($categ);
            $categs[]=$categ;
        }
        $images = ['ssd.jpg','ord.jpg'];
        for ($i = 1; $i <= 100; $i++) {
            $product = new Product();
            $product->setName("Produit" . $i)
                    ->setPrice(100*$i)
                    ->setImage($images[rand(0,1)])
                    ->setCategory($categs[rand(0,3)]);


            $manager->persist($product);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
