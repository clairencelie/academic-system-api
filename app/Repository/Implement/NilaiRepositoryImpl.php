<?php

namespace Clairence\Repository\Implement;

use Clairence\Repository\Interface\NilaiRepositoryInterface;
use PDO;

class NilaiRepositoryImpl implements NilaiRepositoryInterface
{
    public function __construct(private PDO $conn)
    {
    }

    public function getMataKuliah(string $id_dosen): array
    {
        $daftar_matkul = [];

        $sql = <<<SQL
            SELECT *
            FROM mata_kuliah
            WHERE id_dosen = :id_dosen;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_dosen', $id_dosen);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                $daftar_matkul[] = $data;
            }
        }

        return $daftar_matkul;
    }

    public function getDaftarNilai(string $id_mata_kuliah, string $tahun_akademik, string $semester): array
    {
        $daftar_nilai = [];

        $sql = <<<SQL
            SELECT nilai.id_khs,
                mhs.nim,
                mhs.nama,
                mhs.jurusan,
                nilai.id_mata_kuliah,
                mk.id_mata_kuliah_master,
                nilai.id_nilai,
                nilai.kehadiran,
                nilai.tugas,
                nilai.uts,
                nilai.uas,
                nilai.nilai,
                nilai.angka_kualitas,
                mk.jumlah_sks,
                nilai.status,
                nilai.tahun_akademik,
                nilai.semester
            FROM nilai
            JOIN mahasiswa as mhs ON (mhs.nim = nilai.nim)
            JOIN mata_kuliah as mk ON (mk.id_mata_kuliah = nilai.id_mata_kuliah)
            WHERE nilai.id_mata_kuliah = :id_mata_kuliah AND nilai.tahun_akademik = :tahun_akademik AND nilai.semester = :semester;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_mata_kuliah', $id_mata_kuliah);
        $statement->bindParam('tahun_akademik', $tahun_akademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                $daftar_nilai[] = $data;
            }
        }

        return $daftar_nilai;
    }

    public function getTahunAkademik(): array
    {
        $tahun_akademik = [];

        $sql = <<<SQL
            SELECT *
            FROM tahun_akademik;
        SQL;

        $statement = $this->conn->query($sql);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                $tahun_akademik[] = $data;
            }
        }

        return $tahun_akademik;
    }

    public function updateNilai(
        string $id_nilai,
        int $kehadiran,
        int $tugas,
        int $uts,
        int $uas,
        string $nilai,
        int $kualitas,
        string $status,
    ): bool {

        $sql = <<<SQL
            UPDATE nilai 
            SET kehadiran = :kehadiran,
                tugas = :tugas,
                uts = :uts,
                uas = :uas,
                nilai = :nilai,
                angka_kualitas = :angka_kualitas,
                status = :status
            WHERE id_nilai = :id_nilai;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('kehadiran', $kehadiran);
        $statement->bindParam('tugas', $tugas);
        $statement->bindParam('uts', $uts);
        $statement->bindParam('uas', $uas);
        $statement->bindParam('nilai', $nilai);
        $statement->bindParam('angka_kualitas', $kualitas);
        $statement->bindParam('status', $status);
        $statement->bindParam('id_nilai', $id_nilai);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }
}
