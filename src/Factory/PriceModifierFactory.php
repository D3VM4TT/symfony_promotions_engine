<?php

namespace App\Factory;

use App\Filter\Modifier\DateRangeMultiplierPriceModifier;
use App\Filter\Modifier\EvenItemsMultiplierPriceModifier;
use App\Filter\Modifier\FixedPriceVoucherPriceModifier;
use App\Filter\Modifier\PriceModifierInterface;

class PriceModifierFactory
{
    public function create(string $type): PriceModifierInterface
    {
        return match ($type) {
            'date_range_multiplier' => new DateRangeMultiplierPriceModifier(),
            'fixed_price_voucher' => new FixedPriceVoucherPriceModifier(),
            'even_items_multiplier' => new EvenItemsMultiplierPriceModifier(),
            default => throw new \InvalidArgumentException('Invalid promotion type'),
        };
    }

}
