<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class FixedPriceVoucherPriceModifier implements PriceModifierInterface
{
    public function modify(float $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): float
    {
        // TODO: Implement modify() method.
    }

}
