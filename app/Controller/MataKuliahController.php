<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Repository\Implement\MataKuliahRepositoryImpl;
use Clairence\Service\Implement\MataKuliahServiceImpl;

class MataKuliahController
{
    private MataKuliahServiceImpl $service;
    
    public function __construct()
    {
        $this->service = new MataKuliahServiceImpl(new MataKuliahRepositoryImpl(Database::getConnection()));
    }

    public function getAllMataKuliah(): void
    {
        $learning_subjects = $this->service->findAllMataKuliah();

        echo json_encode($learning_subjects);
    }
}
