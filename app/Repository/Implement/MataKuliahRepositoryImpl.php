<?php

namespace Clairence\Repository\Implement;

use Clairence\Entity\LearningSubject;
use Clairence\Entity\MataKuliahUpdate;
use Clairence\Entity\NewMataKuliah;
use Clairence\Repository\Interface\MataKuliahRepositoryInterface;
use Exception;
use PDO;

class MataKuliahRepositoryImpl implements MataKuliahRepositoryInterface
{
    public function __construct(private PDO $conn)
    {
    }

    public function getAllMataKuliahMaster(): array
    {
        $sql = <<<SQL
            SELECT * 
            FROM master_mata_kuliah;
        SQL;

        $statement = $this->conn->query($sql);

        $statement->execute();

        $learning_subjects = [];

        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $learning_subjects[] = $row;
            }
        }

        return $learning_subjects;
    }

    public function getAllMataKuliah(): array
    {

        $sql = <<<SQL
            SELECT * 
            FROM mata_kuliah
            ORDER BY kelas ASC;
        SQL;

        $statement = $this->conn->query($sql);

        $statement->execute();

        $learning_subjects = [];

        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $learning_subject = LearningSubject::createLearningSubject($row);
                $array = $learning_subject->jsonSerialize();

                $learning_subjects[] = $array;
            }
        }

        return $learning_subjects;
    }

    public function createMataKuliah(NewMataKuliah $newMataKuliah): bool
    {
        $id_mata_kuliah_master = $newMataKuliah->getIdMataKuliahMaster();
        $id_dosen = $newMataKuliah->getIdDosen();
        $nama_mata_kuliah = $newMataKuliah->getNamaMataKuliah();
        $jumlah_sks = $newMataKuliah->getJumlahSks();
        $kelas = $newMataKuliah->getKelas();
        $jenis = $newMataKuliah->getJenis();
        $tahun_akademik = $newMataKuliah->getTahunAkademik();
        $semester = $newMataKuliah->getSemester();


        $sql = <<<SQL
            SELECT *
            FROM mata_kuliah
            WHERE id_mata_kuliah_master = :id_mata_kuliah_master AND tahun_akademik = :tahun_akademik AND semester = :semester;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_mata_kuliah_master', $id_mata_kuliah_master);
        $statement->bindParam('tahun_akademik', $tahun_akademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            // throw new Exception('mata kuliah ' . $nama_mata_kuliah . ' duplikat');
            return false;
        }

        $sql = <<<SQL
            INSERT INTO mata_kuliah (
                id_mata_kuliah_master,
                id_dosen,
                nama_mata_kuliah,
                jumlah_sks,
                kelas,
                jenis,
                tahun_akademik,
                semester
            ) VALUES (
                :id_mata_kuliah_master,
                :id_dosen,
                :nama_mata_kuliah,
                :jumlah_sks,
                :kelas,
                :jenis,
                :tahun_akademik,
                :semester
            )
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_mata_kuliah_master', $id_mata_kuliah_master);
        $statement->bindParam('id_dosen', $id_dosen);
        $statement->bindParam('nama_mata_kuliah', $nama_mata_kuliah);
        $statement->bindParam('jumlah_sks', $jumlah_sks);
        $statement->bindParam('kelas', $kelas);
        $statement->bindParam('jenis', $jenis);
        $statement->bindParam('tahun_akademik', $tahun_akademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        throw new Exception('gagal insert mata kuliah' . $nama_mata_kuliah . ' ' . $id_mata_kuliah_master);
        return false;
    }

    public function updateMataKuliah(MataKuliahUpdate $newMataKuliah): bool
    {
        $id_mata_kuliah = $newMataKuliah->getIdMataKuliah();
        $id_dosen = $newMataKuliah->getIdDosen();
        $kelas = $newMataKuliah->getKelas();

        $sql = <<<SQL
            UPDATE mata_kuliah
            SET id_dosen = :id_dosen,
                kelas = :kelas
            WHERE id_mata_kuliah = :id_mata_kuliah;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_dosen', $id_dosen);
        $statement->bindParam('kelas', $kelas);
        $statement->bindParam('id_mata_kuliah', $id_mata_kuliah);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function deleteMataKuliah(array $list_id_mata_kuliah): bool
    {
        for ($i = 0; $i < count($list_id_mata_kuliah); $i++) {
            $sql = <<<SQL
                SELECT * 
                FROM nilai
                WHERE id_mata_kuliah = :id_mata_kuliah;
            SQL;

            $statement = $this->conn->prepare($sql);

            $statement->bindParam('id_mata_kuliah', $list_id_mata_kuliah[$i]);

            $statement->execute();

            if ($statement->rowCount() > 0) {
                return false;
            }
        }

        for ($i = 0; $i < count($list_id_mata_kuliah); $i++) {
            $sql = <<<SQL
                DELETE
                FROM mata_kuliah
                WHERE id_mata_kuliah = :id_mata_kuliah;
            SQL;

            $statement = $this->conn->prepare($sql);

            $statement->bindParam('id_mata_kuliah', $list_id_mata_kuliah[$i]);

            $statement->execute();
        }

        return true;
    }
}
