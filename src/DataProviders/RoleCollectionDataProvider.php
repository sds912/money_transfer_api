<?php

namespace App\DataProviders;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Roles;
use App\PermissionRoles;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

final class RoleCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private $collectionExtensions;
    private $managerRegistry;
    private $security;
    

    public function __construct( ManagerRegistry $managerRegistry, /* iterable */ $collectionExtensions = [], Security $security )
    {
       
        $this->collectionExtensions = $collectionExtensions;
        $this->managerRegistry = $managerRegistry;
        $this->security = $security;
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Roles::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
 {
     $manager = $this->managerRegistry->getManagerForClass($resourceClass);
     $repository = $manager->getRepository($resourceClass);
     if($this->security->getUser()->getRoles()[0] === PermissionRoles::SUPER_ADMIN )
     {
        $queryBuilder = 
        $repository->createQueryBuilder('r')
        ->where('r.roleName != :admin')
        ->andWhere('r.roleName != :partner')
        ->andWhere('r.roleName != :adminAgency')
        ->andWhere('r.roleName != :cashierAgency')
        ->setParameter('admin', PermissionRoles::SUPER_ADMIN)
        ->setParameter('partner',PermissionRoles::OWNER)
        ->setParameter('adminAgency',PermissionRoles::AGENCY_ADMIN)
        ->setParameter('cashierAgency',PermissionRoles::AGENCY_CASHIER);


     }
     if($this->security->getUser()->getRoles()[0] === PermissionRoles::ADMIN ){
        $queryBuilder = 
        $repository->createQueryBuilder('r')
        ->where('r.roleName != :super')
        ->andWhere('r.roleName != :admin')
        ->andWhere('r.roleName != :adminAgency')
        ->andWhere('r.roleName != :cashierAgency')

        ->setParameter('super', PermissionRoles::SUPER_ADMIN)
        ->setParameter('admin',PermissionRoles::ADMIN)
        ->setParameter('adminAgency',PermissionRoles::AGENCY_ADMIN)
        ->setParameter('cashierAgency',PermissionRoles::AGENCY_CASHIER);
     }

     if($this->security->getUser()->getRoles()[0] === PermissionRoles::OWNER ){
        $queryBuilder = 
        $repository->createQueryBuilder('r')
        ->where('r.roleName != :super')
        ->andWhere('r.roleName != :admin')
        ->andWhere('r.roleName != :cashier')
        ->andWhere('r.roleName != :partner')
        ->setParameter('super', PermissionRoles::SUPER_ADMIN)
        ->setParameter('admin',PermissionRoles::ADMIN)
        ->setParameter('cashier',PermissionRoles::CASHIER)
        ->setParameter('partner',PermissionRoles::OWNER);
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