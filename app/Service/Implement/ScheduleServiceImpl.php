<?php

namespace Clairence\Service\Implement;

use Clairence\Entity\Schedule;
use Clairence\Entity\ScheduleRequest;
use Clairence\Entity\ScheduleUpdate;
use Clairence\Repository\Implement\ScheduleRepositoryImpl;
use Clairence\Service\Interface\ScheduleServiceInterface;

class ScheduleServiceImpl implements ScheduleServiceInterface
{

    public function __construct(private ScheduleRepositoryImpl $repository)
    {
    }

    public function findStudentSchedules(string $id, string $id_krs, string $tahun_akademik, string $semester): array
    {
        return $this->repository->getStudentSchedules($id, $id_krs, $tahun_akademik, $semester);
    }

    public function findLecturerSchedules(string $id, string $day, string $tahun_akademik, string $semester): array
    {
        return $this->repository->getLecturerSchedules($id, $day, $tahun_akademik, $semester);
    }

    public function findAllSchedule(string $tahun_akademik, string $semester): array
    {
        return $this->repository->getAllSchedule($tahun_akademik, $semester);
    }

    public function findAllSchedulesByDay(string $day, string $tahun_akademik, string $semester): array
    {
        return $this->repository->getSchedulesByDay($day, $tahun_akademik, $semester);
    }

    public function addSchedule(ScheduleRequest $schedule): bool
    {
        return $this->repository->insertSchedule($schedule);
    }

    public function updateSchedule(ScheduleUpdate $schedule): bool
    {
        return $this->repository->updateSchedule($schedule);
    }

    public function removeSchedule(array $schedule_ids): bool
    {
        return $this->repository->deleteSchedule($schedule_ids);
    }
}
