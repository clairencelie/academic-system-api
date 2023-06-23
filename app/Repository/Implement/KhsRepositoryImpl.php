<?php

namespace Clairence\Repository\Implement;

use Clairence\Entity\KartuHasilStudi;
use Clairence\Entity\NilaiMhs;
use Clairence\Entity\TranskripNilai;
use Clairence\Repository\Interface\KhsRepositoryInterface;
use PDO;

class KhsRepositoryImpl implements KhsRepositoryInterface
{
    public function __construct(private PDO $conn)
    {
    }

    public function createTranskrip(string $id, TranskripNilai $transkrip): bool
    {
        $program_studi = $transkrip->getProgramStudi();
        $ipk = $transkrip->getIpk();
        $total_kredit_diambil = $transkrip->getTotalKreditDiambil();
        $total_kredit_diperoleh = $transkrip->getTotalKreditDiperoleh();

        $sql = <<<SQL
            INSERT INTO transkrip_nilai_mhs (
                nim,
                program_studi,
                ipk,
                total_kredit_diambil,
                total_kredit_diperoleh
            ) VALUES (
                :nim,
                :program_studi,
                :ipk,
                :total_kredit_diambil,
                :total_kredit_diperoleh
            )
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $id);
        $statement->bindParam('program_studi', $program_studi);
        $statement->bindParam('ipk', $ipk);
        $statement->bindParam('total_kredit_diambil', $total_kredit_diambil);
        $statement->bindParam('total_kredit_diperoleh', $total_kredit_diperoleh);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function updateTranskrip(string $id_transkrip_nilai, float $ipk, int $total_kredit_diambil, int $total_kredit_diperoleh): bool
    {
        $sql = <<<SQL
            UPDATE transkrip_nilai_mhs
            SET ipk = :ipk,
                total_kredit_diambil = :total_kredit_diambil,
                total_kredit_diperoleh = :total_kredit_diperoleh
            WHERE id_transkrip_nilai = :id_transkrip_nilai;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_transkrip_nilai', $id_transkrip_nilai);
        $statement->bindParam('ipk', $ipk);
        $statement->bindParam('total_kredit_diambil', $total_kredit_diambil);
        $statement->bindParam('total_kredit_diperoleh', $total_kredit_diperoleh);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return true;
    }

    public function updateKhs(string $id_khs, float $ips, int $kredit_diperoleh): bool
    {
        $sql = <<<SQL
            UPDATE kartu_hasil_studi
            SET ips = :ips,
                kredit_diperoleh = :kredit_diperoleh
            WHERE id_khs = :id_khs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('ips', $ips);
        $statement->bindParam('kredit_diperoleh', $kredit_diperoleh);
        $statement->bindParam('id_khs', $id_khs);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function updateBebanMaksSksKhs(string $id_khs, int $maks_sks_smt_slnjt): bool
    {
        $sql = <<<SQL
            UPDATE kartu_hasil_studi
            SET maks_sks_smt_slnjt = :maks_sks_smt_slnjt
            WHERE id_khs = :id_khs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('maks_sks_smt_slnjt', $maks_sks_smt_slnjt);
        $statement->bindParam('id_khs', $id_khs);

        $statement->execute();

        // if ($statement->rowCount() > 0) {
        //     return true;
        // }
        
        return true;
    }

    public function getKhsById(string $id_khs): array
    {
        $sql = <<<SQL
            SELECT *
            FROM kartu_hasil_studi
            WHERE id_khs = :id_khs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_khs', $id_khs);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $khs = $statement->fetch(PDO::FETCH_ASSOC);
            return $khs;
        }

        return [];
    }

