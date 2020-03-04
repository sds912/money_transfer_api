<?php
// src/Security/PostVoter.php
namespace App\Security\Voters;

use App\Entity\PartnerAccount;
use App\Entity\User;
use App\PermissionRoles;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccountPermissionVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'VIEW';
    const EDIT = 'EDIT';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof PartnerAccount) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
        
            return false;
        }


        /** @var PartnerAccount $account */
        $account = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($account, $user);
            case self::EDIT:
                return $this->canEdit($account, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(PartnerAccount $account, User $user)
    {
       
        if ($this->canEdit($account, $user)) {
            return true;
        }

        // the Post object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return  true;   //!$entretien->isPrivate();
    }

    private function canEdit(PartnerAccount $account, User $user)
    {
      dd($user->getUserRoles());
        return $user->getRoles()[0] === PermissionRoles::SUPER_ADMIN;
    }
}