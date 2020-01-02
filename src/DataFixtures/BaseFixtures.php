<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Roles;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker =  Factory::create('fr_FR');
         

        $superAdmin = new User();

        $superAdmin->setUsername($faker->userName);
        $superAdmin->setPassword($this->encoder->encodePassword($superAdmin,'super'));
        $superAdmin->setEmail($faker->email);
        $superAdmin->setFname($faker->firstName());
        $superAdmin->setLname($faker->lastName);
        $superAdmin->setPhone($faker->phoneNumber);
        $superAdmin->setIsActive($faker->boolean(true));
        $superAdmin->setCountry($faker->country);
        $superAdmin->setCity($faker->city);
        $superAdmin->setAddress($faker->address);
        $role = new Roles();
        $role->setRoleName('ROLE_SUPER_ADMIN');
        $superAdmin->setUserRoles($role);
        $manager->persist($superAdmin);

        for ($i=0; $i < 10; $i++) { 

            $admin = new User();
            $admin->setUsername($faker->userName);
            $admin->setPassword($this->encoder->encodePassword($superAdmin,'admin'));
            $admin->setEmail($faker->email);
            $admin->setFname($faker->firstName());
            $admin->setLname($faker->lastName);
            $admin->setPhone($faker->phoneNumber);
            $admin->setIsActive($faker->boolean(true));
            $admin->setCountry($faker->country);
            $admin->setCity($faker->city);
            $admin->setAddress($faker->address);
            $role = new Roles();
            $role->setRoleName('ROLE_ADMIN');
            $admin->setUserRoles($role);
            $admin->addSupervisor($superAdmin);
            $superAdmin->addSupervisorUser($admin);

            $manager->persist($admin);

            if($i%2 == 0){
                $casher = new User();
                $casher->setUsername($faker->userName);
                $casher->setPassword($this->encoder->encodePassword($superAdmin,'casher'));
                $casher->setEmail($faker->email);
                $casher->setFname($faker->firstName());
                $casher->setLname($faker->lastName);
                $casher->setPhone($faker->phoneNumber);
                $casher->setIsActive($faker->boolean(true));
                $casher->setCountry($faker->country);
                $casher->setCity($faker->city);
                $casher->setAddress($faker->address);
                $role = new Roles();
                $role->setRoleName('ROLE_CASHER');
                $casher->setUserRoles($role);
                $casher->addSupervisor($admin);
                $admin->addSupervisorUser($casher);

                $manager->persist($casher);
            }
        }

        $manager->flush();
    }
}
