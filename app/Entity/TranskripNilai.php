<?php

namespace Clairence\Entity;

class TranskripNilai
{
    public function __construct(
        private string $id_transkrip_nilai,
        private string $nim,
        private string $program_studi,
        private string $ipk,
        private string $total_kredit_diambil,
        private string $total_kredit_diperoleh,
    ) {
    }

    /**
     * Get the value of id_transkrip_nilai
     */
    public function getIdTranskripNilai(): string
    {
        return $this->id_transkrip_nilai;
    }

    /**
     * Get the value of nim
     */
    public function getNim(): string
    {
        return $this->nim;
    }

    /**
     * Get the value of program_studi
     */
    public function getProgramStudi(): string
    {
        return $this->program_studi;
    }

    /**
     * Get the value of ipk
     */
    public function getIpk(): string
    {
        return $this->ipk;
    }

    /**
     * Get the value of total_kredit_diambil
     */
    public function getTotalKreditDiambil(): string
    {
        return $this->total_kredit_diambil;
    }

    /**
     * Get the value of total_kredit_diperoleh
     */
    public function getTotalKreditDiperoleh(): string
    {
        return $this->total_kredit_diperoleh;
    }

    static public function createTranskrip(array $data): TranskripNilai
    {
        return new TranskripNilai(
            $data['id_transkrip_nilai'],
            $data['nim'],
            $data['program_studi'],
            $data['ipk'],
            $data['total_kredit_diambil'],
            $data['total_kredit_diperoleh'],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
