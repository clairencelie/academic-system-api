<?php

namespace Clairence\Service\Implement;

use Clairence\Repository\Implement\TahunAkademikRepositoryImpl;
use Clairence\Service\Interface\TahunAkademikServiceInterface;

class TahunAkademikServiceImpl implements TahunAkademikServiceInterface
{

    public function __construct(private TahunAkademikRepositoryImpl $repository) {
    }

    public function setTahunAkademik(string $tahunAkademik, string $semester): bool
    {
        return $this->repository->setTahunAkademik($tahunAkademik, $semester);
    }

    public function setJadwalKrs(string $tanggalMulai, string $tanggalSelesai): bool
    {
        return $this->repository->setJadwalKrs($tanggalMulai, $tanggalSelesai);
    }

    public function tambahSemesterMhs(): bool
    {
        return $this->repository->tambahSemesterMhs();
    }
}
