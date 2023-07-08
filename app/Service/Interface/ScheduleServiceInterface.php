<?php

namespace Clairence\Service\Interface;

use Clairence\Entity\Schedule;
use Clairence\Entity\ScheduleRequest;
use Clairence\Entity\ScheduleUpdate;

interface ScheduleServiceInterface
{
    public function findStudentSchedules(string $id, string $id_krs, string $tahun_akademik, string $semester): array;

    public function findLecturerSchedules(string $id, string $day, string $tahun_akademik, string $semester): array;

    public function findAllSchedule(string $tahun_akademik, string $semester): array;

    public function findAllSchedulesByDay(string $day, string $tahun_akademik, string $semester): array;

    public function addSchedule(ScheduleRequest $schedule): bool;

    public function updateSchedule(ScheduleUpdate $schedule): bool;

    public function removeSchedule(array $schedule_id): bool;
}
