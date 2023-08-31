<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

interface PriceModifierInterface
{

    public function modify(float $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): float;

}
