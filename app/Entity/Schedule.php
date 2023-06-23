<?php

namespace Clairence\Entity;

class Schedule
{
    public function __construct(
        private string $id,
        private string $learning_sub_id,
        private string $learning_sub_name,
        private string $lecturer_id,
        private string $lecturer_name,
        private string $starts_at,
        private string $ends_at,
        private string $day,
        private string $room,
        private string $grade,
        private string $credit,
        private string $information,
    )
    {
    }

    public static function createSchedule(array $data): Schedule
    {
        return new Schedule(
            $data["id_jadwal"],
            $data["id_mata_kuliah"],
            $data["nama_mata_kuliah"],
            $data["id_dosen"],
            $data["nama_dosen"],
            $data["waktu_mulai"],
            $data["waktu_selesai"],
            $data["hari"],
            $data["ruangan"],
            $data["kelas"],
            $data["jumlah_sks"],
            $data["keterangan"],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
