<?php

namespace Clairence\Repository\Interface;

interface TahunAkademikRepositoryInterface
{
    public function setTahunAkademik(string $tahunAkademik, string $semester): bool;
    
    public function setJadwalKrs(string $tanggalMulai, string $tanggalSelesai): bool;

    public function tambahSemesterMhs(): bool;
}
