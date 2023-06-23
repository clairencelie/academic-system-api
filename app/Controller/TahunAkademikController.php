<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Entity\RincianTagihan;
use Clairence\Entity\TagihanPerkuliahan;
use Clairence\Helper\UniqueIdGenerator;
use Clairence\Repository\Implement\PaymentRepositoryImpl;
use Clairence\Repository\Implement\TahunAkademikRepositoryImpl;
use Clairence\Repository\Implement\UserRepositoryImpl;
use Clairence\Service\Implement\PaymentServiceImpl;
use Clairence\Service\Implement\TahunAkademikServiceImpl;
use Clairence\Service\Implement\UserServiceImpl;
use Exception;
use PDO;

class TahunAkademikController
{
    private UserServiceImpl $userService;
    private PaymentServiceImpl $paymentService;
    private TahunAkademikServiceImpl $tahunAkademikService;
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();

        $this->tahunAkademikService = new TahunAkademikServiceImpl(new TahunAkademikRepositoryImpl($this->pdo));

        $this->paymentService = new PaymentServiceImpl(new PaymentRepositoryImpl($this->pdo));

        $this->userService = new UserServiceImpl(new UserRepositoryImpl($this->pdo));
    }

    public function setTahunAkademik(): void
    {
        if (!isset($_POST['tahunAkademik']) || !isset($_POST['semester'])) {
            http_response_code(400);
            exit();
        }

        $tahunAkademik = $_POST['tahunAkademik'];
        $semester = $_POST['semester'];

        try {
            $this->pdo->beginTransaction();

            $condition = $this->tahunAkademikService->setTahunAkademik($tahunAkademik, $semester);

            if ($condition) {

                // Increment semester mahasiswa (smt + 1)

                $tambahSemesterMhsSuccess = $this->tahunAkademikService->tambahSemesterMhs();

                if ($tambahSemesterMhsSuccess) {
                    // Generate Data DP KRS (Rp. 1.000.000)
                    // step:
                    // get data mahasiswa aktif -> list of mahasiswa aktif
                    // foreach list nya lalu insert data pembayaran kuliah dan rincian tagihan

                    $mahasiswaAktif = $this->userService->getMahasiswaAktif();

                    foreach ($mahasiswaAktif as $mhs) {
                        $tagihanUangMukaKrs = new TagihanPerkuliahan(
                            idPembayaranKuliah: UniqueIdGenerator::generate_uuid(),
                            nim: $mhs['nim'],
                            totalTagihan: '1004000',
                            sisaPembayaran: '1004000',
                            statusPembayaran: 'belum_bayar',
                            kategori: 'Uang Muka Pengisian KRS',
                            metodePembayaran: 'full',
                            tahunAkademik: $tahunAkademik,
                            semester: $semester
                        );

                        $setTagihanDPKrs = $this->paymentService->setTagihanPerkuliahan($tagihanUangMukaKrs);

                        if ($setTagihanDPKrs) {

                            $tagihanDPKrsMhs = $this->paymentService->getTagihanPerkuliahanPerTA($mhs['nim'], $tahunAkademik, $semester, 'Uang Muka Pengisian KRS');

                            if ($tagihanDPKrsMhs != null) {
                                // Rincian DP KRS
                                $rincianDPKrs = new RincianTagihan(
                                    idTagihanPerkuliahan: $tagihanDPKrsMhs->getIdTagihanPerkuliahan(),
                                    item: 'Uang Muka KRS',
                                    jumlahItem: '1',
                                    hargaItem: '1000000',
                                    totalHargaItem: '1000000',
                                );

                                // Rincian Biaya Admin
                                $rincianAdminDPKrs = new RincianTagihan(
                                    idTagihanPerkuliahan: $tagihanDPKrsMhs->getIdTagihanPerkuliahan(),
                                    item: 'Biaya Admin',
                                    jumlahItem: '1',
                                    hargaItem: '4000',
                                    totalHargaItem: '4000',
                                );

                                $insertRincianDPKrs = $this->paymentService->insertRincianTagihan($rincianDPKrs);
                                $insertRincianAdminDPKrs = $this->paymentService->insertRincianTagihan($rincianAdminDPKrs);
                                if ($insertRincianDPKrs) {
                                    if ($insertRincianAdminDPKrs) {
                                    } else {
                                        throw new Exception('Gagal insert rincian biaya admin');
                                    }
                                } else {
                                    throw new Exception('Gagal insert rincian dp krs');
                                }
                            } else {
                                throw new Exception('Tagihan mahasiswa tidak ditemukan');
                            }
                        } else {
                            throw new Exception('Gagal set tagihan DP KRS');
                        }
                    }
                    $this->pdo->commit();
                    echo json_encode(["message" => "set tahun akademik baru berhasil"]);
                } else {
                    throw new Exception('Gagal tambah semester mahasiswa');
                }
            }
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            http_response_code(409);
            echo json_encode(["message" => "Exception: {$e->getMessage()}"]);
        }
    }

    public function setJadwalKrs(): void
    {
        if (!isset($_POST['tanggalMulai']) || !isset($_POST['tanggalSelesai'])) {
            http_response_code(400);
            exit();
        }

        $condition = $this->tahunAkademikService->setJadwalKrs($_POST['tanggalMulai'], $_POST['tanggalSelesai']);

        if ($condition) {
            echo json_encode(["message" => "set jadwal krs berhasil"]);
            exit();
        } else {
            http_response_code(409);
            echo json_encode(["message" => "set jadwal krs gagal"]);
            exit();
        }
    }
}
