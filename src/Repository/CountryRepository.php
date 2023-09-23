<?php

namespace App\Repository;

use App\Entity\Country;
use App\Service\CaseConvertService;
use App\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Country>
 *
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function save(Country $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Country $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByRequest(Request $request): array
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 25);
        $sortBy = CaseConvertService::snakeToCamel($request->get('sort_by', 'id'));
        $sortOrder = $request->get('sort_order', 'ASC');
        $title = $request->get('title', null);
        $region = $request->get('region', null);
        $id = $request->get('id', null);

        $query = $this->createQueryBuilder('country');

        if ($id) {
            $query->andWhere('country.id = :id')
                ->setParameter('id', $id);
        }

        if ($region) {
            $query->andWhere('country.region = :regionId')
                ->setParameter('regionId', $region);
        }

        if ($title){
            $query->andWhere('country.title LIKE :title')
                ->setParameter('title', '%'.$title.'%');
        }

        return $query->orderBy('country.'.$sortBy, $sortOrder)
            ->getQuery()
            ->getResult();
    }
}
