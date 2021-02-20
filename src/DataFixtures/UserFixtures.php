<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    
    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setEmail('admin@gmail.com')
            ->setRoles(["ROLE_ADMIN"]);
        
        $user->setPassword($this->encoder->encodePassword($user, 'dev'));
        $manager->persist($user);
        $user = (new User())
            ->setEmail('user@gmail.com')
            ->setRoles(["ROLE_ADMIN"]);
    
        $user->setPassword($this->encoder->encodePassword($user, 'dev'));
    
        $manager->persist($user);
        $manager->flush();
    }
}