<?php

namespace Clairence\Service\Interface;

use Clairence\Entity\Schedule;
use Clairence\Entity\ScheduleRequest;
use Clairence\Entity\ScheduleUpdate;

interface ScheduleServiceInterface
{
    public function findStudentSchedules(string $id, string $day): array;

    public function findLecturerSchedules(string $id, string $day): array;

    public function findAllSchedule(): array;

    public function findAllSchedulesByDay(string $day): array;

    public function addSchedule(ScheduleRequest $schedule): bool;

    public function updateSchedule(ScheduleUpdate $schedule): bool;

    public function removeSchedule(array $schedule_id): bool;
}
