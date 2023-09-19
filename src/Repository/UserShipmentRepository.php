<?php

namespace App\Repository;

use App\Entity\UserShipment;
use App\Service\CaseConvertService;
use App\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<UserShipment>
 *
 * @method UserShipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserShipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserShipment[]    findAll()
 * @method UserShipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserShipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserShipment::class);
    }

    public function save(UserShipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserShipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByRequest(Request $request): PaginatorService
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 25);
        $sortBy = CaseConvertService::snakeToCamel($request->get('sort_by', 'id'));
        $sortOrder = $request->get('sort_order', 'ASC');
        $userId = $request->get('user_id', null);
        $id = $request->get('id', null);

        $paginator = new PaginatorService();
        $query = $this->createQueryBuilder('userShipment');

        if ($id) {
            $query->andWhere('userShipment.id = :id')
                ->setParameter('id', $id);
        }

        if ($userId){
            $query->leftJoin('userShipment.user', 'user')
                ->andWhere('user.id = :userId')
                ->setParameter('userId', $userId);
        }

        $paginator->setCurrentPage($page)
            ->setQueryBuilder($query)
            ->setPerPage($perPage);

        $items = $query->orderBy('userShipment.'.$sortBy, $sortOrder)
            ->setFirstResult($paginator->getFirstResult())
            ->setMaxResults($paginator->getPerPage())
            ->getQuery()
            ->getResult();

        $paginator->setResult($items);

        return $paginator;
    }
}
