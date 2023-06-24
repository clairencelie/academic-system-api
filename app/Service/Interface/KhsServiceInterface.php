<?php

namespace Clairence\Service\Interface;

use Clairence\Entity\KartuHasilStudi;
use Clairence\Entity\NilaiMhs;
use Clairence\Entity\TranskripNilai;

interface KhsServiceInterface
{
    public function createTranskrip(string $id, TranskripNilai $transkrip): bool;

    public function updateTranskrip(string $id_transkrip): bool;

    public function updateKhs(string $id_khs): bool;

    public function updateBebanMaksSksKhs(string $id_khs, string $nim): bool;

    public function createKhs(string $id_transkrip, KartuHasilStudi $khs): bool;

    public function getTranskrip(string $nim): array;

    public function getTranskripPerSemester(string $nim): array;

    public function getKhs(string $id_transkrip, string $semester): ?KartuHasilStudi;

    public function insertRincianKhs(string $id_khs, string $id_nilai): bool;

    public function insertNilai(NilaiMhs $nilai_mhs): bool;

    public function getNilai(string $nim, string $tahun_akademik, string $semester): array;
}
