<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Clairence\Controller\KhsController;
use Clairence\Controller\KrsController;
use Clairence\Controller\MataKuliahController;
use Clairence\Controller\NilaiController;
use Clairence\Controller\PaymentController;
use Clairence\Controller\ScheduleController;
use Clairence\Controller\TahunAkademikController;
use Clairence\Controller\TokenController;
use Clairence\Controller\UserController;
use Clairence\Middleware\AuthMiddleware;
use Clairence\Router\Router;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: DELETE, POST, GET, OPTIONS');

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    header("Content-Type: *");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods: DELETE, POST, GET, OPTIONS');

    http_response_code(200);
    exit();
}

//  Login
Router::add("POST", "/login", UserController::class, "login");

// Get Student Schedules
Router::add("POST", "/student/schedules", ScheduleController::class, "getStudentSchedules", [AuthMiddleware::class]);

// Get Lecturer Schedules
Router::add("POST", "/lecturer/schedules", ScheduleController::class, "getLecturerSchedules", [AuthMiddleware::class]);

// Get User
Router::add("GET", "/get_user/([0-9]*)", UserController::class, "getUser", [AuthMiddleware::class]);

// Get mahasiswa aktif
Router::add("GET", "/mahasiswa/aktif", UserController::class, "getMahasiswaAktif", [AuthMiddleware::class]);

// Get Lecturers
Router::add("GET", "/lecturers", UserController::class, "getAllLecturer", [AuthMiddleware::class]);

// Get Mata Kuliah
Router::add("GET", "/learning_subjects", MataKuliahController::class, "getAllMataKuliah", [AuthMiddleware::class]);

// Get Mata Kuliah
Router::add("GET", "/master/matkul", MataKuliahController::class, "getAllMataKuliahMaster", [AuthMiddleware::class]);

// Create Mata Kuliah
Router::add("POST", "/create/matkul", MataKuliahController::class, "createMataKuliah", [AuthMiddleware::class]);

// Create Mata Kuliah
Router::add("POST", "/update/matkul", MataKuliahController::class, "updateMataKuliah", [AuthMiddleware::class]);

// Create Mata Kuliah
Router::add("POST", "/delete/matkul", MataKuliahController::class, "deleteMataKuliah", [AuthMiddleware::class]);

// Get All Schedules
Router::add("POST", "/all_schedules", ScheduleController::class, "getAllSchedule", [AuthMiddleware::class]);

// Get Schedules by Day
Router::add("POST", "/schedules/day", ScheduleController::class, "getSchedulesByDay", [AuthMiddleware::class]);

// Create New Schedule
Router::add("POST", "/create/schedule", ScheduleController::class, "addSchedule", [AuthMiddleware::class]);

// Update Schedule
Router::add("POST", "/update/schedule", ScheduleController::class, "updateSchedule", [AuthMiddleware::class]);

// Delete Schedule
Router::add("POST", "/delete/schedule", ScheduleController::class, "removeSchedule", [AuthMiddleware::class]);

// Get Krs Schedule
Router::add("GET", "/krs_schedule", KrsController::class, "getKrsSchedule", [AuthMiddleware::class]);

// create Krs 
Router::add("POST", "/create/krs", KrsController::class, "createStudentKrs", [AuthMiddleware::class]);

// get Krs 
Router::add("POST", "/krs", KrsController::class, "getStudentKrs", [AuthMiddleware::class]);

// get Krs 
Router::add("GET", "/get/krs", KrsController::class, "getAllKrs", [AuthMiddleware::class]);

// commit krs
Router::add("POST", "/commit/krs", KrsController::class, "commitKrs", [AuthMiddleware::class]);

// approve krs
Router::add("POST", "/approve/krs", KrsController::class, "approveKrs", [AuthMiddleware::class]);

// approve krs
Router::add("POST", "/unapprove/krs", KrsController::class, "unapproveKrs", [AuthMiddleware::class]);

// update Krs
Router::add("POST", "/update/krs", KrsController::class, "updateKrs", [AuthMiddleware::class]);

// Get Transkrip 
Router::add("POST", "/transkrip", KhsController::class, "getTranskrip", [AuthMiddleware::class]);

// Get Transkrip 
Router::add("POST", "/transkrip_terinci", KhsController::class, "getTranskripPerSemester", [AuthMiddleware::class]);

// create Transkrip 
Router::add("POST", "/create/transkrip", KhsController::class, "createTranskrip", [AuthMiddleware::class]);

// create Khs 
Router::add("POST", "/create/khs", KhsController::class, "createTranskrip", [AuthMiddleware::class]);

// Get daftar matkul yang diajar oleh dosen 
Router::add("POST", "/dosen/daftar_matkul", NilaiController::class, "getMataKuliah", [AuthMiddleware::class]);

// get daftar nilai matkul 
Router::add("POST", "/mata_kuliah/nilai", NilaiController::class, "getDaftarNilai", [AuthMiddleware::class]);

// get tahun akademik untuk list matkul dosen
Router::add("GET", "/dosen/tahun_akademik", NilaiController::class, "getTahunAkademik", [AuthMiddleware::class]);

// update nilai mhs (untuk dosen)
Router::add("POST", "/nilai/mahasiswa", NilaiController::class, "updateNilai", [AuthMiddleware::class]);

// Set tahun akademik
Router::add("POST", "/set/tahun_akademik", TahunAkademikController::class, "setTahunAkademik", [AuthMiddleware::class]);

// Set jadwal KRS
Router::add("POST", "/set/jadwal_krs", TahunAkademikController::class, "setJadwalKrs", [AuthMiddleware::class]);

// Get tagihan mahasiswa
Router::add("POST", "/mahasiswa/tagihan", PaymentController::class, "getTagihanMahasiswa", [AuthMiddleware::class]);

// Get tagihan mahasiswa
Router::add("POST", "/mahasiswa/tagihan/rincian", PaymentController::class, "getRincianTagihan", [AuthMiddleware::class]);

// Create transaksi (Virtual account)
Router::add("POST", "/charge", PaymentController::class, "bayarTagihan", [AuthMiddleware::class]);

// Update status transaksi
Router::add("POST", "/mahasiswa/pembayaran/status", PaymentController::class, "getStatusTransaksi", [AuthMiddleware::class]);

// Get All list histori transaksi
Router::add("POST", "/mahasiswa/transaksi/histori", PaymentController::class, "getAllHistoriTransaksi", [AuthMiddleware::class]);

// if Auth failed, send token refresh request to this route
Router::add("POST", "/refresh", TokenController::class, "refresh");

Router::run();
