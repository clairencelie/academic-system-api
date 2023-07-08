<?php

namespace Clairence\Entity;

class LearningSubject
{
    public function __construct(
        private string $id,
        private string $id_mata_kuliah,
        private string $lecturer_id,
        private string $name,
        private string $credit,
        private string $grade,
        private string $type,
        private string $tahun_akademik,
        private string $semester,
    )
    {
    }

    public static function createLearningSubject(array $data): LearningSubject
    {
        return new LearningSubject(
            $data["id_mata_kuliah"],
            $data["id_mata_kuliah_master"],
            $data["id_dosen"],
            $data["nama_mata_kuliah"],
            $data["jumlah_sks"],
            $data["kelas"],
            $data["jenis"],
            $data["tahun_akademik"],
            $data["semester"],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
