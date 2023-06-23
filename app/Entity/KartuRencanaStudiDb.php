<?php

namespace Clairence\Entity;

class KartuRencanaStudiDB
{
    public function __construct(
        private string $id,
        private string $nim,
        private string $nama,
        private string $semester,
        private string $jurusan,
        private string $ips,
        private string $ipk,
        private string $kredit_diambil,
        private string $beban_sks_maks,
        private string $waktu_pengisian,
        private string $tahun_akademik,
        private string $commit,
        private array $pilihan_mata_kuliah = [],
    ) {
    }

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
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

    /**
     * Get the value of pilihan_mata_kuliah
     */
    public function getPilihanMataKuliah(): array
    {
        return $this->pilihan_mata_kuliah;
    }

    /**
     * Set the value of pilihan_mata_kuliah
     */
    public function setPilihanMataKuliah(array $pilihan_mata_kuliah): self
    {
        $this->pilihan_mata_kuliah = $pilihan_mata_kuliah;

        return $this;
    }

    /**
         * Get the value of commit
         */
        public function getCommit(): string
        {
                return $this->commit;
        }

    static public function createKRS(array $data): KartuRencanaStudiDB
    {
        return new KartuRencanaStudiDB(
            $data['id_krs'],
            $data['nim'],
            $data['nama'],
            $data['semester'],
            $data['program_studi'],
            $data['ips'],
            $data['ipk'],
            $data['kredit_diambil'],
            $data['beban_sks_maks'],
            $data['waktu_pengisian'],
            $data['tahun_akademik'],
            $data['commit'],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }

        

        /**
         * Get the value of nama
         */
        public function getNama(): string
        {
                return $this->nama;
        }
}
