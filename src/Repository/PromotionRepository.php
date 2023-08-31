<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductPromotion;
use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promotion>
 *
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    public function findValidForProduct(Product $product, \DateTimeInterface $requestDate)
    {
        // get all the valid promotions for a product
        // - valid from date is before request date
        // - valid to date is after request date

        return $this->createQueryBuilder('promotion')
            ->innerJoin('promotion.productPromotions', 'productPromotion')
            ->where('productPromotion.product = :productId')
            ->andWhere('productPromotion.validTo >= :requestDate')
            ->setParameter('productId', $product->getId())
            ->setParameter('requestDate', $requestDate)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Promotion[] Returns an array of Promotion objects
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

//    public function findOneBySomeField($value): ?Promotion
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
