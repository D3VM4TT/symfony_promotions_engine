<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class EvenItemsMultiplierPriceModifier implements PriceModifierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {
        if ($quantity < $promotion->getCriteria()['minimum_quantity']) {
            return $price;
        }

        $remainder = $quantity % 2;

        $numberOfItemsToDiscount = $quantity - $remainder;
        $totalOfItemsToDiscount = $numberOfItemsToDiscount * $price;
        return ($totalOfItemsToDiscount * $promotion->getAdjustment()) + ($remainder * $price);
    }


}
