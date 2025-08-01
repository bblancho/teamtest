<?php

namespace App\Repository;

use App\Entity\Offres;
use App\Entity\Clients;
use App\Entity\Candidatures;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Candidatures>
 */
class CandidaturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Candidatures::class);
    }

    /**
    * @return Candidatures[] Returns an array of Candidatures objects
    */
    public function candidaturesUser($user): array
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

    public function paginateOffreCandidatures(int $page, Offres $offre): PaginationInterface
    {
        $builder =  $this->createQueryBuilder('c') ;

        if($offre){
            $builder = $builder->andWhere('c.offres= :idOffre')
            ->setParameter('idOffre', $offre) ;
        }

        return  $this->paginator->paginate(
            $builder ,
            $page ,
            10 ,
            [   //securité sur le trie
                'distinct' => false , 
                'sortFieldAllowList' => ['c.id'] //securité sur le trie, on choisit sur quel champs on accorde le trie
            ]
        );
    }

    public function paginateOffreCandidaturesValidee(int $page, Offres $offre): PaginationInterface
    {
        $builder =  $this->createQueryBuilder('c') ;

        if($offre){
            $builder = $builder
            ->andWhere('c.offres= :idOffre')
            ->andWhere('c.isRetenue = true')
            ->setParameter('idOffre', $offre) ;
        }

        return  $this->paginator->paginate(
            $builder ,
            $page ,
            10 ,
            [   //securité sur le trie
                'distinct' => false , 
                'sortFieldAllowList' => ['c.id'] //securité sur le trie, on choisit sur quel champs on accorde le trie
            ]
        );
    }
    

    public function aDejaPostule(Clients $user, Offres $offre)
    {
        return $this->createQueryBuilder(alias: 'c')
            ->andWhere('c.clients = :idClient and c.offres = :idOffre')
            ->setParameter('idClient', $user)
            ->setParameter('idOffre', $offre)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function nbCandidatures(Offres $offre)
    {
        return $this->createQueryBuilder(alias: 'c')
            ->select('count(c.id)')
            ->where('c.offres = :idOffre')
            ->setParameter('idOffre', $offre)
            ->getQuery()
            ->getSingleScalarResult();
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
