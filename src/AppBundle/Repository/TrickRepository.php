<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Trick;

/**
 * TrickRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrickRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Return the list of trick with all associated entities.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function findAllWithAllEntities(int $limit = 12, int $offset = 0): array
    {
        // Recovers the current user via the token sent by email.
        $response = $this->createQueryBuilder('t')
            ->leftJoin('t.pictures', 'p')
            ->addSelect('p')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        return array_slice($response, $offset, $limit);
    }

    /**
     * Return a trick with all associated entities.
     *
     * @param string $slug
     *
     * @return Trick|null
     */
    public function findWithAllEntities(string $slug): ?Trick
    {
        $slug = $this->sanitizeSlug($slug);

        return $this->createQueryBuilder('t')
            ->leftJoin('t.user', 'u')
            ->addSelect('u')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Validate a slug.
     *
     * @param string $slug
     *
     * @return string
     */
    public function sanitizeSlug(string $slug): string
    {
        if (preg_match('#^[A-Za-z0-9-]{1,}$#', $slug)) {
            return $slug;
        }
        // If the slug isn't valide
        throw new \LogicException(
            sprintf('Le slug fourni n\'est pas valide!')
        );
    }
}
