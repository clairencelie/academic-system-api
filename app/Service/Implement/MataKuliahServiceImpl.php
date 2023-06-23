<?php

namespace Clairence\Service\Implement;

use Clairence\Repository\Implement\MataKuliahRepositoryImpl;
use Clairence\Service\Interface\MataKuliahServiceInterface;

class MataKuliahServiceImpl implements MataKuliahServiceInterface
{

    public function __construct(private MataKuliahRepositoryImpl $repository)
    {
    }

    public function findAllMataKuliah(): array
    {
        return $this->repository->findAllMataKuliah();
    }
}
