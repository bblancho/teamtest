<?php

namespace App\Repository;

use App\Entity\Offres;
use App\Entity\Clients;
use App\Entity\Candidatures;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function aDejaPostule(Clients $user, Offres $offre)
    {
        return $this->createQueryBuilder(alias: 'c')
            ->andWhere('c.clients = :idClient and c.offres = :idOffre')
            ->setParameter('idClient', $user)
            ->setParameter('idOffre', $offre)
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
