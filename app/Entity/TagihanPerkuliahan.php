<?php

namespace Clairence\Entity;

class TagihanPerkuliahan
{
    public function __construct(
        private string $idPembayaranKuliah,
        private string $nim,
        private string $totalTagihan,
        private string $sisaPembayaran,
        private string $statusPembayaran,
        private string $kategori,
        private string $metodePembayaran,
        private string $tahunAkademik,
        private string $semester,
    ) {
    }

    static public function createTagihanPerkuliahan(array $data): TagihanPerkuliahan
    {
        return new TagihanPerkuliahan(
            idPembayaranKuliah: $data['idPembayaranKuliah'],
            nim: $data['nim'],
            totalTagihan: $data['totalTagihan'],
            sisaPembayaran: $data['sisaPembayaran'],
            statusPembayaran: $data['statusPembayaran'],
            kategori: $data['kategori'],
            metodePembayaran: $data['metodePembayaran'],
            tahunAkademik: $data['tahunAkademik'],
            semester: $data['semester'],
        );
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
     * Get the value of metodePembayaran
     */
    public function getMetodePembayaran(): string
    {
        return $this->metodePembayaran;
    }

    /**
     * Get the value of idPembayaranKuliah
     */
    public function getIdPembayaranKuliah(): string
    {
        return $this->idPembayaranKuliah;
    }
}
