<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class DateRangeMultiplierPriceModifier implements PriceModifierInterface
{
    public function modify(float $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): float
    {
        $totalPrice = $price * $quantity;
        $criteria = $promotion->getCriteria();
        if ($enquiry->getRequestDate() > $criteria['start_date'] && $enquiry->getRequestDate() < $criteria['end_date']) {
            $totalPrice *= $promotion->getAdjustment();
        }
        return $totalPrice;
    }

}
