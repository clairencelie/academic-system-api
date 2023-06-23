<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Entity\PaymentRequest;
use Clairence\Helper\Env;
use Clairence\Helper\UniqueIdGenerator;
use Clairence\Repository\Implement\PaymentRepositoryImpl;
use Clairence\Repository\Implement\UserRepositoryImpl;
use Clairence\Service\Implement\PaymentServiceImpl;
use Clairence\Service\Implement\UserServiceImpl;
use PDO;

class PaymentController
{
    private string $server_key;
    private $url = 'https://api.sandbox.midtrans.com/v2/charge';

    private PaymentServiceImpl $paymentService;
    private UserRepositoryImpl $userRepo;
    private PDO $pdo;

    public function __construct()
    {
        Env::readImmutable();
        $this->server_key = $_ENV["SERVER_KEY"];

        $this->pdo = Database::getConnection();

        $this->paymentService = new PaymentServiceImpl(
            new PaymentRepositoryImpl($this->pdo),
            new UserRepositoryImpl($this->pdo),
        );

        $this->userRepo = new UserRepositoryImpl($this->pdo);
    }

    public function midtransNotificationUpdate(): void
    {
        // Data dari notif post midtrans
        $input = (array) (json_decode(file_get_contents("php://input")));

        // update data histori transaksi

    }

    public function getTagihanMahasiswa(): void
    {
        if (!isset($_POST['nim'])) {
            http_response_code(400);
            exit();
        }

        $tagihanMahasiswa = $this->paymentService->getTagihanPerkuliahan($_POST['nim']);

        echo json_encode($tagihanMahasiswa);
    }

    public function getRincianTagihan(): void
    {
        if (!isset($_POST['id_pembayaran_kuliah'])) {
            http_response_code(400);
            exit();
        }

        $rincianTagihan = $this->paymentService->getRincianTagihan($_POST['id_pembayaran_kuliah']);

        echo json_encode($rincianTagihan);
    }

    public function getStatusTransaksi(): void
    {
        if (!isset($_POST["id_pembayaran_kuliah"]) || !isset($_POST["id_order"])) {
            http_response_code(400);
            exit();
        }

        $getStatus = $this->paymentService->updateHistoryTransaksi($_POST['id_pembayaran_kuliah'], $_POST['id_order'], $this->server_key);

        echo $getStatus;
    }

    public function getAllHistoriTransaksi(): void
    {
        if (!isset($_POST["nim"])) {
            http_response_code(400);
            exit();
        }

        $list_histori_transaksi = $this->paymentService->getAllHistoryTransaksi($_POST['nim']);

        echo json_encode($list_histori_transaksi);
    }

    public function bayarTagihan(): void
    {

        if (!isset($_POST["id_pembayaran_kuliah"]) || !isset($_POST["nim"]) || !isset($_POST["gross_amount"])) {
            http_response_code(400);
            exit();
        }

        $mhs = $this->userRepo->getMahasiswaByNim($_POST['nim']);

        // order id harus beda dengan id pembayaran kuliah
        $order_id = UniqueIdGenerator::generate_uuid();

        $paymentRequest = new PaymentRequest($order_id, $_POST['gross_amount']);

        // tambahkan parameter order id
        $response = $this->paymentService->createCharge($this->url, $_POST['id_pembayaran_kuliah'], $mhs, $this->server_key, $paymentRequest);

        echo $response;
    }
}
