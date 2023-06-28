<?php

namespace Clairence\Entity;

class ItemDetail
{
    private string $name;
    private int $price;
    private int $quantity;

    static public function createItemDetail(array $data): ItemDetail
    {

        return new ItemDetail(
            $data['item'],
            $data['hargaItem'],
            $data['jumlahItem'],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**
     * Get the value of item
     */
    public function getItem(): string
    {
        return $this->name;
    }

    /**
     * Get the value of price
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Get the value of quantity
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
