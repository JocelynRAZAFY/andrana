<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setRoles([User::ROLE_USER]);
        $user->setUsername('Joraz');
        $user->setEmail('rt1jocelyn@gmail.com');
        $user->setEmailInitial('rt1jocelyn@gmail.com');
        $user->setRoles([User::ROLE_USER]);
        $user->setPassword($this->passwordEncoder->encodePassword($user,'123'));
        $user->setStatusValidationEmail(true );
        $user->setAvatar(false);
        $user->setLogo(false);
        $user->setRegistrationDate(new \DateTime());
        $user->setIsSocial(false);
        $manager->persist($user);
        $manager->flush();
        // $product = new Product();
        // $manager->persist($product);
    }
}
