<?php

namespace Clairence\Entity;

class MataKuliahUpdate
{

    public function __construct(
        private string $id_mata_kuliah,
        private string $id_mata_kuliah_master,
        private string $id_dosen,
        private string $nama_mata_kuliah,
        private string $jumlah_sks,
        private string $kelas,
        private string $jenis,
        private string $tahun_akademik,
        private string $semester,
    ) {
    }

    static public function createMataKuliahUpdate(array $data): MataKuliahUpdate
    {
        return new MataKuliahUpdate(
            id_mata_kuliah: $data['id_mata_kuliah'],
            id_mata_kuliah_master: $data['id_mata_kuliah_master'],
            id_dosen: $data['id_dosen'],
            nama_mata_kuliah: $data['nama_mata_kuliah'],
            jumlah_sks: $data['jumlah_sks'],
            kelas: $data['kelas'],
            jenis: $data['jenis'],
            tahun_akademik: $data['tahun_akademik'],
            semester: $data['semester'],
        );
    }

    /**
     * Get the value of id_mata_kuliah_master
     */
    public function getIdMataKuliahMaster(): string
    {
        return $this->id_mata_kuliah_master;
    }

    /**
     * Get the value of id_dosen
     */
    public function getIdDosen(): string
    {
        return $this->id_dosen;
    }

    /**
     * Get the value of nama_mata_kuliah
     */
    public function getNamaMataKuliah(): string
    {
        return $this->nama_mata_kuliah;
    }

    /**
     * Get the value of jumlah_sks
     */
    public function getJumlahSks(): string
    {
        return $this->jumlah_sks;
    }

    /**
     * Get the value of kelas
     */
    public function getKelas(): string
    {
        return $this->kelas;
    }

    /**
     * Get the value of jenis
     */
    public function getJenis(): string
    {
        return $this->jenis;
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
     * Get the value of id_mata_kuliah
     */
    public function getIdMataKuliah(): string
    {
        return $this->id_mata_kuliah;
    }
}
