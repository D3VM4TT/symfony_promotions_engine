<?php

namespace App\Filter\Factory;

use App\Filter\Modifier\DateRangeMultiplierPriceModifier;
use App\Filter\Modifier\EvenItemsMultiplierPriceModifier;
use App\Filter\Modifier\FixedPriceVoucherPriceModifier;
use App\Filter\Modifier\PriceModifierInterface;


interface PriceModifierFactoryInterface
{
    public function create(string $type): PriceModifierInterface;
}
