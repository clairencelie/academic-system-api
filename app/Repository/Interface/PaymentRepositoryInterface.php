<?php

namespace Clairence\Repository\Interface;

use Clairence\Entity\PaymentResponse;
use Clairence\Entity\RincianTagihan;
use Clairence\Entity\TagihanPerkuliahan;
use Clairence\Entity\TagihanPerkuliahanDb;

interface PaymentRepositoryInterface
{
    public function getTagihanPerkuliahan(string $nim): array;

    public function getAllHistoryTransaksi(string $nim): array;

    public function getTagihanPerkuliahanPerTA(string $nim, string $tahunAkademik, string $semester, string $kategori): ?TagihanPerkuliahanDb;

    public function setTagihanPerkuliahan(TagihanPerkuliahan $tagihanPerkuliahan): bool;

    public function updateTagihanPerkuliahan(string $idTagihanPerkuliahan, int $sisaPembayaran, string $statusPembayaran): bool;

    public function insertRincianTagihan(RincianTagihan $rincianTagihan): bool;

    public function getRincianTagihan(string $idTagihanPerkuliahan): array;

    public function insertHistoryTransaksi(string $idPembayaranKuliah, PaymentResponse $paymentResponse): bool;

    public function updateHistoryTransaksi(PaymentResponse $paymentResponse): bool;
}
