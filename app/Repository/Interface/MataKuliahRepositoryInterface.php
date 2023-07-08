<?php

namespace Clairence\Repository\Interface;

use Clairence\Entity\MataKuliahUpdate;
use Clairence\Entity\NewMataKuliah;

interface MataKuliahRepositoryInterface
{
    public function getAllMataKuliahMaster(): array;

    public function getAllMataKuliah(): array;

    public function createMataKuliah(NewMataKuliah $newMataKuliah): bool;

    public function updateMataKuliah(MataKuliahUpdate $newMataKuliah): bool;

    public function deleteMataKuliah(array $list_id_mata_kuliah): bool;
}
