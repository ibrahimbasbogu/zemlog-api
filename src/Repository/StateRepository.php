<?php

namespace App\Repository;

use App\Entity\State;
use App\Service\CaseConvertService;
use App\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<State>
 *
 * @method State|null find($id, $lockMode = null, $lockVersion = null)
 * @method State|null findOneBy(array $criteria, array $orderBy = null)
 * @method State[]    findAll()
 * @method State[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }

    public function findByRequest(Request $request): PaginatorService
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 25);
        $sortBy = CaseConvertService::snakeToCamel($request->get('sort_by', 'id'));
        $sortOrder = $request->get('sort_order', 'ASC');
        $title = $request->get('title', null);
        $countryId = $request->get('country_id', null);
        $id = $request->get('id', null);

        $paginator = new PaginatorService();
        $query = $this->createQueryBuilder('state');

        if ($id) {
            $query->andWhere('state.id = :id')
                ->setParameter('id', $id);
        }

        if ($countryId){
            $query->andWhere('state.country = :countryId')
                ->setParameter('countryId', $countryId);
        }

        if ($title){
            $query->andWhere('state.title LIKE :title')
                ->setParameter('title', '%'.$title.'%');
        }

        $paginator->setCurrentPage($page)
            ->setQueryBuilder($query)
            ->setPerPage($perPage);

        $items = $query->orderBy('state.'.$sortBy, $sortOrder)
            ->setFirstResult($paginator->getFirstResult())
            ->setMaxResults($paginator->getPerPage())
            ->getQuery()
            ->getResult();

        $paginator->setResult($items);

        return $paginator;
    }

//    /**
//     * @return State[] Returns an array of State objects
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

//    public function findOneBySomeField($value): ?State
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
