<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute == 'POST_CREATE' || (in_array($attribute, ['OWNER']) 
            && $subject instanceof \App\Entity\Post);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST_CREATE':
                #The current should have a verified email before posting
                return $user->isVerified();
            case 'OWNER':
                #Only the owner can edit or delete the post
                return $user->isVerified() && $user == $subject->getUser();
        }

        return false;
    }
}
