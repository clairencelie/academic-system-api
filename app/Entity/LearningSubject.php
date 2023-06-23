<?php

namespace Clairence\Entity;

class LearningSubject
{
    public function __construct(
        private string $id,
        private string $lecturer_id,
        private string $name,
        private string $credit,
        private string $grade,
        private string $type,
    )
    {
    }

    public static function createLearningSubject(array $data): LearningSubject
    {
        return new LearningSubject(
            $data["id_mata_kuliah"],
            $data["id_dosen"],
            $data["nama_mata_kuliah"],
            $data["jumlah_sks"],
            $data["kelas"],
            $data["jenis"],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
