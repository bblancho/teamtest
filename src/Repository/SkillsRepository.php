<?php

namespace App\Repository;

use App\Entity\Skills;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Skills>
 */
class SkillsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Skills::class);
    }

    /**
     *  Get published missions  
    */
    public function paginateOffres(int $page,): PaginationInterface
    {
        $builder =  
            $this->createQueryBuilder('s') 
        ;

        $builder = $builder
            ->getQuery()
            ->getResult()
        ;

        return  $this->paginator->paginate(
            $builder ,
            $page ,
            9 ,
            [   //securité sur le trie
                'distinct' => false , 
                'sortFieldAllowList' => ['o.id'] //securité sur le trie, on choisit sur quel champs on accorde le trie
            ]
        );
    }

    //    /**
    //     * @return Skills[] Returns an array of Skills objects
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

    //    public function findOneBySomeField($value): ?Skills
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
