<?php

namespace App\Repository;

use App\Entity\Candidatures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidatures>
 */
class CandidaturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidatures::class);
    }

    /**
    * @return Candidatures[] Returns an array of Candidatures objects
    */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder(alias: 'c')
            ->andWhere('c.clients = :idClient')
            ->setParameter('idClient', $user)
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }

    public function userAsPostule($user, $offre): bool
    {
        return $this->createQueryBuilder(alias: 'c')
            ->andWhere('c.clients = :idClient and c.offres = :idOffre')
            ->setParameters([
                'idClient' => $user,
                'idOffre' => $offre,
            ])
            ->setMaxResults(50)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Candidatures
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
