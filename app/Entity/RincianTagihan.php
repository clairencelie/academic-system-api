<?php

namespace Clairence\Entity;

class RincianTagihan
{
    public function __construct(
        private string $idTagihanPerkuliahan,
        private string $item,
        private string $jumlahItem,
        private string $hargaItem,
        private string $totalHargaItem,
    ) {
    }

    static public function createRincianTagihan(array $data): RincianTagihan
    {
        return new RincianTagihan(
            idTagihanPerkuliahan: $data['idTagihanPerkuliahan'],
            item: $data['item'],
            jumlahItem: $data['jumlahItem'],
            hargaItem: $data['hargaItem'],
            totalHargaItem: $data['totalHargaItem'],
        );
    }

    /**
     * Get the value of idTagihanPerkuliahan
     */
    public function getIdTagihanPerkuliahan(): string
    {
        return $this->idTagihanPerkuliahan;
    }

    /**
     * Get the value of item
     */
    public function getItem(): string
    {
        return $this->item;
    }

    /**
     * Get the value of jumlahItem
     */
    public function getJumlahItem(): string
    {
        return $this->jumlahItem;
    }

    /**
     * Get the value of hargaItem
     */
    public function getHargaItem(): string
    {
        return $this->hargaItem;
    }

    /**
     * Get the value of totalHargaItem
     */
    public function getTotalHargaItem(): string
    {
        return $this->totalHargaItem;
    }
}
