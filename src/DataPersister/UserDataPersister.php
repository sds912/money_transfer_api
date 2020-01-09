<?php
namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Agency;
use App\PermissionRoles;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserDataPersister implements DataPersisterInterface
{

    private $entityManager;
    private $encoder;
    private $security;
    private $request;
  

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        Security $security,
        RequestStack $request)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->encoder = $encoder;
        $this->request = $request;
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

        $currentUser = $this->security->getUser();
        $password = $data->getUsername();
        $currentRole = $currentUser->getRoles()[0];
        $dataRole = $data->getUserRoles()->getRoleName();
        
    
       $uri = $this->request->getCurrentRequest()->getPathInfo(); 
       $pattern = '/\/api\/users\/\d+\/block/';
        if(preg_match($pattern,$uri))
        {
            if($dataRole == PermissionRoles::OWNER)
            {
                $agencies = $this->entityManager->getRepository(Agency::class)->findBy(['owner'=>$data]);
                foreach ($agencies as $agency) {
                   $agency->setIsActive(!$agency->getIsActive());
                   $this->entityManager->persist($agency);
                }
            }

            if($currentRole === PermissionRoles::OWNER)
            {
              $owner = $this->entityManager->getRepository(User::class)->findOneBY(['username'=>$currentUser->getUsername()]);
              $user = $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$data->getUsername()]);

              if($user->getSupervisor()[0] != $owner)
              {
                throw new HttpException(Response::HTTP_UNAUTHORIZED, "You cann't manage this user");
              }
              
            }
    
        }

        if($currentRole != PermissionRoles::SUPER_ADMIN)
        {
            if (PermissionRoles::SUPER_ADMIN != $dataRole) 
            { 
               $data->addSupervisor($currentUser);
            }else{
                throw new HttpException(Response::HTTP_UNAUTHORIZED, "You can not Change Super Admin Attributes");
            }
        }
        if($currentRole === PermissionRoles::OWNER)
        { 
            if ($dataRole != PermissionRoles::AGENCY_ADMIN && $dataRole != PermissionRoles::AGENCY_CASHIER)
            {
                throw new HttpException(Response::HTTP_UNAUTHORIZED, "You can only create agency admin or agency cashier for your account");
            }
        }else{

            if ($dataRole === PermissionRoles::AGENCY_ADMIN || $dataRole == PermissionRoles::AGENCY_CASHIER)
            {
                throw new HttpException(Response::HTTP_UNAUTHORIZED, "You can only create system users");
            } 
        }

       

        $data->setPassword($this->encoder->encodePassword($data, $password));
        $data->eraseCredentials();

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    } 

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}