    public function createKhs(string $id_transkrip, KartuHasilStudi $khs): bool
    {
        $semester = $khs->getSemester();
        $ips = $khs->getIps();
        $kredit_diambil = $khs->getKreditDiambil();
        $kredit_diperoleh = $khs->getKreditDiperoleh();
        $maks_sks_smt_slnjt = $khs->getMaksSksSmtSlnjt();
        $tahun_akademik = $khs->getTahunAkademik();

        $sql = <<<SQL
            INSERT INTO kartu_hasil_studi (
                id_transkrip_nilai,
                semester,
                ips,
                kredit_diambil,
                kredit_diperoleh,
                maks_sks_smt_slnjt,
                tahun_akademik
            ) VALUES (
                :id_transkrip_nilai,
                :semester,
                :ips,
                :kredit_diambil,
                :kredit_diperoleh,
                :maks_sks_smt_slnjt,
                :tahun_akademik
            )
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_transkrip_nilai', $id_transkrip);
        $statement->bindParam('semester', $semester);
        $statement->bindParam('ips', $ips);
        $statement->bindParam('kredit_diambil', $kredit_diambil);
        $statement->bindParam('kredit_diperoleh', $kredit_diperoleh);
        $statement->bindParam('maks_sks_smt_slnjt', $maks_sks_smt_slnjt);
        $statement->bindParam('tahun_akademik', $tahun_akademik);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getTranskrip(string $id): array
    {
        $transkrip = [];

        $sql = <<<SQL
            SELECT *
            FROM transkrip_nilai_mhs
            WHERE nim = :nim
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam('nim', $id);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $transkrip_from_db = $statement->fetch(PDO::FETCH_ASSOC);
            $transkrip["transkrip"] = TranskripNilai::createTranskrip($transkrip_from_db)->jsonSerialize();

            $id_transkrip = $transkrip["transkrip"]["id_transkrip_nilai"];

            $sql = <<<SQL
                SELECT *
                FROM kartu_hasil_studi
                WHERE id_transkrip_nilai = :id_transkrip_nilai;
            SQL;

            $statement = $this->conn->prepare($sql);

            $statement->bindParam('id_transkrip_nilai', $id_transkrip);

            $statement->execute();

            if ($statement->rowCount() > 0) {

                $khs_list = [];

                while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $khs = KartuHasilStudi::createKHS($data)->jsonSerialize();
                    $khs_list[] = $khs;
                }

                $transkrip['khs'] = $khs_list;

                $sql = <<<SQL
                SELECT nilai.id_mata_kuliah,
                        mk.nama_mata_kuliah,
                        nilai.id_nilai,
                        nilai.kehadiran,
                        nilai.tugas,
                        nilai.uts,
                        nilai.uas,
                        nilai.nilai,
                        nilai.angka_kualitas,
                        nilai.status,
                        nilai.tahun_akademik,
                        nilai.semester
                FROM nilai
                JOIN mata_kuliah as mk ON (mk.id_mata_kuliah = nilai.id_mata_kuliah)
                WHERE nilai.id_khs = :id_khs;
            SQL;

                $list_khs = $transkrip['khs'];

                $nilai = [];

                for ($i = 0; $i < count($transkrip['khs']); $i++) {
                    $id_khs = $list_khs[$i]["id_khs"];
                    $statement = $this->conn->prepare($sql);
                    $statement->bindParam('id_khs', $id_khs);

                    $statement->execute();

                    while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $nilai[] = $data;
                    }
                }
                $transkrip['nilai'] = $nilai;
            } else {
                $transkrip['khs'] = [];
                $transkrip['nilai'] = [];
            }
        }

        return $transkrip;
    }

    public function getKhs(string $id_transkrip_nilai, string $semester): ?KartuHasilStudi
    {
        $sql = <<<SQL
            SELECT *
            FROM kartu_hasil_studi
            WHERE id_transkrip_nilai = :id_transkrip_nilai AND semester = :semester;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_transkrip_nilai', $id_transkrip_nilai);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $data = $statement->fetch(PDO::FETCH_ASSOC);
            $khs = KartuHasilStudi::createKHS($data);
            return $khs;
        }

        return null;
    }

    public function getNilaiByKhsId(string $id_khs): array
    {
        $list_nilai = [];

        $sql = <<<SQL
            SELECT nilai.status,
                nilai.angka_kualitas,
                mk.jumlah_sks
            FROM nilai
            JOIN mata_kuliah as mk ON (mk.id_mata_kuliah = nilai.id_mata_kuliah)
            WHERE nilai.id_khs = :id_khs;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_khs', $id_khs);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                $list_nilai[] = $data;
            }
        }

        return $list_nilai;
    }

    public function insertNilai(NilaiMhs $nilai_mhs): bool
    {
        $id_khs = $nilai_mhs->getIdKhs();
        $nim = $nilai_mhs->getNim();
        $id_mata_kuliah = $nilai_mhs->getIdMataKuliah();
        $kehadiran = $nilai_mhs->getKehadiran();
        $tugas = $nilai_mhs->getTugas();
        $uts = $nilai_mhs->getUts();
        $uas = $nilai_mhs->getUas();
        $nilai = $nilai_mhs->getNilai();
        $angka_kualitas = $nilai_mhs->getAngkaKualitas();
        $status = $nilai_mhs->getStatus();
        $tahun_akademik = $nilai_mhs->getTahunAkademik();
        $semester = $nilai_mhs->getSemester();

        $sql = <<<SQL
            INSERT INTO nilai (
                id_khs,
                nim,
                id_mata_kuliah,
                kehadiran,
                tugas,
                uts,
                uas,
                nilai,
                angka_kualitas,
                status,
                tahun_akademik,
                semester
            ) VALUES (
                :id_khs,
                :nim,
                :id_mata_kuliah,
                :kehadiran,
                :tugas,
                :uts,
                :uas,
                :nilai,
                :angka_kualitas,
                :status,
                :tahun_akademik,
                :semester
            );
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_khs', $id_khs);
        $statement->bindParam('nim', $nim);
        $statement->bindParam('id_mata_kuliah', $id_mata_kuliah);
        $statement->bindParam('kehadiran', $kehadiran);
        $statement->bindParam('tugas', $tugas);
        $statement->bindParam('uts', $uts);
        $statement->bindParam('uas', $uas);
        $statement->bindParam('nilai', $nilai);
        $statement->bindParam('angka_kualitas', $angka_kualitas);
        $statement->bindParam('status', $status);
        $statement->bindParam('tahun_akademik', $tahun_akademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getNilai(string $nim, string $tahun_akademik, string $semester): array
    {
        $sql = <<<SQL
            SELECT *
            FROM nilai
            WHERE nim = :nim AND tahun_akademik = :tahun_akademik AND semester = :semester;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);
        $statement->bindParam('tahun_akademik', $tahun_akademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        $nilai = [];

        if ($statement->rowCount() > 0) {

            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                $nilai_mhs = NilaiMhs::createNilaiMhs($data);
                $nilai[] = $nilai_mhs;
            }
            return $nilai;
        }

        return [];
    }

    public function insertRincianKhs(string $id_khs, string $id_nilai): bool
    {
        $sql = <<<SQL
            INSERT INTO rincian_khs_mhs (
                id_khs,
                id_nilai
            ) VALUES (
                :id_khs,
                :id_nilai
            )
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_khs', $id_khs);
        $statement->bindParam('id_nilai', $id_nilai);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }
}
