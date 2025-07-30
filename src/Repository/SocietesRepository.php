<?php

namespace App\Repository;

use App\Entity\Societes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Societes>
 */
class SocietesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Societes::class);
    }
    
    public function paginateSocietes(int $page): PaginationInterface
    {
        $builder =  $this->createQueryBuilder('s') ;

        $builder = $builder
            ->andWhere('s.isVerified = true')
        ;
        
        return  $this->paginator->paginate(
            $builder ,
            $page ,
            10 ,
            [   //securité sur le trie
                'distinct' => false , 
                'sortFieldAllowList' => ['o.id'] //securité sur le trie, on choisit sur quel champs on accorde le trie
            ]
        );
    }

    //    /**
    //     * @return Societes[] Returns an array of Societes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Societes
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
