<?php

namespace Clairence\Service\Implement;

use Clairence\Entity\MataKuliahUpdate;
use Clairence\Entity\NewMataKuliah;
use Clairence\Repository\Implement\MataKuliahRepositoryImpl;
use Clairence\Service\Interface\MataKuliahServiceInterface;

class MataKuliahServiceImpl implements MataKuliahServiceInterface
{

    public function __construct(private MataKuliahRepositoryImpl $repository)
    {
    }

    public function findAllMataKuliahMaster(): array
    {
        return $this->repository->getAllMataKuliahMaster();
    }

    public function findAllMataKuliah(): array
    {
        return $this->repository->getAllMataKuliah();
    }

    public function createMataKuliah(NewMataKuliah $newMataKuliah): bool
    {
        return $this->repository->createMataKuliah($newMataKuliah);
    }

    public function updateMataKuliah(MataKuliahUpdate $newMataKuliah): bool
    {
        return $this->repository->updateMataKuliah($newMataKuliah);
    }

    public function deleteMataKuliah(array $list_id_mata_kuliah): bool
    {
        return $this->repository->deleteMataKuliah($list_id_mata_kuliah);
    }
}
