<?php

namespace Clairence\Entity;

class ScheduleRequest
{
    public function __construct(
        private string $learning_sub_id,
        private string $lecturer_id,
        private string $starts_at,
        private string $ends_at,
        private string $day,
        private string $room,
        private string $information,
        private string $tahun_akademik,
        private string $semester,
    ) {
    }

    public static function createScheduleRequest(array $data): ScheduleRequest
    {
        return new ScheduleRequest(
            $data["learning_sub_id"],
            $data["lecturer_id"],
            $data["starts_at"],
            $data["ends_at"],
            $data["day"],
            $data["room"],
            $data["information"],
            $data["tahun_akademik"],
            $data["semester"],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**
     * Get the value of learning_sub_id
     */
    public function getLearningSubId(): string
    {
        return $this->learning_sub_id;
    }

    /**
     * Get the value of lecturer_id
     */
    public function getLecturerId(): string
    {
        return $this->lecturer_id;
    }

    /**
     * Get the value of starts_at
     */
    public function getStartsAt(): string
    {
        return $this->starts_at;
    }

    /**
     * Get the value of ends_at
     */
    public function getEndsAt(): string
    {
        return $this->ends_at;
    }

    /**
     * Get the value of day
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * Get the value of room
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * Get the value of information
     */
    public function getInformation(): string
    {
        return $this->information;
    }


    /**
     * Get the value of tahun_akademik
     */
    public function getTahunAkademik(): string
    {
        return $this->tahun_akademik;
    }

    /**
     * Get the value of semester
     */
    public function getSemester(): string
    {
        return $this->semester;
    }
}
