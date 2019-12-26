<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{

    private $entityManager;
    private $encoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    public function supports($data): bool
    {
      return $data instanceof User;  
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {
        if($data->getPassword())
    {
        $data->setPassword(
            $this->encoder->encodePassword($data, $data->getPassword())
        );
        $data->eraseCredentials();
    }
    
        $this->entityManager->persist($data);

        $this->entityManager->flush();
    } 

   public function remove($data)
   {

    
    $this->entityManager->remove($data);
    $this->entityManager->flush();
   }

}