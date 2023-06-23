<?php

namespace Clairence\Service\Interface;

interface TahunAkademikServiceInterface
{
    public function setTahunAkademik(string $tahunAkademik, string $semester): bool;

    public function setJadwalKrs(string $tanggalMulai, string $tanggalSelesai): bool;

    public function tambahSemesterMhs(): bool;
}
