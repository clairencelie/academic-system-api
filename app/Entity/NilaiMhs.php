<?php

namespace Clairence\Entity;

class NilaiMhs
{
    public function __construct(
        private string $id_khs,
        private string $nim,
        private string $id_mata_kuliah,
        private string $id_nilai,
        private int $kehadiran,
        private int $tugas,
        private int $uts,
        private int $uas,
        private string $nilai,
        private int $angka_kualitas,
        private string $status,
        private string $tahun_akademik,
        private string $semester,
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
     * Set the value of nim
     */
    public function setNim(string $nim): self
    {
        $this->nim = $nim;

        return $this;
    }

    /**
     * Get the value of id_mata_kuliah
     */
    public function getIdMataKuliah(): string
    {
        return $this->id_mata_kuliah;
    }

    /**
     * Set the value of id_mata_kuliah
     */
    public function setIdMataKuliah(string $id_mata_kuliah): self
    {
        $this->id_mata_kuliah = $id_mata_kuliah;

        return $this;
    }

    /**
     * Get the value of id_nilai
     */
    public function getIdNilai(): string
    {
        return $this->id_nilai;
    }

    /**
     * Set the value of id_nilai
     */
    public function setIdNilai(string $id_nilai): self
    {
        $this->id_nilai = $id_nilai;

        return $this;
    }

    /**
     * Get the value of kehadiran
     */
    public function getKehadiran(): int
    {
        return $this->kehadiran;
    }

    /**
     * Set the value of kehadiran
     */
    public function setKehadiran(int $kehadiran): self
    {
        $this->kehadiran = $kehadiran;

        return $this;
    }

    /**
     * Get the value of tugas
     */
    public function getTugas(): int
    {
        return $this->tugas;
    }

    /**
     * Set the value of tugas
     */
    public function setTugas(int $tugas): self
    {
        $this->tugas = $tugas;

        return $this;
    }

    /**
     * Get the value of uts
     */
    public function getUts(): int
    {
        return $this->uts;
    }

    /**
     * Set the value of uts
     */
    public function setUts(int $uts): self
    {
        $this->uts = $uts;

        return $this;
    }

    /**
     * Get the value of uas
     */
    public function getUas(): int
    {
        return $this->uas;
    }

    /**
     * Set the value of uas
     */
    public function setUas(int $uas): self
    {
        $this->uas = $uas;

        return $this;
    }

    /**
     * Get the value of nilai
     */
    public function getNilai(): string
    {
        return $this->nilai;
    }

    /**
     * Set the value of nilai
     */
    public function setNilai(int $nilai): self
    {
        $this->nilai = $nilai;

        return $this;
    }

    /**
     * Get the value of angka_kualitas
     */
    public function getAngkaKualitas(): int
    {
        return $this->angka_kualitas;
    }

    /**
     * Set the value of angka_kualitas
     */
    public function setAngkaKualitas(string $angka_kualitas): self
    {
        $this->angka_kualitas = $angka_kualitas;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of tahun_akademik
     */
    public function getTahunAkademik(): string
    {
        return $this->tahun_akademik;
    }

    /**
     * Get the value of semester
     */
    public function getSemester(): string
    {
        return $this->semester;
    }


    /**
     * Get the value of id_khs
     */
    public function getIdKhs(): string
    {
        return $this->id_khs;
    }

    static public function createNilaiMhs(array $data): NilaiMhs
    {
        return new NilaiMhs(
            $data['id_khs'],
            $data['nim'],
            $data['id_mata_kuliah'],
            $data['id_nilai'],
            $data['kehadiran'],
            $data['tugas'],
            $data['uts'],
            $data['uas'],
            $data['nilai'],
            $data['angka_kualitas'],
            $data['status'],
            $data['tahun_akademik'],
            $data['semester']
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
