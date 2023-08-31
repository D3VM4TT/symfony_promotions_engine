<?php

namespace App\Filter;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class LowestPriceFilter implements PromotionsFilterInterface
{
    public function apply(PromotionEnquiryInterface $enquiry, Promotion ...$promotions): PromotionEnquiryInterface
    {

        // TODO: Create a PriceEnquiryInterface that the LowestPriceEnquiry implements

        $price = $enquiry->getProduct()->getPrice(); // TODO: Check if the enquiry has a product
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $price * $quantity;

        // Create a factory that fetches the priceModifier class based on the promotion type
        //   - e.g. date_range_multiplier => DateRangeMultiplierPriceModifier
        //   - e.g. fixed_price_voucher => FixedPriceVoucherPriceModifier
        //   - e.g. even_items_multiplier => EvenItemsMultiplierPriceModifier



        /**
         * Loop over the promotions
         *   Run the promotions modification logic against the query
         *   1. Check if the promotion is applicable e.g. is it in date range, does it have a valid code, is the quantity high enough
         *   2. Apply the price modification to obtain a $modifiedPrice (how?)
         *   3. Check IF the $modifiedPrice < $lowestPrice
         *       1. Save to Enquiry properties
         *       2. Update $lowestPrice
         */

//        foreach ($promotions as $promotion) {
//            $priceModifier = $this->priceModifierFactory->create($promotion->getType());
//            $modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);
//            ($modifiedPrice < $lowestPrice) && $lowestPrice = $modifiedPrice;
//        }



        $enquiry->setDiscountedPrice(250);
        $enquiry->setPrice(100);
        $enquiry->setPromotionId(3);
        $enquiry->setPromotionName('Black Friday half price sale!');

        return $enquiry;
    }
}
