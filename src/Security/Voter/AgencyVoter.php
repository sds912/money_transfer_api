<?php

namespace App\Security\Voter;

use App\Entity\Agency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AgencyVoter extends Voter
{

    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(RequestStack $request, EntityManagerInterface $entityManager)
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    protected function supports($attribute, $subject)
    {
        
        if(empty($subject) || !$subject instanceof Agency) 
        {
            return false;
        }


        return in_array($attribute, ['agency.edit', 'agency_view']);
          
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($user != $subject->getAgencyOwner()->getSupervisor() || $user != $subject->getAgencyOwner())
        {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
