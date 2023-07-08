<?php

namespace Clairence\Service\Implement;

use Clairence\Entity\KartuHasilStudi;
use Clairence\Entity\KartuRencanaStudi;
use Clairence\Entity\Student;
use Clairence\Entity\TranskripNilai;
use Clairence\Repository\Implement\KhsRepositoryImpl;
use Clairence\Repository\Implement\KrsRepositoryImpl;
use Clairence\Repository\Implement\UserRepositoryImpl;
use Clairence\Service\Interface\KrsServiceInterface;

class KrsServiceImpl implements KrsServiceInterface
{

    public function __construct(
        private KrsRepositoryImpl $krs_repository,
    ) {
    }

    public function getKrsSchedule(): array
    {
        return $this->krs_repository->getKrsSchedule();
    }

    public function getKrsById(string $id_krs): array
    {
        return $this->krs_repository->getKrsById($id_krs);
    }

    public function getPilihanMataKuliah(string $id_krs): array
    {
        return $this->krs_repository->getPilihanMataKuliah($id_krs);
    }

    public function setKrsSchedule(string $starts_date, string $ends_date, string $semester, string $academic_year): bool
    {
        return $this->krs_repository->setKrsSchedule($starts_date, $ends_date, $semester, $academic_year);
    }

    public function createStudentKrs(KartuRencanaStudi $krs): bool
    {
        return $this->krs_repository->createStudentKrs($krs);
    }

    public function updateKrs(string $id_krs, KartuRencanaStudi $new_krs): bool
    {
        return $this->krs_repository->updateKrs($id_krs, $new_krs);
    }

    public function commitKrs(string $id_krs): bool
    {
        return $this->krs_repository->commitKrs($id_krs);
    }

    public function approveKrs(string $id_krs): bool
    {
        return $this->krs_repository->approveKrs($id_krs);
    }

    public function getStudentKrs(string $id): array
    {
        return $this->krs_repository->getStudentKrs($id);
    }

    public function getAllKrs(): array
    {
        return $this->krs_repository->getAllKrs();
    }

    public function getStudentKrsBySemester(string $id, string $semester): array
    {
        return $this->krs_repository->getStudentKrsBySemester($id, $semester);
    }

    public function insertRincianKrs(string $id_krs, array $list_matkul): bool
    {
        return $this->krs_repository->insertRincianKrs($id_krs, $list_matkul);
    }

    public function deleteRincianKrs(string $id_krs): bool
    {
        return $this->krs_repository->deleteRincianKrs($id_krs);
    }
}
