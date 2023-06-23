<?php

namespace Clairence\Repository\Interface;

use Clairence\Entity\Schedule;
use Clairence\Entity\ScheduleRequest;
use Clairence\Entity\ScheduleUpdate;

interface ScheduleRepositoryInterface
{
    public function getStudentSchedules(string $id, string $day): array;

    public function getLecturerSchedules(string $id, string $day): array;

    public function getAllSchedule(): array;

    public function getSchedulesByDay(string $day): array;
    
    public function isScheduleConflict(ScheduleRequest $schedule): bool;

    public function insertSchedule(ScheduleRequest $schedule): bool;

    public function updateSchedule(ScheduleUpdate $schedule): bool;

    public function deleteSchedule(array $schedule_ids): bool;
}
