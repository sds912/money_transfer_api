<?php

namespace App\DataProviders;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Roles;
use App\Entity\User;
use App\PermissionRoles;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class UserCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private $collectionExtensions;
    private $managerRegistry;
    private $security;
    private $entityManager;
    

    public function __construct(
         ManagerRegistry $managerRegistry,
          /* iterable */ $collectionExtensions = [], 
          Security $security,
          EntityManagerInterface $entityManager )
    {
       
        $this->collectionExtensions = $collectionExtensions;
        $this->managerRegistry = $managerRegistry;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
 {
     $manager = $this->managerRegistry->getManagerForClass($resourceClass);
     $repository = $manager->getRepository($resourceClass);
     $partner = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::OWNER]);
     $admin = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::ADMIN]);
     $cashier = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::CASHIER]);

     $owner = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::OWNER]);
     $agencyAdmin = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::AGENCY_ADMIN]);
     $agencyCashier = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::AGENCY_CASHIER]);
     $super = $this->entityManager->getRepository(Roles::class)->findOneBy(['roleName' => PermissionRoles::SUPER_ADMIN]);
     
     if($this->security->getUser()->getRoles()[0] === PermissionRoles::SUPER_ADMIN )
     {
        $queryBuilder = $repository->createQueryBuilder('u')
        ->where('u.userRoles != :partner')
        ->andWhere('u.userRoles != :admin')
        ->andWhere('u.userRoles != :cashier')
        ->setParameter('partner',$partner)
        ->setParameter('admin', $agencyAdmin)
        ->setParameter('cashier',$agencyCashier);

     

     }
     if ($this->security->getUser()->getRoles()[0] === PermissionRoles::ADMIN) {
         $queryBuilder = $repository->createQueryBuilder('u')
         ->where('u.userRoles != :partner')
         ->andWhere('u.userRoles != :admin')
         ->andWhere('u.userRoles != :cashier')
         ->setParameter('partner',$partner)
         ->setParameter('admin', $super)
         ->setParameter('cashier',$agencyCashier);
     }
     if($this->security->getUser()->getRoles()[0] === PermissionRoles::OWNER ){
        $queryBuilder = $repository->createQueryBuilder('u')
        ->join('u.supervisor','super')
        ->setParameter('super', $this->security->getUser());
        
     }
     $queryNameGenerator = new QueryNameGenerator();
     foreach ($this->collectionExtensions as $extension) {

         $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operationName, $context);

         if ($extension instanceof QueryResultCollectionExtensionInterface && $extension->supportsResult($resourceClass, $operationName, $context)) {
            // dd($extension->getResult($queryBuilder, $resourceClass, $operationName, $context));
             return $extension->getResult($queryBuilder, $resourceClass, $operationName, $context);
         }
     }

return $queryBuilder->getQuery()->getResult();
}
}