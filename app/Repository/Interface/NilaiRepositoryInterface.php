<?php

namespace Clairence\Repository\Interface;

interface NilaiRepositoryInterface
{
    public function getMataKuliah(string $id_dosen): array;

    public function getDaftarNilai(string $id_mata_kuliah, string $tahun_akademik, string $semester): array;

    public function getTahunAkademik(): array;

    public function updateNilai(
        string $id_nilai,
        int $kehadiran,
        int $tugas,
        int $uts,
        int $uas,
        string $nilai,
        int $kualitas,
        string $status,
    ): bool;
}
