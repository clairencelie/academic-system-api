<?php

namespace Clairence\Entity;

class RincianTagihanDb
{
    public function __construct(
        private string $idRincianTagihan,
        private string $idTagihanPerkuliahan,
        private string $item,
        private string $jumlahItem,
        private string $hargaItem,
        private string $totalHargaItem,
    ) {
    }

    static public function createRincianTagihanDb(array $data): RincianTagihanDb
    {
        return new RincianTagihanDb(
            idRincianTagihan: $data['id_rincian_tagihan'],
            idTagihanPerkuliahan: $data['id_pembayaran_kuliah'],
            item: $data['item'],
            jumlahItem: $data['jumlah_item'],
            hargaItem: $data['harga_item'],
            totalHargaItem: $data['total_harga_item'],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
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

    /**
     * Get the value of idRincianTagihan
     */
    public function getIdRincianTagihan(): string
    {
        return $this->idRincianTagihan;
    }
}
