<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Factory\PriceModifierFactory;
use App\Tests\ServiceTestCase;
use App\Filter\LowestPriceFilter;

class PriceModifierTest extends ServiceTestCase
{

    /** @test */
    public function dateRangeMultiplier_returns_a_correctly_modified_price(): void
    {
        // Given
        $priceModifierFactory = new PriceModifierFactory();
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setRequestDate('2023-08-31');

        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale!');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(["start_date" => "2021-11-26", "end_date" => "2024-11-29"]);
        $promotion->setType('date_range_multiplier');
        $dateRangeMultiplier = $priceModifierFactory->create($promotion->getType());

        // When
        $modifiedPrice = $dateRangeMultiplier->modify(100, 5, $promotion, $enquiry);

        // Then
        $this->assertSame(floatval(250), $modifiedPrice);
    }

}
