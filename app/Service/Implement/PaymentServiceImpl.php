<?php

namespace Clairence\Service\Implement;

use Clairence\Entity\PaymentRequest;
use Clairence\Entity\PaymentResponse;
use Clairence\Entity\RincianTagihan;
use Clairence\Entity\Student;
use Clairence\Entity\TagihanPerkuliahan;
use Clairence\Entity\TagihanPerkuliahanDb;
use Clairence\Helper\PaymentDetailEncoder;
use Clairence\Repository\Implement\PaymentRepositoryImpl;
use Clairence\Service\Interface\PaymentServiceInterface;
use Exception;
use GuzzleHttp\Client;

class PaymentServiceImpl implements PaymentServiceInterface
{

    public function __construct(private PaymentRepositoryImpl $paymentRepository)
    {
    }

    public function getTagihanPerkuliahan(string $nim): array
    {
        return $this->paymentRepository->getTagihanPerkuliahan($nim);
    }

    public function getAllHistoryTransaksi(string $nim): array
    {
        return $this->paymentRepository->getAllHistoryTransaksi($nim);
    }

    public function getTagihanPerkuliahanPerTA(string $nim, string $tahunAkademik, string $semester, string $kategori): ?TagihanPerkuliahanDb
    {
        return $this->paymentRepository->getTagihanPerkuliahanPerTA($nim, $tahunAkademik, $semester, $kategori);
    }

    public function setTagihanPerkuliahan(TagihanPerkuliahan $tagihanPerkuliahan): bool
    {
        return $this->paymentRepository->setTagihanPerkuliahan($tagihanPerkuliahan);
    }

    public function insertRincianTagihan(RincianTagihan $rincianTagihan): bool
    {
        return $this->paymentRepository->insertRincianTagihan($rincianTagihan);
    }

    public function getRincianTagihan(string $idTagihanPerkuliahan): array
    {
        return $this->paymentRepository->getRincianTagihan($idTagihanPerkuliahan);
    }

    // public function insertHistoryTransaksi(PaymentResponse $paymentResponse): bool
    // {
    //     return $this->paymentRepository->insertHistoryTransaksi($paymentResponse);
    // }

    public function updateHistoryTransaksi(string $id_pembayaran_kuliah, string $id_order, string $server_key): string
    {
        $client = new Client();

        $response = $client->request('GET', 'https://api.sandbox.midtrans.com/v2/' . $id_order . '/status', [
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic ' . base64_encode($server_key . ':'),
            ],
        ]);

        $decoded_body = (array) json_decode((string) $response->getBody());

        $statusResponse = PaymentResponse::createPaymentResponse($decoded_body);

        if (!$statusResponse->getStatusMessage() == 'Success, transaction is found') {
            http_response_code(409);
            throw new Exception('Transaksi tidak ditemukan');
        }
        
        if (!$this->paymentRepository->updateHistoryTransaksi($statusResponse)) {
            http_response_code(409);
            throw new Exception('Gagal update histori transaksi');
        }
        
        if ($statusResponse->getTransactionStatus() == 'settlement') {
            
            if (!$this->paymentRepository->updateTagihanPerkuliahan($id_pembayaran_kuliah, 0, 'lunas')) {
                http_response_code(409);
                throw new Exception('Gagal update pembayaran kuliah');
            }

        }

        return json_encode(json_decode((string) $response->getBody()));
    }

    public function createCharge(string $charge_url, string $id_pembayaran_kuliah, Student $mhs, string $server_key, PaymentRequest $paymentRequest): string
    {
        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($server_key . ':'),
            'Content-type' => 'application/json'
        );

        $payload = PaymentDetailEncoder::encode($paymentRequest->getOrderId(), $paymentRequest->getGrossAmount(), $mhs);

        $client = new Client();

        $response = $client->post($charge_url, [
            'headers' => $headers,
            'body' => $payload
        ]);

        $decoded_body = (array) json_decode((string) $response->getBody());

        $paymentResponse = PaymentResponse::createPaymentResponse($decoded_body);

        if (!$this->paymentRepository->insertHistoryTransaksi($id_pembayaran_kuliah, $paymentResponse)) {
            throw new Exception("Insert payment details to DB failed");
        }

        // if (!$this->paymentRepository->insertPayment($user->getId(), $payment_response)) {
        //     throw new Exception("Insert payment details to DB failed");
        // }

        return json_encode(json_decode((string) $response->getBody()));
    }
}
