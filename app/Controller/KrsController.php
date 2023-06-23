<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Entity\KartuHasilStudi;
use Clairence\Entity\KartuRencanaStudi;
use Clairence\Entity\NilaiMhs;
use Clairence\Entity\RincianTagihan;
use Clairence\Entity\TagihanPerkuliahan;
use Clairence\Helper\UniqueIdGenerator;
use Clairence\Repository\Implement\KhsRepositoryImpl;
use Clairence\Repository\Implement\KrsRepositoryImpl;
use Clairence\Repository\Implement\PaymentRepositoryImpl;
use Clairence\Service\Implement\KhsServiceImpl;
use Clairence\Service\Implement\KrsServiceImpl;
use Clairence\Service\Implement\PaymentServiceImpl;
use Exception;
use PDO;

class KrsController
{

    private KrsServiceImpl $krs_service;
    private KhsServiceImpl $khs_service;
    private PaymentServiceImpl $paymentService;
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();

        $this->krs_service = new KrsServiceImpl(
            new KrsRepositoryImpl($this->pdo),
        );

        $this->khs_service = new KhsServiceImpl(
            new KhsRepositoryImpl($this->pdo),
        );

        $this->paymentService = new PaymentServiceImpl(
            new PaymentRepositoryImpl($this->pdo),
        );
    }

    public function getKrsSchedule(): void
    {
        $jadwal_krs = $this->krs_service->getKrsSchedule();

        echo json_encode($jadwal_krs);
    }

    public function setKrsSchedule(): void
    {
        if (!isset($_POST['starts_date']) || !isset($_POST['ends_date']) || !isset($_POST['semester']) || !isset($_POST['academic_year'])) {
            http_response_code(400);
            exit();
        }

        // Call service
        $condition = $this->krs_service->setKrsSchedule($_POST['starts_date'], $_POST['ends_date'], $_POST['semester'], $_POST['academic_year']);

        if ($condition) {
            echo json_encode(["message" => "krs schedule updated"]);
        } else {
            http_response_code(409);
            echo json_encode(["message" => "krs schedule update failed"]);
        }
    }

    public function getStudentKrs(): void
    {
        if (!isset($_POST['nim'])) {
            http_response_code(400);
            exit();
        }

        $student_krs = $this->krs_service->getStudentKrs($_POST['nim']);

        echo json_encode($student_krs);
    }

    public function getAllKrs(): void
    {
        $list_krs = $this->krs_service->getAllKrs();
        echo json_encode($list_krs);
    }

    public function createStudentKrs(): void
    {

        if (!isset($_POST['krs'])) {
            http_response_code(400);
            exit();
        }

        $krs = KartuRencanaStudi::createKRS((array) json_decode($_POST['krs'])->krs);

        $list_id_matkul_diambil = (array) json_decode($_POST['krs'])->list_matkul;

        $this->pdo->beginTransaction();

        try {
            $condition = $this->krs_service->createStudentKrs($krs);

            if ($condition) {
                // Membuat rincian KRS, Memasukan matakuliah apa saja yang diambil pada krs diatas
                $krs_from_db = $this->krs_service->getStudentKrsBySemester($krs->getNim(), $krs->getSemester());

                $insertRincianSuccess = $this->krs_service->insertRincianKrs($krs_from_db['id_krs'], $list_id_matkul_diambil);

                if ($insertRincianSuccess) {
                    $this->pdo->commit();
                    echo json_encode(["message" => "krs created successfully"]);
                } else {
                    http_response_code(409);
                    throw new Exception('gagal insert rincian krs');
                    exit();
                }
            } else {
                http_response_code(409);
                throw new Exception('gagal membuat krs');
                exit();
            }
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(["message" => "Exception: {$e->getMessage()}"]);
        }
    }

    public function updateKrs(): void
    {
        // Butuh id_krs
        if (!isset($_POST['krs'])) {
            http_response_code(400);
            exit();
        }

        $id_krs = (string) json_decode($_POST['krs'])->id_krs;

        $krs = KartuRencanaStudi::createKRS((array) json_decode($_POST['krs'])->krs);

        $list_id_matkul_diambil = (array) json_decode($_POST['krs'])->list_matkul;

        $this->pdo->beginTransaction();

        try {
            $updateSuccess = $this->krs_service->updateKrs($id_krs, $krs);

            if ($updateSuccess) {
                $deleteRincianKrsLama = $this->krs_service->deleteRincianKrs($id_krs);

                if ($deleteRincianKrsLama) {
                    $insertRincianKrsBaru = $this->krs_service->insertRincianKrs($id_krs, $list_id_matkul_diambil);

                    if ($insertRincianKrsBaru) {
                        $this->pdo->commit();
                        echo json_encode(["message" => "krs berhasil diupdate"]);
                    } else {
                        throw new Exception("gagal insert rincian krs baru");
                    }
                } else {
                    throw new Exception("gagal delete rincian krs lama");
                }
            } else {
                throw new Exception('gagal update krs');
            }
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            http_response_code(409);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function commitKrs(): void
    {
        if (!isset($_POST['id_krs'])) {
            http_response_code(400);
            exit();
        }

        $krs = $this->krs_service->getKrsById($_POST['id_krs']);

        $list_id_matkul_diambil = $this->krs_service->getPilihanMataKuliah($krs['id_krs']);

        $this->pdo->beginTransaction();

        try {
            $semester_kata = $krs['semester'] % 2 == 0 ? 'genap' : 'ganjil';

            // Membuat KHS baru untuk KRS baru
            $transkrip = $this->khs_service->getTranskrip($krs['nim']);

            $id_transkrip = $transkrip['transkrip']['id_transkrip_nilai'];

            $tahun_akademik = $krs['tahun_akademik'];

            $kredit_diambil = $krs['kredit_diambil'];

            $new_khs = new KartuHasilStudi('', $id_transkrip, $krs['semester'], "0", $kredit_diambil, "0", "0", $tahun_akademik);

            $createKhsSuccess = $this->khs_service->createKhs($id_transkrip, $new_khs);

            if ($createKhsSuccess) {

                // Membuat rincian KHS, mata kuliah apa saja yang diambil saat pengisian KRS
                $semester = $krs['semester'];

                $khs = $this->khs_service->getKhs($id_transkrip, $semester);

                $id_khs = $khs->getIdKhs();

                // insert data template nilai mhs
                foreach ($list_id_matkul_diambil as $id_mata_kuliah) {

                    $nilai_mhs = new NilaiMhs($id_khs, $krs['nim'], $id_mata_kuliah, '', 0, 0, 0, 0, 'T', 0, 'belum diisi', $krs['tahun_akademik'], $semester_kata);
                    $insertNilaiSuccess = $this->khs_service->insertNilai($nilai_mhs);
                    if (!$insertNilaiSuccess) {
                        http_response_code(409);
                        throw new Exception('gagal insert template nilai');
                        exit();
                    }
                }

                // Update transkrip nilai mhs
                $updateTranskripSuccess = $this->khs_service->updateTranskrip($krs['nim']);
                if ($updateTranskripSuccess) {

                    // Kunci KRS mhs
                    $commitKrs = $this->krs_service->commitKrs($krs['id_krs']);

                    if ($commitKrs) {

                        // Generate data pembayaran kuliah
                        /**
                         * SKS = Rp.165.000 * Jumlah SKS
                         * BPP = Rp.1.300.000
                         * Registrasi = Rp.400.000
                         * DP KRS (Sudah isi KRS berarti sudah membayar DP KRS) = Rp. -1.000.000
                         */
                        $totalBiayaSKS = 165000 * $kredit_diambil;
                        $bpp = 1300000;
                        $registrasi = 400000;
                        $totalTagihan = $totalBiayaSKS + $bpp + $registrasi;

                        $tagihanSemester = new TagihanPerkuliahan(
                            idPembayaranKuliah: UniqueIdGenerator::generate_uuid(),
                            nim: $krs['nim'],
                            totalTagihan: $totalTagihan,
                            sisaPembayaran: $totalTagihan,
                            statusPembayaran: 'belum_bayar',
                            kategori: 'Semester ' . $semester,
                            metodePembayaran: 'full',
                            tahunAkademik: $tahun_akademik,
                            semester: $semester_kata
                        );

                        $setTagihanSemester = $this->paymentService->setTagihanPerkuliahan($tagihanSemester);

                        if ($setTagihanSemester) {
                            $dataTagihanSemesterMhs = $this->paymentService->getTagihanPerkuliahanPerTA($krs['nim'], $tahun_akademik, $semester_kata, 'Semester ' . $semester);

                            if ($dataTagihanSemesterMhs != null) {
                                // Rincian Sks
                                $rincianSks = new RincianTagihan(
                                    idTagihanPerkuliahan: $dataTagihanSemesterMhs->getIdTagihanPerkuliahan(),
                                    item: 'SKS',
                                    jumlahItem: $kredit_diambil,
                                    hargaItem: '165000',
                                    totalHargaItem: $totalBiayaSKS,
                                );

                                // Rincian BPP
                                $rincianBpp = new RincianTagihan(
                                    idTagihanPerkuliahan: $dataTagihanSemesterMhs->getIdTagihanPerkuliahan(),
                                    item: 'BPP',
                                    jumlahItem: '1',
                                    hargaItem: '1300000',
                                    totalHargaItem: '1300000',
                                );
                                // Rincian Biaya Admin
                                $rincianBpp = new RincianTagihan(
                                    idTagihanPerkuliahan: $dataTagihanSemesterMhs->getIdTagihanPerkuliahan(),
                                    item: 'BPP',
                                    jumlahItem: '1',
                                    hargaItem: '1300000',
                                    totalHargaItem: '1300000',
                                );

                                // Rincian Biaya Admin
                                $rincianAdminSemester = new RincianTagihan(
                                    idTagihanPerkuliahan: $dataTagihanSemesterMhs->getIdTagihanPerkuliahan(),
                                    item: 'Biaya Admin',
                                    jumlahItem: '1',
                                    hargaItem: '4000',
                                    totalHargaItem: '4000',
                                );

                                $insertRincianSks = $this->paymentService->insertRincianTagihan($rincianSks);
                                $insertRincianBpp = $this->paymentService->insertRincianTagihan($rincianBpp);
                                $insertRincianAdminDPKrs = $this->paymentService->insertRincianTagihan($rincianAdminSemester);

                                if ($insertRincianSks) {
                                    if ($insertRincianBpp) {
                                        if ($insertRincianAdminDPKrs) {
                                            // commit KRS
                                            $this->pdo->commit();
                                            echo json_encode([
                                                "message" => "krs commited successfuly",
                                            ]);
                                        } else {
                                            throw new Exception('Gagal insert rincian biaya admin');
                                        }
                                    } else {
                                        throw new Exception('Gagal insert rincian bpp');
                                    }
                                } else {
                                    throw new Exception('Gagal insert rincian sks');
                                }
                            } else {
                                throw new Exception('Tagihan semester mahasiswa tidak ditemukan');
                            }
                        } else {
                            throw new Exception('Gagal set tagihan semester');
                        }
                    } else {
                        http_response_code(409);
                        throw new Exception('gagal kunci krs');
                        exit();
                    }
                } else {
                    http_response_code(409);
                    throw new Exception('gagal update data transkrip');
                    exit();
                }
            } else {
                http_response_code(409);
                throw new Exception('gagal membuat khs');
                exit();
            }
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(["message" => "Exception: {$e->getMessage()}"]);
        }
    }
}
