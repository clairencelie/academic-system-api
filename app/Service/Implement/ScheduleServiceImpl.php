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

    public function findStudentSchedules(string $id, string $day): array
    {
        return $this->repository->getStudentSchedules($id, $day);
    }

    public function findLecturerSchedules(string $id, string $day): array
    {
        return $this->repository->getLecturerSchedules($id, $day);
    }

    public function findAllSchedule(): array
    {
        return $this->repository->getAllSchedule();
    }

    public function findAllSchedulesByDay(string $day): array
    {
        $raw_schedules = $this->repository->getSchedulesByDay($day);

        // modify dup schedule name
        $no_dup_schedules = [];

        foreach ($raw_schedules as $schedule) {
            $index = array_search($schedule['learning_sub_name'], array_column($no_dup_schedules, 'learning_sub_name'));


            if ($index === false) {
                $no_dup_schedules[] = $schedule;
            } else {
                $isSameStartTime = $no_dup_schedules[$index]['starts_at'] == $schedule['starts_at'];
                $isSameEndTime = $no_dup_schedules[$index]['ends_at'] == $schedule['ends_at'];
                $isSameLecturer = $no_dup_schedules[$index]['lecturer_id'] == $schedule['lecturer_id'];

                if ($isSameStartTime && $isSameEndTime && $isSameLecturer) {
                    $no_dup_schedules[$index]['id'] = $no_dup_schedules[$index]['id'] . " / " . $schedule['id'];
                    if ($no_dup_schedules[$index]['grade'] != $schedule['grade']) {

                        if ($no_dup_schedules[$index]['grade'][1] == $schedule['grade'][1]) {
                            $no_dup_schedules[$index]['grade'] = '0' . $no_dup_schedules[$index]['grade'][1] . 'MATS';
                        } else {
                            $no_dup_schedules[$index]['grade'] = $no_dup_schedules[$index]['grade'] . ' & ' . $schedule['grade'];
                        }

                        if ($no_dup_schedules[$index]['learning_sub_id'] != $schedule['learning_sub_id']) {
                            $no_dup_schedules[$index]['learning_sub_id'] = $no_dup_schedules[$index]['learning_sub_id'] . " / " . $schedule['learning_sub_id'];
                        }
                    }
                }
            }
        }

        return $no_dup_schedules;
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
