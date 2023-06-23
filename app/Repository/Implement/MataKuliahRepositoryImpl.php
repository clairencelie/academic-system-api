<?php

namespace Clairence\Repository\Implement;

use Clairence\Entity\LearningSubject;
use Clairence\Service\Interface\MataKuliahServiceInterface;
use PDO;

class MataKuliahRepositoryImpl implements MataKuliahServiceInterface
{
    public function __construct(private PDO $conn)
    {
    }

    public function findAllMataKuliah(): array
    {

        $sql = <<<SQL
            SELECT * 
            FROM mata_kuliah
            ORDER BY nama_mata_kuliah ASC;
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
}
