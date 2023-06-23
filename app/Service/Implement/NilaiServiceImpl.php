<?php

namespace Clairence\Service\Implement;

use Clairence\Repository\Implement\NilaiRepositoryImpl;
use Clairence\Service\Interface\NilaiServiceInterface;

class NilaiServiceImpl implements NilaiServiceInterface
{

    public function __construct(private NilaiRepositoryImpl $repository)
    {
    }

    public function getMataKuliah(string $id_dosen): array
    {
        return $this->repository->getMataKuliah($id_dosen);
    }

    public function getDaftarNilai(string $id_mata_kuliah, string $tahun_akademik, string $semester): array
    {
        return $this->repository->getDaftarNilai($id_mata_kuliah, $tahun_akademik, $semester);
    }

    public function getTahunAkademik(): array
    {
        return $this->repository->getTahunAkademik();
    }

    public function updateNilai(
        string $id_nilai,
        int $kehadiran,
        int $tugas,
        int $uts,
        int $uas,
        string $nilai,
        int $kualitas,
        string $status,
    ): bool {
        return $this->repository->updateNilai(
            $id_nilai,
            $kehadiran,
            $tugas,
            $uts,
            $uas,
            $nilai,
            $kualitas,
            $status,
        );
    }
}
