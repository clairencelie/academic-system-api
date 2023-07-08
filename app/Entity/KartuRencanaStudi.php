<?php

namespace Clairence\Entity;

class KartuRencanaStudi
{
    public function __construct(
        private string $nim,
        private string $id_dosen,
        private string $semester,
        private string $jurusan,
        private string $ips,
        private string $ipk,
        private string $kredit_diambil,
        private string $beban_sks_maks,
        private string $waktu_pengisian,
        private string $tahun_akademik,
    ) {
    }


    /**
     * Get the value of nim
     */
    public function getNim(): string
    {
        return $this->nim;
    }

    /**
     * Get the value of semester
     */
    public function getSemester(): string
    {
        return $this->semester;
    }

    /**
     * Get the value of jurusan
     */
    public function getJurusan(): string
    {
        return $this->jurusan;
    }

    /**
     * Get the value of ips
     */
    public function getIps(): string
    {
        return $this->ips;
    }

    /**
     * Get the value of ipk
     */
    public function getIpk(): string
    {
        return $this->ipk;
    }

    /**
     * Get the value of kredit_diambil
     */
    public function getKreditDiambil(): string
    {
        return $this->kredit_diambil;
    }

    /**
     * Get the value of beban_sks_maks
     */
    public function getBebanSksMaks(): string
    {
        return $this->beban_sks_maks;
    }

    /**
     * Get the value of waktu_pengisian
     */
    public function getWaktuPengisian(): string
    {
        return $this->waktu_pengisian;
    }

    /**
     * Get the value of tahun_akademik
     */
    public function getTahunAkademik(): string
    {
        return $this->tahun_akademik;
    }

    static public function createKRS(array $data): KartuRencanaStudi
    {
        return new KartuRencanaStudi(
            $data['nim'],
            $data['id_dosen'],
            $data['semester'],
            $data['program_studi'],
            $data['ips'],
            $data['ipk'],
            $data['kredit_diambil'],
            $data['beban_sks_maks'],
            $data['waktu_pengisian'],
            $data['tahun_akademik'],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }

        /**
         * Get the value of id_dosen
         */
        public function getIdDosen(): string
        {
                return $this->id_dosen;
        }
}
