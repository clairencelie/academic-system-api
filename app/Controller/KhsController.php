<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Entity\TranskripNilai;
use Clairence\Repository\Implement\KhsRepositoryImpl;
use Clairence\Service\Implement\KhsServiceImpl;

class KhsController
{

    private KhsServiceImpl $service;

    public function __construct()
    {
        $this->service = new KhsServiceImpl(new KhsRepositoryImpl(Database::getConnection()));
    }

    public function getTranskrip(): void
    {
        if (!isset($_POST['nim'])) {
            http_response_code(400);
            exit();
        }

        $transkrip = $this->service->getTranskrip($_POST['nim']);

        if ($transkrip == []) {
            http_response_code(404);
            exit;
        }

        echo json_encode($transkrip);
    }

    public function getTranskripPerSemester(): void
    {
        if (!isset($_POST['nim'])) {
            http_response_code(400);
            exit();
        }

        $transkrip = $this->service->getTranskripPerSemester($_POST['nim']);

        if ($transkrip == []) {
            http_response_code(404);
            exit;
        }

        echo json_encode($transkrip);
    }


    public function getKhs(): void
    {
        // array of KartuHasilStudi
    }

    public function createTranskrip(): void
    {
        if (!isset($_POST['transkrip'])) {
            http_response_code(400);
            exit();
        }

        $transkrip = TranskripNilai::createTranskrip($_POST['transkrip']);

        $nim = $transkrip->getNim();

        if ($this->service->createTranskrip($nim, $transkrip)) {
            echo json_encode([
                "message" => "transkrip created successfully",
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                "message" => "failed to create transkrip",
            ]);
        }
    }

    public function createKhs(): void
    {
    }
}
