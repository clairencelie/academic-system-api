<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Entity\Schedule;
use Clairence\Entity\ScheduleRequest;
use Clairence\Entity\ScheduleUpdate;
use Clairence\Repository\Implement\ScheduleRepositoryImpl;
use Clairence\Service\Implement\ScheduleServiceImpl;

class ScheduleController
{
    private ScheduleServiceImpl $service;

    public function __construct()
    {
        $this->service = new ScheduleServiceImpl(new ScheduleRepositoryImpl(Database::getConnection()));
    }

    public function getStudentSchedules(): void
    {
        if (!isset($_POST["id"]) || !isset($_POST["day"])) {
            http_response_code(400);
            exit();
        }

        $id = $_POST["id"];
        $day = $_POST["day"];

        $schedules = $this->service->findStudentSchedules($id, $day);

        echo json_encode($schedules);
    }

    public function getLecturerSchedules(): void
    {
        if (!isset($_POST["id"]) || !isset($_POST["day"])) {
            http_response_code(400);
            exit();
        }

        $id = $_POST["id"];
        $day = $_POST["day"];

        $schedules = $this->service->findLecturerSchedules($id, $day);

        echo json_encode($schedules);
    }

    public function getAllSchedule(): void
    {
        $schedules = $this->service->findAllSchedule();

        echo json_encode($schedules);
    }

    public function getSchedulesByDay(string $day): void
    {
        $schedules = $this->service->findAllSchedulesByDay($day);

        echo json_encode($schedules);
    }

    public function addSchedule(): void
    {
        if (!isset($_POST["new_schedule"])) {
            http_response_code(400);
            exit();
        }

        $new_schedule = ScheduleRequest::createScheduleRequest((array) json_decode($_POST["new_schedule"]));

        $condition = $this->service->addSchedule($new_schedule);

        if ($condition) {
            echo json_encode([
                "message" => "new schedule succesfully created",
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                "message" => "failed to create new schedule",
            ]);
        }
    }

    public function updateSchedule(): void
    {
        if (!isset($_POST["new_schedule"])) {
            http_response_code(400);
            exit();
        }

        $new_schedule = ScheduleUpdate::createScheduleUpdate((array) json_decode($_POST["new_schedule"]));

        $condition = $this->service->updateSchedule($new_schedule);

        if ($condition) {
            echo json_encode([
                "message" => "schedule succesfully updated",
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                "message" => "failed to update schedule",
            ]);
        }
    }

    public function removeSchedule(): void
    {
        if (!isset($_POST["schedule_id"])) {
            http_response_code(400);
            exit();
        }

        $condition = $this->service->removeSchedule((array) json_decode($_POST["schedule_id"]));

        if ($condition) {
            echo json_encode([
                "message" => "schedule removed"
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                "message" => "failed to remove schedule"
            ]);
        }
    }

}
