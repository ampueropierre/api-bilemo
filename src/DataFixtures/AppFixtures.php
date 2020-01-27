<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserClient;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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

        $admin_user = new User();
        $admin_user->setEmail('admin@bilemo.com');
        $admin_user->setPassword($this->passwordEncoder->encodePassword($admin_user,'admin'));
        $admin_user->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin_user);

        $classic_user = new User();
        $classic_user->setEmail('user@bilemo.com');
        $classic_user->setPassword($this->passwordEncoder->encodePassword($classic_user,'user'));
        $classic_user->setRoles(['ROLE_USER']);

        for ($i=0; $i < 5; $i++) {
            $classic_user->addProduct($tab[$i]);
            $userClient = new UserClient();
            $userClient->setFullname($faker->name);
            $userClient->setEmail($faker->email);
            $userClient->setUser($classic_user);
            $manager->persist($userClient);
        }

        $manager->persist($classic_user);

        $manager->flush();
    }
}
