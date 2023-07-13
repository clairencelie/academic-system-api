<?php

namespace Clairence\Repository\Interface;

use Clairence\Entity\KartuRencanaStudi;

interface KrsRepositoryInterface
{
    public function getKrsSchedule(): array;

    public function getKrsById(string $id_krs): array;

    public function getPilihanMataKuliah(string $id_krs): array;

    public function setKrsSchedule(string $starts_date, string $ends_date, string $semester, string $academic_year): bool;

    public function createStudentKrs(KartuRencanaStudi $krs): bool;

    public function updateKrs(string $id_krs, KartuRencanaStudi $new_krs): bool;

    public function getStudentKrs(string $id): array;

    public function commitKrs(string $id_krs): bool;

    public function approveKrs(string $id_krs): bool;

    public function unApproveKrs(string $id_krs): bool;

    public function getAllKrs(): array;
    
    public function getStudentKrsBySemester(string $id, string $semester): array;
    
    public function insertRincianKrs(string $id_krs, array $list_matkul): bool;
    
    public function deleteRincianKrs(string $id_krs): bool;
    
}
