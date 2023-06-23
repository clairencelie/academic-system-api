<?php

namespace Clairence\Service\Implement;

use Clairence\Entity\KartuHasilStudi;
use Clairence\Entity\NilaiMhs;
use Clairence\Entity\TranskripNilai;
use Clairence\Repository\Implement\KhsRepositoryImpl;
use Clairence\Repository\Implement\UserRepositoryImpl;
use Clairence\Service\Interface\KhsServiceInterface;

class KhsServiceImpl implements KhsServiceInterface
{
    public function __construct(
        private KhsRepositoryImpl $khs_repository,
    ) {
    }

    public function createTranskrip(string $id, TranskripNilai $transkrip): bool
    {
        return $this->khs_repository->createTranskrip($id, $transkrip);
    }

    public function updateTranskrip(string $nim): bool
    {
        $transkrip = $this->getTranskrip($nim);

        $list_khs = $transkrip['khs'];

        $banyak_khs = count($list_khs);

        $akumulasi_ips = 0;
        $new_total_kredit_diambil = 0;
        $new_total_kredit_diperoleh = 0;

        foreach ($list_khs as $khs) {
            $obj_khs = KartuHasilStudi::createKHS($khs);
            $akumulasi_ips += (float) $obj_khs->getIps();
            $new_total_kredit_diambil += $obj_khs->getKreditDiambil();
            $new_total_kredit_diperoleh += $obj_khs->getKreditDiperoleh();
        }

        $new_ipk = $akumulasi_ips / $banyak_khs;

        return $this->khs_repository->updateTranskrip($transkrip['transkrip']['id_transkrip_nilai'], $new_ipk, $new_total_kredit_diambil, $new_total_kredit_diperoleh);
    }

    public function updateKhs(string $id_khs): bool
    {
        // getKhs
        $khs = $this->khs_repository->getKhsById($id_khs);

        // get list nilai sesuai id_khs
        $list_nilai = $this->khs_repository->getNilaiByKhsId($id_khs);

        // foreach list tsb, 
        // ips = jumlahkan angka kualitas lalu bagi dengan kredit diambil (jumlah angka kualitas / kredit diambil)
        // kredit_diperoleh = dalam foreach cek, jika $nilai['status'] == 'lulus' tambah kredit diperoleh

        $jumlah_angka_kualitas = 0;
        $kredit_diperoleh = 0;

        $kredit_diambil = $khs['kredit_diambil'];

        foreach ($list_nilai as $nilai) {
            $jumlah_angka_kualitas += $nilai['angka_kualitas'];
            if ($nilai['status'] == 'lulus') {
                $kredit_diperoleh += $nilai['jumlah_sks'];
            }
        }

        $ips = $jumlah_angka_kualitas / $kredit_diambil;

        $condition = $this->khs_repository->updateKhs($id_khs, $ips, $kredit_diperoleh);

        if ($condition) {
            return true;
        }

        return true;
    }

    public function updateBebanMaksSksKhs(string $id_khs, string $nim): bool
    {
        // NIM
        $transkrip = $this->khs_repository->getTranskrip($nim);

        $ipk = $transkrip['transkrip']['ipk'];

        $maks_sks_smt_slnjt = 0;

        if ($ipk >= 3) {
            $maks_sks_smt_slnjt = 24;
        } else if ($ipk >= 2.76) {
            $maks_sks_smt_slnjt = 22;
        } else if ($ipk >= 2.5) {
            $maks_sks_smt_slnjt = 20;
        } else if ($ipk < 2.5) {
            $maks_sks_smt_slnjt = 18;
        }

        return $this->khs_repository->updateBebanMaksSksKhs($id_khs, $maks_sks_smt_slnjt);
    }

    public function createKhs(string $id_transkrip, KartuHasilStudi $khs): bool
    {
        return $this->khs_repository->createKhs($id_transkrip, $khs);
    }

    public function getTranskrip(string $id): array
    {
        return $this->khs_repository->getTranskrip($id);
    }

    public function getKhs(string $id_transkrip, string $semester): ?KartuHasilStudi
    {
        return $this->khs_repository->getKhs($id_transkrip, $semester);
    }

    public function insertRincianKhs(string $id_khs, string $id_nilai): bool
    {
        return $this->khs_repository->insertRincianKhs($id_khs, $id_nilai);
    }

    public function getNilai(string $nim, string $tahun_akademik, string $semester): array
    {
        return $this->khs_repository->getNilai($nim, $tahun_akademik, $semester);
    }

    public function insertNilai(NilaiMhs $nilai_mhs): bool
    {
        return $this->khs_repository->insertNilai($nilai_mhs);
    }
}
