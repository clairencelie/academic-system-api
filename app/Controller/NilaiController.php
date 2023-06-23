<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Repository\Implement\KhsRepositoryImpl;
use Clairence\Repository\Implement\NilaiRepositoryImpl;
use Clairence\Service\Implement\KhsServiceImpl;
use Clairence\Service\Implement\NilaiServiceImpl;
use Exception;
use PDO;

class NilaiController
{

    private NilaiServiceImpl $service;
    private KhsServiceImpl $khs_service;
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();

        $this->service = new NilaiServiceImpl(new NilaiRepositoryImpl($this->pdo));

        $this->khs_service = new KhsServiceImpl(
            new KhsRepositoryImpl($this->pdo),
        );
    }

    public function getTahunAkademik(): void
    {
        $tahun_akademik = $this->service->getTahunAkademik();

        echo json_encode($tahun_akademik);
    }

    public function getMataKuliah(): void
    {
        if (!isset($_POST['id_dosen'])) {
            http_response_code(400);
            exit();
        }

        $daftar_matkul = $this->service->getMataKuliah($_POST['id_dosen']);

        echo json_encode($daftar_matkul);
    }

    public function getDaftarNilai(): void
    {
        if (!isset($_POST['id_mata_kuliah']) || !isset($_POST['tahun_akademik']) || !isset($_POST['semester'])) {
            http_response_code(400);
            exit();
        }

        $daftar_nilai = $this->service->getDaftarNilai($_POST['id_mata_kuliah'], $_POST['tahun_akademik'], $_POST['semester']);

        echo json_encode($daftar_nilai);
    }

    public function updateNilai(): void
    {
        if (
            !isset($_POST['nim']) ||
            !isset($_POST['id_khs']) ||
            !isset($_POST['id_nilai']) ||
            !isset($_POST['jumlah_sks']) ||
            !isset($_POST['kehadiran']) ||
            !isset($_POST['tugas']) ||
            !isset($_POST['uts']) ||
            !isset($_POST['uas'])
        ) {
            http_response_code(400);
            exit();
        }

        $nim = $_POST['nim'];

        $id_khs = $_POST['id_khs'];

        $jumlah_sks = $_POST['jumlah_sks'];

        $id_nilai = $_POST['id_nilai'];
        $kehadiran = $_POST['kehadiran'];
        $tugas = $_POST['tugas'];
        $uts = $_POST['uts'];
        $uas = $_POST['uas'];
        $nilai_angka = ((($kehadiran / 14) * 5) + (($tugas / 100) * 20) + (($uts / 100) * 35) + (($uas / 100) * 40));
        $nilai_huruf = '';
        $bobot_nilai = 0;
        $status = '';

        if ($nilai_angka >= 80) {
            $nilai_huruf = 'A';
            $bobot_nilai = 4;
            $status = 'lulus';
        } else if ($nilai_angka >= 68) {
            $nilai_huruf = 'B';
            $bobot_nilai = 3;
            $status = 'lulus';
        } else if ($nilai_angka >= 56) {
            $nilai_huruf = 'C';
            $bobot_nilai = 2;
            $status = 'lulus';
        } else if ($nilai_angka >= 45) {
            $nilai_huruf = 'D';
            $bobot_nilai = 1;
            $status = 'belum lulus';
        } else if ($nilai_angka < 45) {
            $nilai_huruf = 'E';
            $bobot_nilai = 0;
            $status = 'belum lulus';
        }

        $angka_kualitas = $jumlah_sks * $bobot_nilai;

        $this->pdo->beginTransaction();

        try {
            $condition = $this->service->updateNilai($id_nilai, $kehadiran, $tugas, $uts, $uas, $nilai_huruf, $angka_kualitas, $status);

            if ($condition) {
                // update KHS
                $updateKhsSuccess =  $this->khs_service->updateKhs($id_khs);

                if ($updateKhsSuccess) {

                    // update Transkrip
                    $transkripUpdateSuccess = $this->khs_service->updateTranskrip($nim);

                    if ($transkripUpdateSuccess) {

                        $maksSksKhsUpdate = $this->khs_service->updateBebanMaksSksKhs($id_khs, $nim);

                        if ($maksSksKhsUpdate) {
                            $this->pdo->commit();
                            echo json_encode(["message" => "update khs berhasil"]);
                        } else {
                            throw new Exception('Gagal update maks beban sks pada khs');
                        }
                    } else {
                        throw new Exception('Gagal update transkrip');
                    }

                    // update maks sks smt slnjt pada KHS
                } else {
                    throw new Exception('Gagal update khs');
                }
            } else {
                throw new Exception('Gagal update nilai');
            }
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            http_response_code(409);
            echo json_encode(["message" => "Exception: {$e->getMessage()}"]);
        }
    }
}
