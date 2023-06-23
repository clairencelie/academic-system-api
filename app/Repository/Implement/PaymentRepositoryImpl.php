<?php

namespace Clairence\Repository\Implement;

use Clairence\Entity\PaymentResponse;
use Clairence\Entity\RincianTagihan;
use Clairence\Entity\RincianTagihanDb;
use Clairence\Entity\TagihanPerkuliahan;
use Clairence\Entity\TagihanPerkuliahanDb;
use Clairence\Repository\Interface\PaymentRepositoryInterface;
use Exception;
use PDO;

class PaymentRepositoryImpl implements PaymentRepositoryInterface
{
    public function __construct(private PDO $conn)
    {
    }

    public function getTagihanPerkuliahan(string $nim): array
    {
        $listTagihanPerkuliahan = [];

        $sql = <<<SQL
            SELECT *
            FROM pembayaran_kuliah
            WHERE nim = :nim;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                // Buat objek tagihanPerkuliahan
                $tagihanPerkuliahan = TagihanPerkuliahanDb::createTagihanPerkuliahanDb($data);
                $listTagihanPerkuliahan[] = $tagihanPerkuliahan->jsonSerialize();
            }
        }

        return $listTagihanPerkuliahan;
    }

    public function getAllHistoryTransaksi(string $nim): array
    {
        $list_histori_transaksi = [];

        $sql = <<<SQL
            SELECT histori_transaksi.id_transaksi,
                    histori_transaksi.id_pembayaran_kuliah,
                    histori_transaksi.id_order,
                    histori_transaksi.jenis_pembayaran,
                    histori_transaksi.bank,
                    histori_transaksi.no_va,
                    histori_transaksi.total_pembayaran,
                    histori_transaksi.waktu_transaksi,
                    histori_transaksi.status_transaksi,
                    histori_transaksi.waktu_kedaluwarsa
            FROM histori_transaksi
            JOIN pembayaran_kuliah ON (histori_transaksi.id_pembayaran_kuliah = pembayaran_kuliah.id_pembayaran_kuliah)
            JOIN mahasiswa ON (pembayaran_kuliah.nim = mahasiswa.nim)
            WHERE mahasiswa.nim = :nim;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);

        $statement->execute();

        while ($data = $statement->fetch(PDO::FETCH_ASSOC)){ 
            $list_histori_transaksi[] = $data;
        }

        return $list_histori_transaksi;
    }

    public function getTagihanPerkuliahanPerTA(string $nim, string $tahunAkademik, string $semester, string $kategori): ?TagihanPerkuliahanDb
    {
        $sql = <<<SQL
            SELECT *
            FROM pembayaran_kuliah
            WHERE nim = :nim AND tahun_akademik = :tahunAkademik AND semester = :semester AND kategori = :kategori;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('nim', $nim);
        $statement->bindParam('tahunAkademik', $tahunAkademik);
        $statement->bindParam('semester', $semester);
        $statement->bindParam('kategori', $kategori);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $data = $statement->fetch(PDO::FETCH_ASSOC);
            // Buat objek tagihanPerkuliahan
            $tagihanPerkuliahan = TagihanPerkuliahanDb::createTagihanPerkuliahanDb($data);
            return $tagihanPerkuliahan;
        }

        return null;
    }

    public function setTagihanPerkuliahan(TagihanPerkuliahan $tagihanPerkuliahan): bool
    {
        $id_pembayaran_kuliah = $tagihanPerkuliahan->getIdPembayaranKuliah();
        $nim = $tagihanPerkuliahan->getNim();
        $total_tagihan = $tagihanPerkuliahan->getTotalTagihan();
        $sisa_pembayaran = $tagihanPerkuliahan->getSisaPembayaran();
        $status_pembayaran = $tagihanPerkuliahan->getStatusPembayaran();
        $kategori = $tagihanPerkuliahan->getKategori();
        $metode_pembayaran = $tagihanPerkuliahan->getMetodePembayaran();
        $tahun_akademik = $tagihanPerkuliahan->getTahunAkademik();
        $semester = $tagihanPerkuliahan->getSemester();

        $sql = <<<SQL
            INSERT INTO pembayaran_kuliah (
                id_pembayaran_kuliah,
                nim,
                total_tagihan,
                sisa_pembayaran,
                status_pembayaran,
                kategori,
                metode_pembayaran,
                tahun_akademik,
                semester
            ) VALUES (
                :id_pembayaran_kuliah,
                :nim,
                :total_tagihan,
                :sisa_pembayaran,
                :status_pembayaran,
                :kategori,
                :metode_pembayaran,
                :tahun_akademik,
                :semester
            )
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_pembayaran_kuliah', $id_pembayaran_kuliah);
        $statement->bindParam('nim', $nim);
        $statement->bindParam('total_tagihan', $total_tagihan);
        $statement->bindParam('sisa_pembayaran', $sisa_pembayaran);
        $statement->bindParam('status_pembayaran', $status_pembayaran);
        $statement->bindParam('kategori', $kategori);
        $statement->bindParam('metode_pembayaran', $metode_pembayaran);
        $statement->bindParam('tahun_akademik', $tahun_akademik);
        $statement->bindParam('semester', $semester);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function updateTagihanPerkuliahan(string $idTagihanPerkuliahan, int $sisaPembayaran, string $statusPembayaran): bool
    {
        // $sql = <<<SQL
        //     SELECT *
        //     FROM pembayaran_kuliah
        //     WHERE id_pembayaran_kuliah = :idTagihanPerkuliahan AND sisa_pembayaran = :sisaPembayaran AND status_pembayaran = :statusPembayaran;
        // SQL;

        // $statement = $this->conn->prepare($sql);

        // $statement->bindParam('sisaPembayaran', $sisaPembayaran);
        // $statement->bindParam('idTagihanPembayaran', $idTagihanPembayaran);

        // $statement->execute();

        // if ($statement->rowCount() > 0) {
        //     throw new Exception('Data sama, tidak perlu diupdate');
        //     return false;
        // }

        $sql = <<<SQL
            UPDATE pembayaran_kuliah
            SET sisa_pembayaran = :sisaPembayaran,
                status_pembayaran = :statusPembayaran
            WHERE id_pembayaran_kuliah = :idTagihanPerkuliahan;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('sisaPembayaran', $sisaPembayaran);
        $statement->bindParam('statusPembayaran', $statusPembayaran);
        $statement->bindParam('idTagihanPerkuliahan', $idTagihanPerkuliahan);

        $statement->execute();

        return true;
    }

    public function insertRincianTagihan(RincianTagihan $rincianTagihan): bool
    {

        $id_pembayaran_kuliah = $rincianTagihan->getIdTagihanPerkuliahan();
        $item = $rincianTagihan->getItem();
        $jumlah_item = $rincianTagihan->getJumlahItem();
        $harga_item = $rincianTagihan->getHargaItem();
        $total_harga_item = $rincianTagihan->getTotalHargaItem();

        $sql = <<<SQL
            INSERT INTO rincian_tagihan (
                id_pembayaran_kuliah,
                item,
                jumlah_item,
                harga_item,
                total_harga_item
            ) VALUES (
                :id_pembayaran_kuliah,
                :item,
                :jumlah_item,
                :harga_item,
                :total_harga_item
            );
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_pembayaran_kuliah', $id_pembayaran_kuliah);
        $statement->bindParam('item', $item);
        $statement->bindParam('jumlah_item', $jumlah_item);
        $statement->bindParam('harga_item', $harga_item);
        $statement->bindParam('total_harga_item', $total_harga_item);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getRincianTagihan(string $idTagihanPerkuliahan): array
    {
        $listRincianTagihan = [];

        $sql = <<<SQL
            SELECT *
            FROM rincian_tagihan
            WHERE id_pembayaran_kuliah = :idTagihanPerkuliahan;
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('idTagihanPerkuliahan', $idTagihanPerkuliahan);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
                // Buat objek tagihanPerkuliahan
                $rincianTagihan = RincianTagihanDb::createRincianTagihanDb($data);
                $listRincianTagihan[] = $rincianTagihan->jsonSerialize();
            }
        }

        return $listRincianTagihan;
    }

    public function insertHistoryTransaksi(string $idPembayaranKuliah, PaymentResponse $paymentResponse): bool
    {
        $id_transaksi = $paymentResponse->getTransactionId();
        $id_order = $paymentResponse->getOrderId();
        $jenis_pembayaran = $paymentResponse->getPaymentType();
        $bank = $paymentResponse->getBank();
        $no_va = $paymentResponse->getVaNumber();
        $total_pembayaran = $paymentResponse->getGrossAmount();
        $waktu_transaksi = $paymentResponse->getTransactionTime();
        $status_transaksi = $paymentResponse->getTransactionStatus();
        $waktu_kedaluwarsa = $paymentResponse->getExpiryTime();

        $sql = <<<SQL
            INSERT INTO histori_transaksi (
                id_transaksi,
                id_pembayaran_kuliah,
                id_order,
                jenis_pembayaran,
                bank,
                no_va,
                total_pembayaran,
                waktu_transaksi,
                status_transaksi,
                waktu_kedaluwarsa
            ) VALUES (
                :id_transaksi,
                :id_pembayaran_kuliah,
                :id_order,
                :jenis_pembayaran,
                :bank,
                :no_va,
                :total_pembayaran,
                :waktu_transaksi,
                :status_transaksi,
                :waktu_kedaluwarsa
            );
        SQL;

        $statement = $this->conn->prepare($sql);

        $statement->bindParam('id_transaksi', $id_transaksi);
        $statement->bindParam('id_pembayaran_kuliah', $idPembayaranKuliah);
        $statement->bindParam('id_order', $id_order);
        $statement->bindParam('jenis_pembayaran', $jenis_pembayaran);
        $statement->bindParam('bank', $bank);
        $statement->bindParam('no_va', $no_va);
        $statement->bindParam('total_pembayaran', $total_pembayaran);
        $statement->bindParam('waktu_transaksi', $waktu_transaksi);
        $statement->bindParam('status_transaksi', $status_transaksi);
        $statement->bindParam('waktu_kedaluwarsa', $waktu_kedaluwarsa);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function updateHistoryTransaksi(PaymentResponse $paymentResponse): bool
    {
        $id_transaksi = $paymentResponse->getTransactionId();
        $id_order = $paymentResponse->getOrderId();
        $jenis_pembayaran = $paymentResponse->getPaymentType();
        $bank = $paymentResponse->getBank();
        $no_va = $paymentResponse->getVaNumber();
        $total_pembayaran = $paymentResponse->getGrossAmount();
        $waktu_transaksi = $paymentResponse->getTransactionTime();
        $status_transaksi = $paymentResponse->getTransactionStatus();
        $waktu_kedaluwarsa = $paymentResponse->getExpiryTime();

        $sql = <<<SQL
            UPDATE histori_transaksi
            SET id_transaksi = :id_transaksi,
                jenis_pembayaran = :jenis_pembayaran,
                bank = :bank,
                no_va = :no_va,
                total_pembayaran = :total_pembayaran,
                waktu_transaksi = :waktu_transaksi,
                status_transaksi = :status_transaksi,
                waktu_kedaluwarsa = :waktu_kedaluwarsa
            WHERE id_order = :id_order;
        SQL;

        $statement = $this->conn->prepare($sql);
        $statement->bindParam('id_transaksi', $id_transaksi);
        $statement->bindParam('jenis_pembayaran', $jenis_pembayaran);
        $statement->bindParam('bank', $bank);
        $statement->bindParam('no_va', $no_va);
        $statement->bindParam('total_pembayaran', $total_pembayaran);
        $statement->bindParam('waktu_transaksi', $waktu_transaksi);
        $statement->bindParam('status_transaksi', $status_transaksi);
        $statement->bindParam('waktu_kedaluwarsa', $waktu_kedaluwarsa);
        $statement->bindParam('id_order', $id_order);

        $statement->execute();

        return true;
    }
}
