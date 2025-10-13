<?php

namespace App\Repository;

use App\Entity\Offres;
use App\Model\SearchModel;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Offres>
 */
class OffresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Offres::class);
    }
    
    /**
     *  Get published missions  
    */
    public function paginateOffres(int $page, ?int $userId): PaginationInterface
    {
        $builder =  
            $this->createQueryBuilder('o') 
            ->andWhere('o.isActive = true')
            ->andWhere('o.isArchive = false')
        ;

        if($userId){
            $builder = 
                $builder
                ->andWhere('o.societes = :user')
                ->orderBy('o.id', 'DESC')
                ->setParameter('user', $userId) 
            ;
        }

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

    /**
     *  Get published missions thanks to archives
    */
    public function paginateOffresArchives(int $page, ?int $userId): PaginationInterface
    {
        $builder =  $this->createQueryBuilder('o') ;

        if($userId){
            $builder = 
                $builder->andWhere('o.societes = :user')
                ->setParameter('user', $userId) 
                ->orderBy('o.id', 'DESC')
                ->andWhere('o.isArchive = true')
                ->andWhere('o.isActive = false')
            ;
        }

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

    /**
     * Get published missions thanks to research Data value
    */
    public function findBySearch(SearchModel $searchdata): PaginationInterface
    {
        $builder =  
            $this->createQueryBuilder('o') 
            ->andWhere('o.isActive = true')
            ->andWhere('o.isArchive = false')
            ->orderBy('o.id', 'DESC')
        ;

        if( !empty($searchdata->query) )
        {
            $builder = $builder
                ->andWhere('o.nom LIKE :query')
                ->setParameter('query', "%{$searchdata->query}%" ) 
                ->orWhere('o.description LIKE :q')
                ->setParameter('q', "%{$searchdata->query}%" ) 
            ;
        }

        $data = $builder
            ->getQuery()
            ->getResult()
        ;

        return  $this->paginator->paginate(
            $data ,
            $searchdata->page ,
            9 ,
            [   //securité sur le trie
                'distinct' => false , 
                // 'sortFieldAllowList' => ['o.id'] //securité sur le trie, on choisit sur quel champs on accorde le trie
            ]
        );
    }

/**
*
* @return Offres[]
*/
public function offreIsPublish(): array
{
	return $this->createQueryBuilder('o')
        ->where('o.isActive = 1')
        ->orderBy('o.id', 'DESC')
        ->getQuery() // On génère un objet Query
        ->getResult() ;
}


/**
*
* @return Offres[]
*/
public function offreNotPublish(): array
{

	return $this->createQueryBuilder('o')
        ->where('o.isActive = 0')
        ->orderBy('o.id', 'DESC')
        ->getQuery() // On génère un objet Query
        ->getResult() ;
}


//    /**
//     * @return Offres[] Returns an array of Offres objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offres
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



}
