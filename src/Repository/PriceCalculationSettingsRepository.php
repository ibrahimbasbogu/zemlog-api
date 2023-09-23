<?php

namespace App\Repository;

use App\Entity\PriceCalculationSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PriceCalculationSettings>
 *
 * @method PriceCalculationSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceCalculationSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceCalculationSettings[]    findAll()
 * @method PriceCalculationSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceCalculationSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceCalculationSettings::class);
    }

//    /**
//     * @return PriceCalculationSettings[] Returns an array of PriceCalculationSettings objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PriceCalculationSettings
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
