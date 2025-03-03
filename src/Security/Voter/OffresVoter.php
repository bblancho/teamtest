<?php

namespace App\Security\Voter;

use App\Entity\Offres;
use App\Entity\Societes;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class OffresVoter extends Voter
{
    public const OFFRE_EDIT = 'offre_edit';
    public const OFFRE_DELETE = 'offre_delete';
    public const OFFRE_VIEW = 'offre_view';
    public const OFFRE_CREATE = 'offre_create';
    public const OFFRE_SOCIETE = 'offre_societe';
    public const OFFRE_ALL = 'offre_all';

    protected function supports(string $attribute, mixed $offre): bool
    {
        return
            in_array( $attribute, [self::OFFRE_CREATE, self::OFFRE_ALL, self::OFFRE_SOCIETE] )  ||
            ( 
                in_array($attribute, [self::OFFRE_EDIT, self::OFFRE_DELETE, self::OFFRE_VIEW] ) && $offre instanceof \App\Entity\Offres 
            );
    }

    /**
     * @param string $attribute
     * @param Offres|null $offre
     * @param TokenInterface $token
     * 
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $offre, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof Societes) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::OFFRE_DELETE:
            case self::OFFRE_VIEW:
            case self::OFFRE_EDIT:
                return $this->canEdit($offre, $user) ;
                break;

            case self::OFFRE_CREATE:
            case self::OFFRE_SOCIETE:
            case self::OFFRE_ALL:
                return true ;
                break;
        }

        return false;
    }

    private function canEdit(Offres $offre, Societes $societe){
        return $societe === $offre->getSocietes();
    }    

}
