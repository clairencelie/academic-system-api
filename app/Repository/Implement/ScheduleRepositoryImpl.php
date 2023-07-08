<?php

namespace Clairence\Repository\Implement;

use Clairence\Entity\Schedule;
use Clairence\Entity\ScheduleRequest;
use Clairence\Entity\ScheduleUpdate;
use Clairence\Repository\Interface\ScheduleRepositoryInterface;
use Exception;
use PDO;

class ScheduleRepositoryImpl implements ScheduleRepositoryInterface
{
    public function __construct(private PDO $conn)
    {
    }

    public function getStudentSchedules(string $id, string $id_krs, string $tahun_akademik, string $semester): array
    {
        $sql = <<<SQL
            SELECT 
                jadwal_perkuliahan.id_jadwal AS id,
                jadwal_perkuliahan.id_mata_kuliah AS id_matkul,
                mata_kuliah.id_mata_kuliah_master AS learning_sub_id,
                mata_kuliah.nama_mata_kuliah AS learning_sub_name,
                jadwal_perkuliahan.id_dosen AS lecturer_id,
                dosen.nama AS lecturer_name,
                jadwal_perkuliahan.waktu_mulai AS starts_at,
                jadwal_perkuliahan.waktu_selesai AS ends_at,
                jadwal_perkuliahan.hari AS day,
                jadwal_perkuliahan.ruangan AS room,
                mata_kuliah.kelas AS grade,
                mata_kuliah.jumlah_sks AS credit,
                jadwal_perkuliahan.keterangan AS information,
                jadwal_perkuliahan.tahun_akademik,
                jadwal_perkuliahan.semester
            FROM kartu_rencana_studi as krs
            JOIN rincian_krs_mhs as rkm ON (rkm.id_krs = krs.id_krs)
            JOIN mata_kuliah ON (mata_kuliah.id_mata_kuliah = rkm.id_mata_kuliah)
            JOIN jadwal_perkuliahan ON (jadwal_perkuliahan.id_mata_kuliah = mata_kuliah.id_mata_kuliah)
            JOIN dosen ON (dosen.nip = jadwal_perkuliahan.id_dosen)
            WHERE krs.id_krs = :id_krs
            AND krs.nim = :nim
            AND jadwal_perkuliahan.tahun_akademik = :tahun_akademik
            AND jadwal_perkuliahan.semester = :semester
            ORDER BY jadwal_perkuliahan.waktu_mulai ASC, mata_kuliah.kelas ASC;
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id_krs", $id_krs);
        $statement->bindParam("nim", $id);
        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            $schedules = [];
            while ($schedule = $statement->fetch(PDO::FETCH_ASSOC)) {
                array_push($schedules, $schedule);
            }
            return $schedules;
        }

