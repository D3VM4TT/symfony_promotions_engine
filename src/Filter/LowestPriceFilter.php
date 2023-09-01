<?php

namespace App\Filter;

use App\DTO\PriceEnquiryInterface;
use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;
use App\Filter\Factory\PriceModifierFactoryInterface;


class LowestPriceFilter implements PriceFilterInterface
{


    public function __construct(private PriceModifierFactoryInterface $priceModifierFactory)
    {
    }

    public function apply(PriceEnquiryInterface $enquiry, Promotion ...$promotions): PromotionEnquiryInterface
    {
        $price = $enquiry->getProduct()->getPrice();
        $enquiry->setPrice(100);
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $price * $quantity;

        foreach ($promotions as $promotion) {
            $priceModifier = $this->priceModifierFactory->create($promotion->getType());
            $modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);
            if ($modifiedPrice < $lowestPrice) {
                $lowestPrice = $modifiedPrice;
                $enquiry->setDiscountedPrice($lowestPrice);
                $enquiry->setPromotionId($promotion->getId());
                $enquiry->setPromotionName($promotion->getName());
            }
        }


        return $enquiry;
    }
}
