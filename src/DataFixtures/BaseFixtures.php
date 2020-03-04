<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Partner;
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
        //$faker =  Factory::create('fr_FR');
         
         $role1 = new  Roles();
         $role1->setRoleName('ROLE_SUPER_ADMIN');
         $manager->persist($role1);

         $role2 = new  Roles();
         $role2->setRoleName('ROLE_ADMIN');
         $manager->persist($role2);


         $role3 = new  Roles();
         $role3->setRoleName('ROLE_CASHIER');
         $manager->persist($role3);


         $role4 = new  Roles();
         $role4->setRoleName('ROLE_AGENCY_OWNER');
         $manager->persist($role4);

         $role5 = new  Roles();
         $role5->setRoleName('ROLE_AGENCY_ADMIN');
         $manager->persist($role5);

         $role6 = new  Roles();
         $role6->setRoleName('ROLE_AGENCY_CASHIER');
         $manager->persist($role6);


         


        $superAdmin = new User();

        $superAdmin->setPassword($this->encoder->encodePassword($superAdmin,'super'));
        $superAdmin->setEmail('pape912.ps@gmail.com');
        $superAdmin->setFname('Babacar');
        $superAdmin->setLname('Senghor');
        $superAdmin->setPhone('777443663');
        $superAdmin->setIsActive(true);
        $role1->addUser($superAdmin);
        $manager->persist($superAdmin);
        
        for ($i=0; $i < 5; $i++){
            $admin = new User();
            $admin->setPassword($this->encoder->encodePassword($superAdmin,'admin'));
            $admin->setEmail('email'.$i.'admin@gmail.com');
            $admin->setFname('prenom'.$i);
            $admin->setLname('nom'.$i);
            $admin->setPhone('775845121'.$i);
            $admin->setIsActive(true);
            $role2->addUser($admin);
            $admin->addSupervisor($superAdmin);
            $superAdmin->addSupervisorUser($admin);

            $manager->persist($admin);

        
            $casher = new User();
            $casher->setPassword($this->encoder->encodePassword($superAdmin,'casher'));
            $casher->setEmail('email'.$i.'cashier@gmail.com');
            $casher->setFname('prenom'.$i);
            $casher->setLname('nom'.$i);
            $casher->setPhone('785644255'.$i);
            $casher->setIsActive(true);
            $role3->addUser($casher);
            $casher->addSupervisor($admin);
            $admin->addSupervisorUser($casher);

            $manager->persist($casher);

        
            
            $user = new User();
            $user->setPassword($this->encoder->encodePassword($user,'partner'));
            $user->setEmail('email'.$i.'cashier@gmail.com');
            $user->setFname('prenom'.$i);
            $user->setLname('nom'.$i);
            $user->setPhone('785644255'.$i);
            $user->setIsActive(true);
            $user->addSupervisor($admin);
            $admin->addSupervisorUser($user);

            $manager->persist($user);
            
            $partner = new Partner();
            $partner->setPhone('785456556'.$i);
            $partner->setCountry('senegal');
            $partner->setCity('dakar');
            $partner->setAddress('parcelle unite 11');
            $partner->setNinea('DK55456986658');
            $partner->setRc('785232822556532');
            $partner->setUser($user);
            
       
            $manager->persist($partner);

            

        }

    
        
        

        $manager->flush();
    }
}
