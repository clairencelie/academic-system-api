<?php

namespace Clairence\Repository\Implement;

use Clairence\Entity\Academic;
use Clairence\Entity\Administrator;
use Clairence\Entity\Lecturer;
use Clairence\Entity\Student;
use Clairence\Entity\User;
use Clairence\Entity\UserFromDb;
use Clairence\Repository\Interface\UserRepositoryInterface;
use PDO;

class UserRepositoryImpl implements UserRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }

    public function auth(string $id): ?UserFromDb
    {
        $sql = <<<SQL
            SELECT id_user, password, role FROM user WHERE id_user = :id;
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam("id", $id);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            return new UserFromDb(
                $user["id_user"],
                $user["password"],
                $user["role"],
            );
        }

        return null;
    }

    public function getUserById(string $id): ?User
    {
        $userFromDb = $this->auth($id);

        if ($userFromDb == null) {
            return null;
        }

        $id = $userFromDb->getId();
        $role = $userFromDb->getRole();

        switch ($role) {
            case 'akademik':
                $sql = <<<SQL
                    SELECT * 
                    FROM akademik
                    WHERE nip = :nip;
                SQL;

                $statement = $this->conn->prepare($sql);
                $statement->bindParam("nip", $id);

                $statement->execute();

                if ($statement->rowCount() == 1) {
                    $data = $statement->fetch(PDO::FETCH_ASSOC);
                    return Academic::createAcademic($data);
                }
                return null;
                break;

            case 'dosen':
                $sql = <<<SQL
                    SELECT * 
                    FROM dosen
                    WHERE nip = :nip;
                SQL;

                $statement = $this->conn->prepare($sql);
                $statement->bindParam("nip", $id);

                $statement->execute();

                if ($statement->rowCount() == 1) {
                    $data = $statement->fetch(PDO::FETCH_ASSOC);
                    return Lecturer::createLecturer($data);
                }
                return null;
                break;

            case 'mahasiswa':
                $sql = <<<SQL
                    SELECT * 
                    FROM mahasiswa
                    WHERE nim = :nim;
                SQL;

                $statement = $this->conn->prepare($sql);
                $statement->bindParam("nim", $id);

                $statement->execute();

                if ($statement->rowCount() == 1) {
                    $data = $statement->fetch(PDO::FETCH_ASSOC);
                    return Student::createStudent($data);
                }
                break;

            case 'tata_usaha':
                $sql = <<<SQL
                    SELECT * 
                    FROM tata_usaha
                    WHERE nip = :nip;
                SQL;

                $statement = $this->conn->prepare($sql);
                $statement->bindParam("nip", $id);

                $statement->execute();

                if ($statement->rowCount() == 1) {
                    $data = $statement->fetch(PDO::FETCH_ASSOC);
                    return Administrator::createAdministrator($data);
                }
                return null;
                break;

            default:
                return null;
                break;
        }
    }

    public function getAllLecturer(): array
    {
        $sql = <<<SQL
            SELECT * FROM dosen;
        SQL;

        $statement = $this->conn->query($sql);

        $statement->execute();

        $lecturers = [];

        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $lecturer = Lecturer::createLecturer($row);
                $array = $lecturer->jsonSerialize();
                $lecturers[] = $array;
            }
        }

        return $lecturers;
    }

    public function getMahasiswaAktif(): array
    {
        $list_mhs_aktif = [];

        $sql = <<<SQL
            SELECT *
            FROM mahasiswa
            WHERE status = 'aktif';
        SQL;

        $statement = $this->conn->query($sql);

        if ($statement->rowCount() > 0) {
            while ($mahasiswa_aktif = $statement->fetch(PDO::FETCH_ASSOC)) {
                $list_mhs_aktif[] = $mahasiswa_aktif;
            }
        }

        return $list_mhs_aktif;
    }

    public function getMahasiswaByNim(string $nim): Student
    {
        $sql = <<<SQL
            SELECT *
            FROM mahasiswa
            WHERE nim = :nim;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);

        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return Student::createStudent($data);
    }
}
