<?php

namespace Clairence\Service\Interface;

use Clairence\Entity\MataKuliahUpdate;
use Clairence\Entity\NewMataKuliah;

interface MataKuliahServiceInterface {

    public function findAllMataKuliahMaster(): array;

    public function findAllMataKuliah(): array;

    public function createMataKuliah(NewMataKuliah $newMataKuliah): bool;

    public function updateMataKuliah(MataKuliahUpdate $newMataKuliah): bool;

    public function deleteMataKuliah(array $list_id_mata_kuliah): bool;

}
