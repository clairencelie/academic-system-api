<?php

namespace Clairence\Entity;

class TagihanPerkuliahanDb
{
    public function __construct(
        private string $idTagihanPerkuliahan,
        private string $nim,
        private string $totalTagihan,
        private string $sisaPembayaran,
        private string $statusPembayaran,
        private string $metodePembayaran,
        private string $kategori,
        private string $tahunAkademik,
        private string $semester,
    ) {
    }

    static public function createTagihanPerkuliahanDb(array $data): TagihanPerkuliahanDb
    {
        return new TagihanPerkuliahanDb(
            idTagihanPerkuliahan: $data['id_pembayaran_kuliah'],
            nim: $data['nim'],
            totalTagihan: $data['total_tagihan'],
            sisaPembayaran: $data['sisa_pembayaran'],
            statusPembayaran: $data['status_pembayaran'],
            metodePembayaran: $data['metode_pembayaran'],
            kategori: $data['kategori'],
            tahunAkademik: $data['tahun_akademik'],
            semester: $data['semester'],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**
     * Get the value of nim
     */
    public function getNim(): string
    {
        return $this->nim;
    }

    /**
     * Get the value of totalTagihan
     */
    public function getTotalTagihan(): string
    {
        return $this->totalTagihan;
    }

    /**
     * Get the value of sisaPembayaran
     */
    public function getSisaPembayaran(): string
    {
        return $this->sisaPembayaran;
    }

    /**
     * Get the value of statusPembayaran
     */
    public function getStatusPembayaran(): string
    {
        return $this->statusPembayaran;
    }

    /**
     * Get the value of kategori
     */
    public function getKategori(): string
    {
        return $this->kategori;
    }

    /**
     * Get the value of tahunAkademik
     */
    public function getTahunAkademik(): string
    {
        return $this->tahunAkademik;
    }

    /**
     * Get the value of semester
     */
    public function getSemester(): string
    {
        return $this->semester;
    }

    /**
     * Get the value of idTagihanPerkuliahan
     */
    public function getIdTagihanPerkuliahan(): string
    {
        return $this->idTagihanPerkuliahan;
    }
}
