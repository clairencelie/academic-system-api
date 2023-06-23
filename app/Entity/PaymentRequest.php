<?php

namespace Clairence\Entity;

class PaymentRequest
{
    public function __construct(
        private string $order_id,
        private string $gross_amount,
    ) {
    }

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function setOrderId(string $order_id): void
    {
        $this->order_id = $order_id;
    }

    public function getGrossAmount(): string
    {
        return $this->gross_amount;
    }

    public function setGrossAmount(string $gross_amount): void
    {
        $this->gross_amount = $gross_amount;
    }
}
