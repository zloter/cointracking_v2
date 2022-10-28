<?php

namespace App\Types;

class Transaction
{
    public function __construct(
        public int $time,
        public int $type,
        public ?float $buy,
        public ?string $buyCoin,
        public ?float $sell,
        public ?string $sellCoin,
        public ?float $fee,
        public ?string $feeCoin,
        public ?string $comment,
        public ?string $buyOrigin,
        public ?string $sellOrigin
    ) {}
}
