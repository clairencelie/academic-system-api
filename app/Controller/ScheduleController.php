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
        if (!isset($_POST["id"]) || !isset($_POST["id_krs"]) || !isset($_POST["tahun_akademik"]) || !isset($_POST["semester"])) {
            http_response_code(400);
            exit();
        }

        $id = $_POST["id"];
        $id_krs = $_POST["id_krs"];
        $tahun_akademik = $_POST["tahun_akademik"];
        $semester = $_POST["semester"];

        $schedules = $this->service->findStudentSchedules($id, $id_krs, $tahun_akademik, $semester);

        echo json_encode($schedules);
    }

    public function getLecturerSchedules(): void
    {
        if (!isset($_POST["id"]) || !isset($_POST["day"]) || !isset($_POST["tahun_akademik"]) || !isset($_POST["semester"])) {
            http_response_code(400);
            exit();
        }

        $id = $_POST["id"];
        $day = $_POST["day"];
        $tahun_akademik = $_POST["tahun_akademik"];
        $semester = $_POST["semester"];

        $schedules = $this->service->findLecturerSchedules($id, $day, $tahun_akademik, $semester);

        echo json_encode($schedules);
    }

    public function getAllSchedule(): void
    {
        if (!isset($_POST["tahun_akademik"]) || !isset($_POST["semester"])) {
            http_response_code(400);
            exit();
        }

        $tahun_akademik = $_POST["tahun_akademik"];
        $semester = $_POST["semester"];

        $schedules = $this->service->findAllSchedule($tahun_akademik, $semester);

        echo json_encode($schedules);
    }

    public function getSchedulesByDay(): void
    {
        if (!isset($_POST["day"]) || !isset($_POST["tahun_akademik"]) || !isset($_POST["semester"])) {
            http_response_code(400);
            exit();
        }

        $day = $_POST["day"];
        $tahun_akademik = $_POST["tahun_akademik"];
        $semester = $_POST["semester"];

        $schedules = $this->service->findAllSchedulesByDay($day, $tahun_akademik, $semester);

        echo json_encode($schedules);
    }

    public function addSchedule(): void
    {
        if (!isset($_POST["new_schedule"])) {
            http_response_code(400);
            exit();
        }

        $new_schedule = ScheduleRequest::createScheduleRequest((array) json_decode($_POST["new_schedule"]));
        // $new_schedule = ScheduleRequest::createScheduleRequest($_POST["new_schedule"]);

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
        // $new_schedule = ScheduleUpdate::createScheduleUpdate($_POST["new_schedule"]);

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
        // $condition = $this->service->removeSchedule($_POST["schedule_id"]);

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
