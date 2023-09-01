<?php

namespace App\DTO;

interface PriceEnquiryInterface extends PromotionEnquiryInterface
{

    public function setPrice(int $price): void;

    public function setDiscountedPrice(int $discountedPrice): void;

    public function getQuantity(): ?int;
}
