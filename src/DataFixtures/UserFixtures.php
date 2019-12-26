<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $superAdmin = new User();

        $superAdmin->setUsername('supperAdmin');
        $superAdmin->setPassword($this->encoder->encodePassword($superAdmin,'super'));
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']);
        $manager->persist($superAdmin);
        $manager->flush();
    }
}
