<?php

namespace Clairence\Repository\Implement;

use Clairence\Repository\Interface\TahunAkademikRepositoryInterface;
use Exception;
use PDO;

class TahunAkademikRepositoryImpl implements TahunAkademikRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }



    public function setTahunAkademik(string $tahunAkademik, string $semester): bool
    {
        $sql = <<<SQL
            SELECT *
            FROM tahun_akademik
            WHERE tahun_akademik = :tahunAkademik AND semester = :semester;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('tahunAkademik', $tahunAkademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            throw new Exception('Tahun akademik ' . $tahunAkademik . ' semester ' . $semester . ' sudah pernah dibuat');
            return false;
        }

        $sql = <<<SQL
            UPDATE jadwal_krs
            SET tahun_akademik = :tahunAkademik,
                semester = :semester
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('tahunAkademik', $tahunAkademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            $sql = <<<SQL
                    INSERT INTO tahun_akademik (
                        tahun_akademik,
                        semester
                    ) VALUES (
                        :tahunAkademik,
                        :semester
                    );
                SQL;

            $statement = $this->conn->prepare($sql);

            $statement->bindParam('tahunAkademik', $tahunAkademik);
            $statement->bindParam('semester', $semester);

            $statement->execute();

            if ($statement->rowCount() > 0) {
                return true;
            } else {
                throw new Exception('Gagal insert tahun akademik');
                return false;
            }
        } else {
            throw new Exception('Gagal update jadwal pengisian krs');
            return false;
        }
    }

    public function tambahSemesterMhs(): bool
    {
        $sql = <<<SQL
            UPDATE mahasiswa
            SET semester = semester + 1
            WHERE status = 'aktif';
        SQL;

        $statement = $this->conn->query($sql);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            throw new Exception('Gagal menambah data semester mahasiswa');
        }
        
        return false;
    }

    public function setJadwalKrs(string $tanggalMulai, string $tanggalSelesai): bool
    {
        $sql = <<<SQL
        UPDATE jadwal_krs
        SET tanggal_mulai = :tanggalMulai,
            tanggal_selesai = :tanggalSelesai;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('tanggalMulai', $tanggalMulai);
        $statement->bindParam('tanggalSelesai', $tanggalSelesai);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
