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

        $superAdmin->setUsername('babs912');
        $superAdmin->setEmail('senghor.pape912@hotmail.com');
        $superAdmin->setPhone('+221777443663');
        $superAdmin->setFname('Papa Babacar Ngor');
        $superAdmin->setLname('Senghor');
        $superAdmin->setCountry('Senegal');
        $superAdmin->setCity('Dakar');
        $superAdmin->setAddress('Parcelles Assainies');
        $superAdmin->setActive(true);
        $superAdmin->setPassword($this->encoder->encodePassword($superAdmin,'keurm912'));
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']);
        $manager->persist($superAdmin);
        $manager->flush();
    }
}
