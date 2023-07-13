<?php

namespace Clairence\Repository\Implement;

use Clairence\Entity\KartuRencanaStudi;
use Clairence\Entity\KartuRencanaStudiDB;
use Clairence\Repository\Interface\KrsRepositoryInterface;
use PDO;

class KrsRepositoryImpl implements KrsRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }

    public function getKrsSchedule(): array
    {
        $sql = <<<SQL
            SELECT *
            FROM jadwal_krs;
        SQL;

        $statement = $this->conn->query($sql);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            $data = $statement->fetch(PDO::FETCH_ASSOC);
            $data["tanggal_mulai"] = date('d-m-Y', strtotime($data['tanggal_mulai']));
            $data["tanggal_selesai"] = date('d-m-Y', strtotime($data['tanggal_selesai']));
            return $data;
        }

        return [];
    }

    public function getKrsById(string $id_krs): array
    {
        $krs = [];

        $sql = <<<SQL
            SELECT *
            FROM kartu_rencana_studi
            WHERE id_krs = :id_krs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_krs', $id_krs);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $krs = $statement->fetch(PDO::FETCH_ASSOC);
        }

        return $krs;
    }

    public function commitKrs(string $id_krs): bool
    {
        $sql = <<<SQL
            UPDATE kartu_rencana_studi
            SET commit = 1
            WHERE id_krs = :id_krs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_krs', $id_krs);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function approveKrs(string $id_krs): bool
    {
        $sql = <<<SQL
            UPDATE kartu_rencana_studi
            SET approve = 1
            WHERE id_krs = :id_krs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_krs', $id_krs);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function unApproveKrs(string $id_krs): bool
    {
        $sql = <<<SQL
            UPDATE kartu_rencana_studi
            SET approve = 0
            WHERE id_krs = :id_krs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_krs', $id_krs);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function setKrsSchedule(string $starts_date, string $ends_date, string $semester, string $academic_year): bool
    {
        $tanggal_mulai = date('Y-m-d', strtotime($starts_date));
        $tanggal_selesai = date('Y-m-d', strtotime($ends_date));

        $sql = <<<SQL
            UPDATE jadwal_krs
            SET tanggal_mulai = :starts_date,
                tanggal_selesai = :ends_date,
                semester = :semester,
                tahun_akademik = :academic_year;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('starts_date', $tanggal_mulai);
        $statement->bindParam('ends_date', $tanggal_selesai);
        $statement->bindParam('semester', $semester);
        $statement->bindParam('academic_year', $academic_year);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function createStudentKrs(KartuRencanaStudi $krs): bool
    {
        $nim = $krs->getNim();
        $id_dosen = $krs->getIdDosen();
        $program_studi = $krs->getJurusan();
        $ips = $krs->getIps();
        $ipk = $krs->getIpk();
        $kredit_diambil = $krs->getKreditDiambil();
        $beban_sks_maks = $krs->getBebanSksMaks();
        $semester = $krs->getSemester();
        $waktu_pengisian = $krs->getWaktuPengisian();
        $tahun_akademik = $krs->getTahunAkademik();

        $sql = <<<SQL
            INSERT INTO kartu_rencana_studi (
                nim,
                id_dosen,
                program_studi,
                ips,
                ipk,
                kredit_diambil,
                beban_sks_maks,
                semester,
                waktu_pengisian,
                tahun_akademik
            ) VALUES (
                :nim,
                :id_dosen,
                :program_studi,
                :ips,
                :ipk,
                :kredit_diambil,
                :beban_sks_maks,
                :semester,
                :waktu_pengisian,
                :tahun_akademik
            );
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);
        $statement->bindParam('id_dosen', $id_dosen);
        $statement->bindParam('program_studi', $program_studi);
        $statement->bindParam('ips', $ips);
        $statement->bindParam('ipk', $ipk);
        $statement->bindParam('kredit_diambil', $kredit_diambil);
        $statement->bindParam('beban_sks_maks', $beban_sks_maks);
        $statement->bindParam('semester', $semester);
        $statement->bindParam('waktu_pengisian', $waktu_pengisian);
        $statement->bindParam('tahun_akademik', $tahun_akademik);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function updateKrs(string $id_krs, KartuRencanaStudi $new_krs): bool
    {

        $kredit_diambil = $new_krs->getKreditDiambil();

        $sql = <<<SQL
            UPDATE kartu_rencana_studi
            SET kredit_diambil = :kredit_diambil
            WHERE id_krs = :id_krs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('kredit_diambil', $kredit_diambil);
        $statement->bindParam('id_krs', $id_krs);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getStudentKrs(string $nim): array
    {

        $student_krs = [];

        $student_krs_json = [];

        $sql = <<<SQL
            SELECT krs.id_krs,
                    krs.nim,
                    krs.id_dosen,
                    mahasiswa.nama,
                    krs.program_studi,
                    krs.ips,
                    krs.ipk,
                    krs.kredit_diambil,
                    krs.beban_sks_maks,
                    krs.semester,
                    krs.waktu_pengisian,
                    krs.tahun_akademik,
                    krs.commit,
                    krs.approve
            FROM kartu_rencana_studi as krs
            JOIN mahasiswa ON (mahasiswa.nim = krs.nim)
            WHERE krs.nim = :nim;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);

        $statement->execute();

        while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
            $krs = KartuRencanaStudiDB::createKRS($data);
            $student_krs[] = $krs;
        }

        for ($i = 0; $i < count($student_krs); $i++) {
            $krs = $student_krs[$i];
            if ($krs instanceof KartuRencanaStudiDb) {
                // Get id_mata_kuliah from rincian_krs_mhs
                $sql = <<<SQL
                                SELECT id_mata_kuliah
                                FROM rincian_krs_mhs
                                WHERE id_krs = :id_krs;
                            SQL;

                $id_krs = $krs->getId();

                $statement = $this->conn->prepare($sql);

                $statement->bindParam('id_krs', $id_krs);

                $statement->execute();

                $list_id_pilihan_matkul = [];

                while ($pilihan_mata_kuliah = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $list_id_pilihan_matkul[] = $pilihan_mata_kuliah;
                }

                // List mata kuliah yang diambil saat pengisian krs
                $list_pilihan_matkul = [];

                for ($j = 0; $j < count($list_id_pilihan_matkul); $j++) {
                    $id_mata_kuliah = $list_id_pilihan_matkul[$j]['id_mata_kuliah'];

                    $sql = <<<SQL
                                    SELECT id_mata_kuliah as id,
                                        id_mata_kuliah_master as id_mata_kuliah,
                                        id_dosen as lecturer_id,
                                        nama_mata_kuliah as name,
                                        jumlah_sks as credit,
                                        kelas as grade,
                                        jenis as type,
                                        tahun_akademik as tahun_akademik,
                                        semester as semester
                                    FROM mata_kuliah 
                                    WHERE id_mata_kuliah = :id_mata_kuliah;
                                SQL;

                    // kalau sudah insert jadwal smt genap
                    // $sql = <<<SQL
                    //                 SELECT dosen.nip,
                    //                     dosen.nama,
                    //                     mk.id_mata_kuliah,
                    //                     mk.nama_mata_kuliah,
                    //                     mk.jumlah_sks,
                    //                     mk.kelas,
                    //                     mk.jenis,
                    //                     jadwal_perkuliahan.hari,
                    //                     jadwal_perkuliahan.waktu_mulai,
                    //                     jadwal_perkuliahan.waktu_selesai
                    //                 FROM mata_kuliah as mk
                    //                 JOIN dosen ON (mk.id_dosen = dosen.nip)
                    //                 JOIN jadwal_perkuliahan ON (jadwal_perkuliahan.id_mata_kuliah = mk.id_mata_kuliah)
                    //                 WHERE mk.id_mata_kuliah = :id_mata_kuliah;
                    //             SQL;

                    $statement = $this->conn->prepare($sql);

                    $statement->bindParam('id_mata_kuliah', $id_mata_kuliah);

                    $statement->execute();

                    while ($matkul = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $list_pilihan_matkul[] = $matkul;
                    }
                }

                $krs->setPilihanMataKuliah($list_pilihan_matkul);
                $student_krs_json[] = $krs->jsonSerialize();
            }
        }

        return $student_krs_json;
    }

    public function getStudentKrsBySemester(string $nim, string $semester): array
    {
        $sql = <<<SQL
            SELECT * 
            FROM kartu_rencana_studi
            WHERE nim = :nim AND semester = :semester;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function getPilihanMataKuliah(string $id_krs): array
    {
        $list_pilihan_matkul = [];

        $sql = <<<SQL
            SELECT id_mata_kuliah
            FROM rincian_krs_mhs
            WHERE id_krs = :id_krs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_krs', $id_krs);

        $statement->execute();

        while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
            $list_pilihan_matkul[] = $data['id_mata_kuliah'];
        }

        return $list_pilihan_matkul;
    }

    public function insertRincianKrs(string $id_krs, array $list_matkul): bool
    {
        for ($i = 0; $i < count($list_matkul); $i++) {

            $sql = <<<SQL
                INSERT INTO rincian_krs_mhs (
                    id_krs,
                    id_mata_kuliah
                ) VALUES (
                    :id_krs,
                    :id_mata_kuliah
                )
            SQL;

            $statement = $this->conn->prepare($sql);

            $statement->bindParam('id_krs', $id_krs);
            $statement->bindParam('id_mata_kuliah', $list_matkul[$i]);

            $statement->execute();

            if ($statement->rowCount() <= 0) {
                return false;
            }
        }
        return true;
    }

    public function deleteRincianKrs(string $id_krs): bool
    {
        $sql = <<<SQL
            DELETE 
            FROM rincian_krs_mhs
            WHERE id_krs = :id_krs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_krs', $id_krs);

        $statement->execute();

        return true;
    }

    public function getAllKrs(): array
    {
        $student_krs = [];

        $student_krs_json = [];

        $sql = <<<SQL
            SELECT krs.id_krs,
                    krs.nim,
                    krs.id_dosen,
                    mahasiswa.nama,
                    krs.program_studi,
                    krs.ips,
                    krs.ipk,
                    krs.kredit_diambil,
                    krs.beban_sks_maks,
                    krs.semester,
                    krs.waktu_pengisian,
                    krs.tahun_akademik,
                    krs.commit,
                    krs.approve
            FROM kartu_rencana_studi as krs
            JOIN mahasiswa ON (mahasiswa.nim = krs.nim);
        SQL;

        $statement = $this->conn->query($sql);

        $statement->execute();

        while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
            $krs = KartuRencanaStudiDB::createKRS($data);
            $student_krs[] = $krs;
        }

        for ($i = 0; $i < count($student_krs); $i++) {
            $krs = $student_krs[$i];
            if ($krs instanceof KartuRencanaStudiDb) {
                // Get id_mata_kuliah from rincian_krs_mhs
                $sql = <<<SQL
                                SELECT id_mata_kuliah
                                FROM rincian_krs_mhs
                                WHERE id_krs = :id_krs;
                            SQL;

                $id_krs = $krs->getId();

                $statement = $this->conn->prepare($sql);

                $statement->bindParam('id_krs', $id_krs);

                $statement->execute();

                $list_id_pilihan_matkul = [];

                while ($pilihan_mata_kuliah = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $list_id_pilihan_matkul[] = $pilihan_mata_kuliah;
                }

                // List mata kuliah yang diambil saat pengisian krs
                $list_pilihan_matkul = [];

                for ($j = 0; $j < count($list_id_pilihan_matkul); $j++) {
                    $id_mata_kuliah = $list_id_pilihan_matkul[$j]['id_mata_kuliah'];

                    $sql = <<<SQL
                                    SELECT id_mata_kuliah as id,
                                        id_mata_kuliah_master as id_mata_kuliah,
                                        id_dosen as lecturer_id,
                                        nama_mata_kuliah as name,
                                        jumlah_sks as credit,
                                        kelas as grade,
                                        jenis as type,
                                        tahun_akademik as tahun_akademik,
                                        semester as semester
                                    FROM mata_kuliah 
                                    WHERE id_mata_kuliah = :id_mata_kuliah;
                                SQL;

                    // kalau sudah insert jadwal smt genap
                    // $sql = <<<SQL
                    //                 SELECT dosen.nip,
                    //                     dosen.nama,
                    //                     mk.id_mata_kuliah,
                    //                     mk.nama_mata_kuliah,
                    //                     mk.jumlah_sks,
                    //                     mk.kelas,
                    //                     mk.jenis,
                    //                     jadwal_perkuliahan.hari,
                    //                     jadwal_perkuliahan.waktu_mulai,
                    //                     jadwal_perkuliahan.waktu_selesai
                    //                 FROM mata_kuliah as mk
                    //                 JOIN dosen ON (mk.id_dosen = dosen.nip)
                    //                 JOIN jadwal_perkuliahan ON (jadwal_perkuliahan.id_mata_kuliah = mk.id_mata_kuliah)
                    //                 WHERE mk.id_mata_kuliah = :id_mata_kuliah;
                    //             SQL;

                    $statement = $this->conn->prepare($sql);

                    $statement->bindParam('id_mata_kuliah', $id_mata_kuliah);

                    $statement->execute();

                    while ($matkul = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $list_pilihan_matkul[] = $matkul;
                    }
                }

                $krs->setPilihanMataKuliah($list_pilihan_matkul);
                $student_krs_json[] = $krs->jsonSerialize();
            }
        }

        return $student_krs_json;
    }
}
