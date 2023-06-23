<?php

namespace Clairence\Service\Interface;

use Clairence\Entity\PaymentRequest;
use Clairence\Entity\PaymentResponse;
use Clairence\Entity\RincianTagihan;
use Clairence\Entity\Student;
use Clairence\Entity\TagihanPerkuliahan;
use Clairence\Entity\TagihanPerkuliahanDb;

interface PaymentServiceInterface
{
    public function getTagihanPerkuliahan(string $nim): array;

    public function getAllHistoryTransaksi(string $nim): array;

    public function getTagihanPerkuliahanPerTA(string $nim, string $tahunAkademik, string $semester, string $kategori): ?TagihanPerkuliahanDb;

    public function setTagihanPerkuliahan(TagihanPerkuliahan $tagihanPerkuliahan): bool;

    public function insertRincianTagihan(RincianTagihan $rincianTagihan): bool;

    public function getRincianTagihan(string $idTagihanPerkuliahan): array;

    // public function insertHistoryTransaksi(PaymentResponse $paymentResponse): bool;

    public function updateHistoryTransaksi(string $id_pembayaran_kuliah, string $id_order, string $server_key): string;

    public function createCharge(string $charge_url, string $id_pembayaran_kuliah, Student $mhs, string $server_key, PaymentRequest $paymentRequest): string;
}
