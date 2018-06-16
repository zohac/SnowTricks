<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * UserRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Recovers a user by token.
     *
     * @param string $token
     *
     * @return User|null
     */
    public function getUserWithToken(string $token): ?User
    {
        $token = $this->sanitizeToken($token);

        return $this->createQueryBuilder('u')
            ->where('u.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Recovers a user with email.
     *
     * @param string $emailRecovery
     *
     * @return User|null
     */
    public function getUserWithemailRecovery(string $emailRecovery): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :emailRecovery')
            ->setParameter('emailRecovery', $emailRecovery)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * The token The token is a 64 character hexadecimal string.
     *
     * @param string $token
     */
    public function sanitizeToken(string $token)
    {
        if (preg_match('#^[0-9a-f]{64}$#', $token)) {
            return $token;
        }
        // If the token isn't valide
        throw new \LogicException(
            sprintf('Le token fourni n\'est pas valide!')
        );
    }
}
