<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\Product;
use App\Entity\Client;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $mobile = [
            'Xperia 5',
            'Y5 2019',
            'Galaxy J4+',
            'Galaxy A10',
            'Redmi 6',
            'View Lite',
            'Iphone X',
            'Redmi Note 8T',
            'P Smart Z',
            'Galaxy A40',
            'moto g8 plus',
            'Mate 20 lite',
            'Mi9 SE',
            'P30 lite',
            'KEY2 LE',
            'Nova 5T',
            'Fairphone 2',
            'iPhone 7',
            'Reno',
            'Galaxy A70',
        ];

        $faker = Faker\Factory::create('fr_fr');

        $tab = [];

        foreach ($mobile as $name) {
            $product = new Product();
            $product->setName($name);
            $manager->persist($product);
            $tab[] = $product;
        }

        $clientFirst = new Client();
        $clientFirst->setEmail($faker->email);

        for ($i=0; $i < 5; $i++) {
            $clientFirst->addProduct($tab[$i]);
            $user = new User();
            $user->setFullname($faker->name);
            $user->setEmail($faker->email);
            $user->setClient($clientFirst);
            $manager->persist($user);
        }

        $manager->persist($clientFirst);

        $clientSecond = new Client();
        $clientSecond->setEmail($faker->email);

        for ($i=10; $i < 15; $i++) {
            $clientSecond->addProduct($tab[$i]);
            $user = new User();
            $user->setFullname($faker->name);
            $user->setEmail($faker->email);
            $user->setClient($clientSecond);
            $manager->persist($user);
        }

        $manager->persist($clientSecond);

        $manager->flush();
    }
}
