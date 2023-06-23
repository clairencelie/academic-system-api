<?php

namespace Clairence\Repository\Interface;

use Clairence\Entity\KartuHasilStudi;
use Clairence\Entity\NilaiMhs;
use Clairence\Entity\TranskripNilai;

interface KhsRepositoryInterface
{
    public function createTranskrip(string $id, TranskripNilai $transkrip): bool;

    public function updateTranskrip(string $id_transkrip, float $ipk, int $total_kredit_diambil, int $total_kredit_diperoleh): bool;

    public function updateKhs(string $id_khs, float $ips, int $kredit_diperoleh): bool;

    public function getNilaiByKhsId(string $id_khs): array;

    public function updateBebanMaksSksKhs(string $id_khs, int $maks_sks_smt_slnjt): bool;

    public function createKhs(string $id_transkrip, KartuHasilStudi $khs): bool;

    public function getTranskrip(string $id): array;

    public function getKhsById(string $id_khs): array;

    public function getKhs(string $id, string $semester): ?KartuHasilStudi;

    public function insertRincianKhs(string $id_khs, string $id_nilai): bool;

    public function insertNilai(NilaiMhs $nilai_mhs): bool;

    public function getNilai(string $nim, string $tahun_akademik, string $semester): array;
}
