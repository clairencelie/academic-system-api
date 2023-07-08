<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Entity\MataKuliahUpdate;
use Clairence\Entity\NewMataKuliah;
use Clairence\Repository\Implement\MataKuliahRepositoryImpl;
use Clairence\Service\Implement\MataKuliahServiceImpl;

class MataKuliahController
{
    private MataKuliahServiceImpl $service;
    
    public function __construct()
    {
        $this->service = new MataKuliahServiceImpl(new MataKuliahRepositoryImpl(Database::getConnection()));
    }

    public function getAllMataKuliahMaster(): void
    {
        $learning_subjects = $this->service->findAllMataKuliahMaster();

        echo json_encode($learning_subjects);
    }

    public function getAllMataKuliah(): void
    {
        $learning_subjects = $this->service->findAllMataKuliah();

        echo json_encode($learning_subjects);
    }

    public function createMataKuliah(): void
    {
        if (!isset($_POST["new_matkul"])) {
            http_response_code(400);
            exit();
        }

        $new_matkul = NewMataKuliah::createNewMataKuliah((array) json_decode($_POST["new_matkul"]));
        // $new_matkul = NewMataKuliah::createNewMataKuliah($_POST["new_matkul"]);

        $condition = $this->service->createMataKuliah($new_matkul);

        if ($condition) {
            echo json_encode([
                "message" => "berhasil insert mata kuliah",
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                "message" => "gagal insert mata kuliah",
            ]);
        }
    }

    public function updateMataKuliah(): void
    {
        if (!isset($_POST["new_matkul"])) {
            http_response_code(400);
            exit();
        }

        $new_matkul = MataKuliahUpdate::createMataKuliahUpdate((array) json_decode($_POST["new_matkul"]));
        // $new_matkul = MataKuliahUpdate::createMataKuliahUpdate($_POST["new_matkul"]);

        $condition = $this->service->updateMataKuliah($new_matkul);

        if ($condition) {
            echo json_encode([
                "message" => "berhasil update mata kuliah",
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                "message" => "gagal update mata kuliah",
            ]);
        }
    }

    public function deleteMataKuliah(): void
    {
        if (!isset($_POST["id_mata_kuliah"])) {
            http_response_code(400);
            exit();
        }

        $condition = $this->service->deleteMataKuliah((array) json_decode($_POST["id_mata_kuliah"]));

        if ($condition) {
            echo json_encode([
                "message" => "berhasil delete mata kuliah",
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                "message" => "gagal delete mata kuliah",
            ]);
        }
    }
}
