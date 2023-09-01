<?php

namespace App\Cache;

use App\Entity\Product;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PromotionsCache
{


    public function __construct(
        private EntityManagerInterface $entityManager,
        private CacheInterface $cache
    ) {
    }

    public function findValidForProduct(Product $product, \DateTime $requestDate): ?array
    {
        $cacheKey = 'product_promotions_' . $product->getId() . '_' . $requestDate->format('Y-m-d');

        $productPromotions = $this->cache->get($cacheKey, function(ItemInterface $item) use ($product, $requestDate) {
            return $this->entityManager->getRepository(Promotion::class)->findValidForProduct(
                $product,
                $requestDate
            );
        });

        return $productPromotions;
    }

}
