<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserClient;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
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

        $user = new User();
        $user->setEmail('user@bilemo.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'bilemo'));

        foreach ($tab as $item) {
            $user->addProduct($item);
            $userClient = new UserClient();
            $userClient->setFullname($faker->name);
            $userClient->setEmail($faker->email);
            $userClient->setUser($user);
            $manager->persist($userClient);
        }

        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('user2@bilemo.com');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2,'bilemo2'));

        for ($i = 0;$i < 5; $i++) {
            $user2->addProduct($tab[$i]);
            $userClient = new UserClient();
            $userClient->setFullname($faker->name);
            $userClient->setEmail($faker->email);
            $userClient->setUser($user2);
            $manager->persist($userClient);
        }

        $manager->persist($user2);

        $manager->flush();
    }
}
