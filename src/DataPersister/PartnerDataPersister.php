<?php
namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Partner;
use App\Entity\Roles;
use App\PermissionRoles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class PartnerDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $encoder;
    private $security;
  
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->encoder = $encoder;
    }

    public function supports($data): bool
    {
      return $data instanceof Partner;  
    }

    /**
     * @param Partner $data
     */
    public function persist($data)
    {
        $currentUser = $this->security->getUser();
        $role = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::OWNER]);

      $data->getUser()->setUserRoles($role);
        $data->setPartnerCreator($currentUser);
        $data->getUser()->setPassword($this->encoder->encodePassword($data->getUser(), $data->getUser()->getPhone()));
        $data->getUser()->eraseCredentials();
    
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    } 

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}