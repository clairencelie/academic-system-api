<?php

namespace Clairence\Entity;

class KartuHasilStudi
{
    public function __construct(
        private string $id_khs,
        private string $id_transkrip_nilai,
        private string $semester,
        private string $ips,
        private string $kredit_diambil,
        private string $kredit_diperoleh,
        private string $maks_sks_smt_slnjt,
        private string $tahun_akademik,
    ) {
    }

    /**
     * Get the value of id_khs
     */
    public function getIdKhs(): string
    {
        return $this->id_khs;
    }

    /**
     * Get the value of id_transkrip_nilai
     */
    public function getIdTranskripNilai(): string
    {
        return $this->id_transkrip_nilai;
    }

    /**
     * Get the value of semester
     */
    public function getSemester(): string
    {
        return $this->semester;
    }

    /**
     * Get the value of ips
     */
    public function getIps(): string
    {
        return $this->ips;
    }

    /**
     * Get the value of kredit_diambil
     */
    public function getKreditDiambil(): string
    {
        return $this->kredit_diambil;
    }

    /**
     * Get the value of kredit_diperoleh
     */
    public function getKreditDiperoleh(): string
    {
        return $this->kredit_diperoleh;
    }

    /**
     * Get the value of maks_sks_smt_slnjt
     */
    public function getMaksSksSmtSlnjt(): string
    {
        return $this->maks_sks_smt_slnjt;
    }

    /**
     * Get the value of tahun_akademik
     */
    public function getTahunAkademik(): string
    {
        return $this->tahun_akademik;
    }

    static public function createKHS(array $data): KartuHasilStudi
    {
        return new KartuHasilStudi(
            $data['id_khs'],
            $data['id_transkrip_nilai'],
            $data['semester'],
            $data['ips'],
            $data['kredit_diambil'],
            $data['kredit_diperoleh'],
            $data['maks_sks_smt_slnjt'],
            $data['tahun_akademik'],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
