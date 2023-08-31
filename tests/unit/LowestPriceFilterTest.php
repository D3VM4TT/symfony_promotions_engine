<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Tests\ServiceTestCase;
use App\Filter\LowestPriceFilter;

class LowestPriceFilterTest extends ServiceTestCase
{

   /** @test */
    public function lowest_price_promotions_filtering_is_applied_correctly(): void
    {
        // Given
        $lowestPriceFilter = $this->container->get(LowestPriceFilter::class);
        $enquiry = new LowestPriceEnquiry();
        $promotions = $this->promotionsDataProvider();

        // When
        $filteredEnquiry = $lowestPriceFilter->apply($enquiry, ...$promotions);

        // Then
        $this->assertSame(100, $filteredEnquiry->getPrice());
        $this->assertSame(50, $filteredEnquiry->getDiscountedPrice());
        $this->assertSame('Black Friday half price sale!', $filteredEnquiry->getPromotionName());


    }

    public function promotionsDataProvider(): array
    {
        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale!');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(["start_date"=> "2021-11-26", "end_date"=> "2021-11-29"]);
        $promotion->setType('date_range_multiplier');

        $promotion_2 = new Promotion();
        $promotion_2->setName('Voucher OU812');
        $promotion_2->setAdjustment(100);
        $promotion_2->setCriteria(["code"=> "OU812"]);
        $promotion_2->setType('fixed_price_voucher');

        $promotion_3 = new Promotion();
        $promotion_3->setName('Buy one get one free');
        $promotion_3->setAdjustment(0.5);
        $promotion_3->setCriteria(["minimum_quantity"=> 2]);
        $promotion_3->setType('even_items_multiplier');

        return [$promotion, $promotion_2, $promotion_3];

    }

}
