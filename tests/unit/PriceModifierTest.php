<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Factory\PriceModifierFactory;
use App\Tests\ServiceTestCase;

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
        $this->assertSame(250, $modifiedPrice);
    }

    /** @test */
    public function fixedPriceVoucherModifier_returns_a_correctly_modified_price(): void
    {
        // Given
        $priceModifierFactory = new PriceModifierFactory();
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setRequestDate('2023-08-31');
        $enquiry->setQuantity(4);
        $enquiry->setVoucherCode('OU812');

        $promotion = new Promotion();
        $promotion->setName('Voucher OU812');
        $promotion->setAdjustment(100);
        $promotion->setCriteria(["code" => "OU812"]);
        $promotion->setType('fixed_price_voucher');

        $priceModifier = $priceModifierFactory->create($promotion->getType());

        // When
        $modifiedPrice = $priceModifier->modify(300, $enquiry->getQuantity(), $promotion, $enquiry);

        // Then
        $this->assertSame(400, $modifiedPrice);
    }

    /** @test */
    public function evenItemsMultiplier_returns_a_correctly_modified_price(): void
    {
        // Given
        $priceModifierFactory = new PriceModifierFactory();
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setRequestDate('2023-08-31');
        $enquiry->setQuantity(5);
        $enquiry->setVoucherCode('OU812');

        $promotion = new Promotion();
        $promotion->setName('Buy one get one free');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(["minimum_quantity" => 2]);
        $promotion->setType('even_items_multiplier');

        $priceModifier = $priceModifierFactory->create($promotion->getType());

        // When
        $modifiedPrice = $priceModifier->modify(100, $enquiry->getQuantity(), $promotion, $enquiry);

        // Then
        $this->assertSame(300, $modifiedPrice);
    }


    /** @test */
    public function evenItemsMultiplier_correctly_calculates_buy_one_get_one_half_price(): void
    {
        // Given
        $priceModifierFactory = new PriceModifierFactory();
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setRequestDate('2023-08-31');
        $enquiry->setQuantity(5);
        $enquiry->setVoucherCode('OU812');

        $promotion = new Promotion();
        $promotion->setName('Buy one get one half price');
        $promotion->setAdjustment(0.75);
        $promotion->setCriteria(["minimum_quantity" => 2]);
        $promotion->setType('even_items_multiplier');

        $priceModifier = $priceModifierFactory->create($promotion->getType());

        // When
        $modifiedPrice = $priceModifier->modify(100, $enquiry->getQuantity(), $promotion, $enquiry);

        // Then
        $this->assertSame(400, $modifiedPrice);
    }
}