        return [];
    }

    public function getLecturerSchedules(string $id, string $day, string $tahun_akademik, string $semester): array
    {
        $sql = <<<SQL
            SELECT 
                jadwal_perkuliahan.id_jadwal AS id,
                jadwal_perkuliahan.id_mata_kuliah AS id_matkul,
                mata_kuliah.id_mata_kuliah_master AS learning_sub_id,
                mata_kuliah.nama_mata_kuliah AS learning_sub_name,
                jadwal_perkuliahan.id_dosen AS lecturer_id,
                dosen.nama AS lecturer_name,
                jadwal_perkuliahan.waktu_mulai AS starts_at,
                jadwal_perkuliahan.waktu_selesai AS ends_at,
                jadwal_perkuliahan.hari AS day,
                jadwal_perkuliahan.ruangan AS room,
                mata_kuliah.kelas AS grade,
                mata_kuliah.jumlah_sks AS credit,
                jadwal_perkuliahan.keterangan AS information,
                jadwal_perkuliahan.tahun_akademik,
                jadwal_perkuliahan.semester
            FROM jadwal_perkuliahan
            JOIN dosen ON (dosen.nip = jadwal_perkuliahan.id_dosen)
            JOIN mata_kuliah ON (mata_kuliah.id_mata_kuliah = jadwal_perkuliahan.id_mata_kuliah)
            WHERE jadwal_perkuliahan.id_dosen = :id_dosen
            AND jadwal_perkuliahan.hari = :hari
            AND jadwal_perkuliahan.tahun_akademik = :tahun_akademik
            AND jadwal_perkuliahan.semester = :semester
            ORDER BY jadwal_perkuliahan.waktu_mulai ASC, mata_kuliah.kelas ASC;
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id_dosen", $id);
        $statement->bindParam("hari", $day);
        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            $schedules = [];
            while ($schedule = $statement->fetch(PDO::FETCH_ASSOC)) {
                array_push($schedules, $schedule);
            }
            return $schedules;
        }

        return [];
    }

    public function getAllSchedule(string $tahun_akademik, string $semester): array
    {
        $sql = <<<SQL
            SELECT 
                jadwal_perkuliahan.id_jadwal AS id,
                jadwal_perkuliahan.id_mata_kuliah AS learning_sub_id,
                mata_kuliah.id_mata_kuliah_master AS id_matkul,
                mata_kuliah.nama_mata_kuliah AS learning_sub_name,
                jadwal_perkuliahan.id_dosen AS lecturer_id,
                dosen.nama AS lecturer_name,
                jadwal_perkuliahan.waktu_mulai AS starts_at,
                jadwal_perkuliahan.waktu_selesai AS ends_at,
                jadwal_perkuliahan.hari AS day,
                jadwal_perkuliahan.ruangan AS room,
                mata_kuliah.kelas AS grade,
                mata_kuliah.jumlah_sks AS credit,
                jadwal_perkuliahan.keterangan AS information,
                jadwal_perkuliahan.tahun_akademik,
                jadwal_perkuliahan.semester
            FROM jadwal_perkuliahan
            JOIN dosen ON (dosen.nip = jadwal_perkuliahan.id_dosen)
            JOIN mata_kuliah ON (mata_kuliah.id_mata_kuliah = jadwal_perkuliahan.id_mata_kuliah)
            WHERE jadwal_perkuliahan.tahun_akademik = :tahun_akademik
            AND jadwal_perkuliahan.semester = :semester
            ORDER BY jadwal_perkuliahan.hari DESC, mata_kuliah.nama_mata_kuliah ASC, mata_kuliah.kelas ASC;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();

        $schedules = [];

        while ($schedule = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($schedules, $schedule);
        }

        return $schedules;
    }

    public function getSchedulesByDay(string $day, string $tahun_akademik, string $semester): array
    {
        $sql = <<<SQL
            SELECT 
                jadwal_perkuliahan.id_jadwal AS id,
                jadwal_perkuliahan.id_mata_kuliah AS learning_sub_id,
                mata_kuliah.nama_mata_kuliah AS learning_sub_name,
                jadwal_perkuliahan.id_dosen AS lecturer_id,
                dosen.nama AS lecturer_name,
                jadwal_perkuliahan.waktu_mulai AS starts_at,
                jadwal_perkuliahan.waktu_selesai AS ends_at,
                jadwal_perkuliahan.hari AS day,
                jadwal_perkuliahan.ruangan AS room,
                mata_kuliah.kelas AS grade,
                mata_kuliah.jumlah_sks AS credit,
                jadwal_perkuliahan.keterangan AS information
            FROM jadwal_perkuliahan
            JOIN dosen ON (dosen.nip = jadwal_perkuliahan.id_dosen)
            JOIN mata_kuliah ON (mata_kuliah.id_mata_kuliah = jadwal_perkuliahan.id_mata_kuliah)
            WHERE jadwal_perkuliahan.hari = :hari
            ORDER BY mata_kuliah.kelas ASC, jadwal_perkuliahan.waktu_mulai ASC;
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("hari", $day);

        $statement->execute();

        $schedules = [];

        while ($schedule = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($schedules, $schedule);
        }

        return $schedules;
    }

    public function isScheduleConflict(ScheduleRequest $schedule): bool
    {
        $learning_sub_id = $schedule->getLearningSubId();
        $lecturer_id = $schedule->getLecturerId();
        $day = $schedule->getDay();
        $starts_at = $schedule->getStartsAt();
        $ends_at = $schedule->getEndsAt();
        $room = $schedule->getRoom();
        $tahun_akademik = $schedule->getTahunAkademik();
        $semester = $schedule->getSemester();


        // Get detail mata kuliah yang ingin diinput ke jadwal
        $sql = <<<SQL
            SELECT * 
            FROM mata_kuliah
            WHERE id_mata_kuliah = :id_mata_kuliah AND tahun_akademik = :tahun_akademik AND semester = :semester;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam("id_mata_kuliah", $learning_sub_id);
        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();


        if ($statement->rowCount() < 0) {
            return false;
        }

        $matkul_db = $statement->fetch(PDO::FETCH_ASSOC);

        $sql = <<<SQL
            SELECT 
            jadwal_perkuliahan.id_jadwal, 
            jadwal_perkuliahan.id_dosen, 
            jadwal_perkuliahan.id_mata_kuliah, 
            jadwal_perkuliahan.hari, 
            jadwal_perkuliahan.waktu_mulai, 
            jadwal_perkuliahan.waktu_selesai, 
            jadwal_perkuliahan.ruangan, 
            jadwal_perkuliahan.tahun_akademik, 
            jadwal_perkuliahan.semester,
            mata_kuliah.nama_mata_kuliah,
            mata_kuliah.kelas
            FROM jadwal_perkuliahan
            JOIN mata_kuliah ON (mata_kuliah.id_mata_kuliah = jadwal_perkuliahan.id_mata_kuliah)
            WHERE (
                jadwal_perkuliahan.id_mata_kuliah = :id_mata_kuliah 
                AND jadwal_perkuliahan.tahun_akademik = :tahun_akademik
                AND jadwal_perkuliahan.semester = :semester
            ) OR (
                jadwal_perkuliahan.id_dosen = :id_dosen
                AND jadwal_perkuliahan.hari = :hari
                AND (
                    jadwal_perkuliahan.waktu_mulai BETWEEN :waktu_mulai AND :waktu_selesai
                    OR jadwal_perkuliahan.waktu_selesai BETWEEN :waktu_mulai AND :waktu_selesai
                )
                AND jadwal_perkuliahan.tahun_akademik = :tahun_akademik
                AND jadwal_perkuliahan.semester = :semester
            ) OR (
                jadwal_perkuliahan.ruangan = :ruangan
                AND jadwal_perkuliahan.hari = :hari
                AND (
                    jadwal_perkuliahan.waktu_mulai BETWEEN :waktu_mulai AND :waktu_selesai
                    OR jadwal_perkuliahan.waktu_selesai BETWEEN :waktu_mulai AND :waktu_selesai
                    OR (
                        :waktu_mulai BETWEEN jadwal_perkuliahan.waktu_mulai AND jadwal_perkuliahan.waktu_selesai
                    )
                    OR (
                        :waktu_selesai BETWEEN jadwal_perkuliahan.waktu_mulai AND jadwal_perkuliahan.waktu_selesai
                    )
                )
                AND jadwal_perkuliahan.tahun_akademik = :tahun_akademik
                AND jadwal_perkuliahan.semester = :semester
            );
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id_mata_kuliah", $learning_sub_id);
        $statement->bindParam("id_dosen", $lecturer_id);
        $statement->bindParam("hari", $day);
        $statement->bindParam("waktu_mulai", $starts_at);
        $statement->bindParam("waktu_selesai", $ends_at);
        $statement->bindParam("ruangan", $room);
        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            $dup_schedules = [];

            while ($schedule_from_db = $statement->fetch(PDO::FETCH_ASSOC)) {
                // if (
                //     $schedule_from_db["id_mata_kuliah"] != $learning_sub_id && $schedule_from_db["id_dosen"] == $lecturer_id && $schedule_from_db["hari"] == $day && $schedule_from_db["waktu_mulai"] == $starts_at && $schedule_from_db["waktu_selesai"] == $ends_at && $schedule_from_db["ruangan"] == $room && $schedule_from_db["tahun_akademik"] == $tahun_akademik &&  $schedule_from_db["semester"] == $semester
                // ) {
                // } else {
                //     $dup_schedules[] = $schedule_from_db;
                // }
                // if (($schedule_from_db['id_mata_kuliah'] != $learning_sub_id) || ($schedule_from_db['id_mata_kuliah'] == $learning_sub_id && ($schedule_from_db["tahun_akademik"] != $tahun_akademik ||  $schedule_from_db["semester"] != $semester))) {
                // } else {
                if ($matkul_db['nama_mata_kuliah'] == $schedule_from_db['nama_mata_kuliah'] && $matkul_db['kelas'] != $schedule_from_db['kelas']) {
                } else {
                    $dup_schedules[] = $schedule_from_db;
                }
                // }
            }

            if (count($dup_schedules) > 0) {
                return true;
            }
        }

        return false;
    }

    public function insertSchedule(ScheduleRequest $schedule): bool
    {
        $lecturer_id = $schedule->getLecturerId();
        $learning_sub_id = $schedule->getLearningSubId();
        $starts_at = $schedule->getStartsAt();
        $ends_at = $schedule->getEndsAt();
        $room = $schedule->getRoom();
        $day = $schedule->getDay();
        $information = $schedule->getInformation();
        $tahun_akademik = $schedule->getTahunAkademik();
        $semester = $schedule->getSemester();

        if ($this->isScheduleConflict($schedule)) {
            return false;
        }

        $sql = <<<SQL
            INSERT INTO jadwal_perkuliahan (
                id_dosen,
                id_mata_kuliah,
                waktu_mulai,
                waktu_selesai,
                ruangan,
                hari,
                keterangan,
                tahun_akademik,
                semester
            ) VALUES (
                :id_dosen,
                :id_mata_kuliah,
                :waktu_mulai,
                :waktu_selesai,
                :ruangan,
                :hari,
                :keterangan,
                :tahun_akademik,
                :semester
            );
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id_dosen", $lecturer_id);
        $statement->bindParam("id_mata_kuliah", $learning_sub_id);
        $statement->bindParam("waktu_mulai", $starts_at);
        $statement->bindParam("waktu_selesai", $ends_at);
        $statement->bindParam("ruangan", $room);
        $statement->bindParam("hari", $day);
        $statement->bindParam("keterangan", $information);
        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();

        if ($statement->rowCount() <= 0) {
            return false;
        }

        return true;
    }

    public function updateSchedule(ScheduleUpdate $schedule): bool
    {
        $schedule_id = $schedule->getId();
        $lecturer_id = $schedule->getLecturerId();
        $starts_at = $schedule->getStartsAt();
        $ends_at = $schedule->getEndsAt();
        $room = $schedule->getRoom();
        $day = $schedule->getDay();
        $information = $schedule->getInformation();
        $tahun_akademik = $schedule->getTahunAkademik();
        $semester = $schedule->getSemester();

        // Get Schedule from db
        $sql = <<<SQL
            SELECT jadwal_perkuliahan.id_jadwal, mata_kuliah.nama_mata_kuliah 
            FROM jadwal_perkuliahan
            JOIN mata_kuliah ON (mata_kuliah.id_mata_kuliah = jadwal_perkuliahan.id_mata_kuliah)
            WHERE id_jadwal = :id_jadwal;
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id_jadwal", $schedule_id);

        $statement->execute();

        if ($statement->rowCount() != 1) {
            return false;
        }

        $schedule_from_db = $statement->fetch(PDO::FETCH_ASSOC);

        $sql = <<<SQL
            SELECT jadwal_perkuliahan.id_jadwal, mata_kuliah.nama_mata_kuliah
            FROM jadwal_perkuliahan
            JOIN mata_kuliah ON (mata_kuliah.id_mata_kuliah = jadwal_perkuliahan.id_mata_kuliah)
            WHERE (
                jadwal_perkuliahan.id_dosen = :id_dosen
                AND jadwal_perkuliahan.hari = :hari
                AND (
                        jadwal_perkuliahan.waktu_mulai BETWEEN :waktu_mulai AND :waktu_selesai
                        OR jadwal_perkuliahan.waktu_selesai BETWEEN :waktu_mulai AND :waktu_selesai
                    )
                AND jadwal_perkuliahan.tahun_akademik = :tahun_akademik
                AND jadwal_perkuliahan.semester = :semester
                )
                OR (
                    jadwal_perkuliahan.ruangan = :ruangan
                    AND jadwal_perkuliahan.hari = :hari
                    AND (
                        jadwal_perkuliahan.waktu_mulai BETWEEN :waktu_mulai AND :waktu_selesai
                        OR jadwal_perkuliahan.waktu_selesai BETWEEN :waktu_mulai AND :waktu_selesai
                        OR (
                            :waktu_mulai BETWEEN jadwal_perkuliahan.waktu_mulai AND jadwal_perkuliahan.waktu_selesai
                        )
                        OR (
                            :waktu_selesai BETWEEN jadwal_perkuliahan.waktu_mulai AND jadwal_perkuliahan.waktu_selesai
                        )
                    )
                    AND jadwal_perkuliahan.tahun_akademik = :tahun_akademik
                    AND jadwal_perkuliahan.semester = :semester
                );
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id_dosen", $lecturer_id);
        $statement->bindParam("hari", $day);
        $statement->bindParam("waktu_mulai", $starts_at);
        $statement->bindParam("waktu_selesai", $ends_at);
        $statement->bindParam("ruangan", $room);
        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();

        $conflict_schedules = [];

        if ($statement->rowCount() > 0) {
            while ($conflict_schedule = $statement->fetch(PDO::FETCH_ASSOC)) {
                if ($conflict_schedule["nama_mata_kuliah"] != $schedule_from_db["nama_mata_kuliah"]) {
                    $conflict_schedules[] = $conflict_schedule;
                }
            }
        }

        if (count($conflict_schedules) > 0) {
            return false;
        }

        $sql = <<<SQL
            UPDATE jadwal_perkuliahan
            SET id_dosen = :id_dosen,
                waktu_mulai = :waktu_mulai,
                waktu_selesai = :waktu_selesai,
                ruangan = :ruangan,
                hari = :hari,
                keterangan = :keterangan,
                tahun_akademik = :tahun_akademik,
                semester = :semester
            WHERE id_jadwal = :id_jadwal;
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id_jadwal", $schedule_id);
        $statement->bindParam("id_dosen", $lecturer_id);
        $statement->bindParam("waktu_mulai", $starts_at);
        $statement->bindParam("waktu_selesai", $ends_at);
        $statement->bindParam("ruangan", $room);
        $statement->bindParam("hari", $day);
        $statement->bindParam("keterangan", $information);
        $statement->bindParam("tahun_akademik", $tahun_akademik);
        $statement->bindParam("semester", $semester);

        $statement->execute();

        return true;
    }

    public function deleteSchedule(array $schedule_ids): bool
    {
        for ($i = 0; $i < count($schedule_ids); $i++) {
            $sql = <<<SQL
                DELETE
                FROM jadwal_perkuliahan
                WHERE id_jadwal = :id_jadwal;
            SQL;

            $statement = $this->conn->prepare($sql);
            $statement->bindParam("id_jadwal", $schedule_ids[$i]);

            $statement->execute();
        }

        return true;
    }
}
