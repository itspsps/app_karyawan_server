<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\IdCardController;
use App\Http\Controllers\KPIController;
use App\Http\Controllers\SlipController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\KIBController;
use App\Http\Controllers\TIBController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\jabatanController;
use App\Http\Controllers\karyawanController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\SP\dashboardSPController;
use App\Http\Controllers\dashboardSPSController;
use App\Http\Controllers\dashboardSIPController;
use App\Http\Controllers\RekapDataController;
use App\Http\Controllers\HomeUserController;
use App\Http\Controllers\HistoryUserController;
use App\Http\Controllers\ProfileUserController;
use App\Http\Controllers\AbsenUserController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\IzinUserController;
use App\Http\Controllers\CutiUserController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\MappingShiftController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\PenugasanUserController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\RecruitmentController;
use App\Models\Jabatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use RealRashid\SweetAlert\Facades\Alert;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth:web', 'log.activity')->group(function () {
    Route::post('/logout', [authController::class, 'logout']);
    Route::put('/karyawan/proses-edit-shift/sp', [karyawanController::class, 'prosesEditShift']);
    Route::put('/karyawan/proses-edit-shift/sps', [karyawanController::class, 'prosesEditShift']);
    Route::put('/karyawan/proses-edit-shift/sip', [karyawanController::class, 'prosesEditShift']);
    Route::get('/id-card', [IdCardController::class, 'index']);
    Route::get('/kpi', [KPIController::class, 'index']);
    Route::get('/my-slip', [SlipController::class, 'index']);
    Route::get('/pengajuan', [PengajuanController::class, 'index']);
    Route::get('/kib', [KIBController::class, 'index']);
    Route::get('/tib', [TIBController::class, 'index']);
    Route::get('/my-division', [DivisionController::class, 'index']);
    Route::get('/my-location', [AbsenController::class, 'myLocation']);
    // Route::get('/absen', [AbsenController::class, 'index']);

    Route::get('/home', [HomeUserController::class, 'index'])->name('home');
    Route::get('/pusher', [HomeUserController::class, 'pusher'])->name('pusher');
    Route::post('/home/absenMasuk', [HomeUserController::class, 'absenMasuk'])->name('absenMasuk');
    Route::get('/datatableHome', [HomeUserController::class, 'datatableHome'])->name('datatableHome');
    Route::get('/get_count_absensi_home', [HomeUserController::class, 'get_count_absensi_home'])->name('get_count_absensi_home');
    Route::get('/home/absen', [HomeUserController::class, 'HomeAbsen'])->name('absen');
    Route::post('/home/absenPulang', [HomeUserController::class, 'absenPulang'])->name('absenPulang');
    Route::get('/home/maps/{lat}/{long}', [HomeUserController::class, 'maps']);
    Route::get('/home/my-absen', [HomeUserController::class, 'myAbsen']);
    Route::get('/home/my-location', [HomeUserController::class, 'myLocation']);
    Route::get('/home/form_datang_terlambat', [HomeUserController::class, 'form_datang_terlambat']);
    Route::post('/home/pulang_cepat_proses', [HomeUserController::class, 'proses_izin_pulang_cepats']);
    Route::get('/home/get_notif', [HomeUserController::class, 'get_notif']);
    Route::get('/home/get_notif_cuti', [HomeUserController::class, 'get_notif_cuti']);
    Route::get('/home/get_notif_penugasan', [HomeUserController::class, 'get_notif_penugasan']);
    Route::get('/home/create_face_id', [HomeUserController::class, 'create_face_id'])->name('create_face_id');
    Route::post('/home/savefaceid', [HomeUserController::class, 'savefaceid'])->name('savefaceid');

    Route::get('/absen/dashboard', [AbsenUserController::class, 'index']);
    route::get('/absen/data-absensi', [AbsenUserController::class, 'recordabsen']);
    route::get('get_table_absensi', [AbsenUserController::class, 'get_table_absensi'])->name('get_table_absensi');

    Route::get('/izin/dashboard', [IzinUserController::class, 'index']);
    Route::post('/izin/tambah-izin-proses', [IzinUserController::class, 'izinAbsen']);
    Route::post('/izin/edit-izin-proses', [IzinUserController::class, 'izinEditProses']);
    Route::get('/izin/detail/edit/{id}', [IzinUserController::class, 'izinEdit']);
    Route::get('/izin/approve/{id}', [IzinUserController::class, 'izinApprove']);
    Route::post('/izin/approve/proses', [IzinUserController::class, 'izinApproveProses']);
    Route::get('/izin/cetak_form_izin_user/{id}', [IzinUserController::class, 'cetak_form_izin_user']);
    Route::get('/izin/delete_izin/{id}', [IzinUserController::class, 'delete_izin']);
    Route::post('/izin/datang_terlambat_proses', [HomeUserController::class, 'proses_izin_datang_terlambat']);

    Route::get('/get_cuti', [CutiUserController::class, 'get_cuti']);
    Route::get('/cuti/dashboard', [CutiUserController::class, 'index']);
    Route::get('/cuti/detail/edit/{id}', [CutiUserController::class, 'cutiEdit']);
    Route::post('/cuti/edit-cuti-proses', [CutiUserController::class, 'cutiUpdateProses']);
    Route::get('/cuti/approve/{id}', [CutiUserController::class, 'cutiApprove']);
    Route::put('/cuti/tambah-cuti-proses', [CutiUserController::class, 'cutiAbsen']);
    Route::post('/cuti/approve/proses', [CutiUserController::class, 'cutiApproveProses']);
    Route::get('/cuti/cetak_form_cuti/cetak/{id}', [CutiUserController::class, 'cetak_form_cuti']);
    Route::get('/cuti/delete_cuti/{id}', [CutiUserController::class, 'delete_cuti']);

    Route::get('/penugasan/dashboard', [PenugasanUserController::class, 'index']);
    Route::get('/penugasan/detail/edit/{id}', [PenugasanUserController::class, 'penugasanEdit']);
    Route::get('/penugasan/detail/delete/{id}', [PenugasanUserController::class, 'penugasanDelete']);
    Route::post('/penugasan/detail/update/{id}', [PenugasanUserController::class, 'penugasanUpdate']);
    Route::get('/penugasan/approve/diminta/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::get('/penugasan/approve/disahkan/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::get('/penugasan/approve/diproseshrd/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::get('/penugasan/approve/diprosesfinance/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::post('/penugasan/approve/diminta/ttd/{id}', [PenugasanUserController::class, 'approvePenugasan']);

    Route::get('/penugasan/get_diminta', [PenugasanUserController::class, 'get_diminta']);
    Route::get('/penugasan/get_diminta_departemen', [PenugasanUserController::class, 'get_diminta_departemen']);
    Route::get('/penugasan/get_biaya_ditanggung', [PenugasanUserController::class, 'get_biaya_ditanggung']);
    Route::get('/penugasan/get_finance', [PenugasanUserController::class, 'get_finance']);
    Route::get('/penugasan/delete_penugasan/{id}', [PenugasanUserController::class, 'delete_penugasan']);
    Route::put('/penugasan/tambah-penugasan-proses', [PenugasanUserController::class, 'tambahPenugasan']);
    Route::put('/penugasan/approve/proses/{id}', [PenugasanUserController::class, 'penugasanApproveProses']);
    Route::get('/penugasan/cetak_form_penugasan/{id}', [PenugasanUserController::class, 'cetak_form_penugasan']);

    // Approval
    Route::get('/approval/dashboard', [ApprovalController::class, 'index']);


    // Menu bar
    Route::get('/history', [HistoryUserController::class, 'index'])->name('history');

    // Route::get('/absen', [HomeUserController::class, 'HomeAbsen'])->name('absen');
    Route::get('/profile', [ProfileUserController::class, 'index'])->name('profile');

    Route::put('/absen/masuk/{id}', [AbsenController::class, 'absenMasuk']);
    Route::put('/absen/pulang/{id}', [AbsenController::class, 'absenPulang']);
    Route::get('/maps/{lat}/{long}', [AbsenController::class, 'maps']);
    Route::get('/my-absen', [AbsenController::class, 'myAbsen']);
    Route::get('/lembur', [LemburController::class, 'index']);
    Route::post('/lembur/masuk', [LemburController::class, 'masuk']);
    Route::put('/lembur/pulang/{id}', [LemburController::class, 'pulang']);
    Route::get('/my-lembur', [LemburController::class, 'myLembur']);


    Route::post('/cuti/tambah', [CutiController::class, 'tambah']);
    Route::delete('/cuti/delete/{id}', [CutiController::class, 'delete']);
    Route::get('/cuti/edit/{id}', [CutiController::class, 'edit']);
    Route::put('/cuti/proses-edit/{id}', [CutiController::class, 'editProses']);
    Route::get('/my-profile', [KaryawanController::class, 'myProfile']);
    Route::put('/my-profile/update/{id}', [KaryawanController::class, 'myProfileUpdate']);
    Route::get('/my-profile/edit-password', [KaryawanController::class, 'editPassMyProfile']);
    Route::put('/my-profile/edit-password-proses/{id}', [KaryawanController::class, 'editPassMyProfileProses']);
    Route::get('/my-dokumen', [DokumenController::class, 'myDokumen']);
    Route::get('/my-dokumen/tambah', [DokumenController::class, 'myDokumenTambah']);
    Route::post('/my-dokumen/tambah-proses', [DokumenController::class, 'myDokumenTambahProses']);
    Route::get('/my-dokumen/edit/{id}', [DokumenController::class, 'myDokumenEdit']);
    Route::put('/my-dokumen/edit-proses/{id}', [DokumenController::class, 'myDokumenEditProses']);
    Route::delete('/my-dokumen/delete/{id}', [DokumenController::class, 'myDokumenDelete']);


    // Mapping Shift User
    Route::get('/mapping_shift/dashboard/', [MappingShiftController::class, 'index']);
    Route::post('/mapping_shift/prosesAddMappingShift', [MappingShiftController::class, 'prosesAddMappingShift']);
    Route::post('/mapping_shift/prosesEditMappingShift', [MappingShiftController::class, 'prosesEditMappingShift']);
});
Route::get('/tes', [authController::class, 'tes'])->name('tes');
Route::get('/', [authController::class, 'index'])->name('login');
Route::get('/register', [authController::class, 'register'])->middleware('guest');
Route::post('/register-proses', [authController::class, 'registerProses'])->middleware('guest');
Route::post('/login-proses', [authController::class, 'loginProses'])->middleware('guest');
Route::get('/dashboard', [dashboardController::class, 'index'])->middleware('admin');

Route::get('/dashboard/holding/sp', [dashboardController::class, 'index'])->middleware('admin');
Route::get('/dashboard/holding/sps', [dashboardController::class, 'index'])->middleware('admin');
Route::get('/dashboard/holding/sip', [dashboardController::class, 'index'])->middleware('admin');
Route::get('/dashboard/holding', [dashboardController::class, 'holding'])->middleware('admin');
Route::get('/activity-logs/sp', [ActivityLogController::class, 'index'])->middleware('admin');
Route::get('/activity-logs/sps', [ActivityLogController::class, 'index'])->middleware('admin');
Route::get('/activity-logs/sip', [ActivityLogController::class, 'index'])->middleware('admin');
Route::get('/activity-datatable/sp', [ActivityLogController::class, 'datatable'])->middleware('admin');
Route::get('/activity-datatable/sps', [ActivityLogController::class, 'datatable'])->middleware('admin');
Route::get('/activity-datatable/sip', [ActivityLogController::class, 'datatable'])->middleware('admin');
// Route::post('/logout', [authController::class, 'logout'])->middleware('auth');
Route::get('/karyawan/sp', [karyawanController::class, 'index'])->middleware('admin');
Route::get('/karyawan_bulanan-datatable/sp', [karyawanController::class, 'datatable_bulanan'])->middleware('admin');
Route::get('/karyawan_harian-datatable/sp', [karyawanController::class, 'datatable_harian'])->middleware('admin');
Route::get('/karyawan/sps', [karyawanController::class, 'index'])->middleware('admin');
Route::get('/karyawan_bulanan-datatable/sps', [karyawanController::class, 'datatable_bulanan'])->middleware('admin');
Route::get('/karyawan_harian-datatable/sps', [karyawanController::class, 'datatable_harian'])->middleware('admin');
Route::get('/karyawan/sip', [karyawanController::class, 'index'])->middleware('admin');
Route::get('/karyawan_bulanan-datatable/sip', [karyawanController::class, 'datatable_bulanan'])->middleware('admin');
Route::get('/karyawan_harian-datatable/sip', [karyawanController::class, 'datatable_harian'])->middleware('admin');
Route::get('/karyawan/tambah-karyawan/sp', [karyawanController::class, 'tambahKaryawan'])->middleware('admin');
Route::get('/karyawan/tambah-karyawan/sps', [karyawanController::class, 'tambahKaryawan'])->middleware('admin');
Route::get('/karyawan/tambah-karyawan/sip', [karyawanController::class, 'tambahKaryawan'])->middleware('admin');
Route::post('/karyawan/tambah-karyawan-proses/sp', [karyawanController::class, 'tambahKaryawanProses'])->middleware('admin');
Route::post('/karyawan/tambah-karyawan-proses/sps', [karyawanController::class, 'tambahKaryawanProses'])->middleware('admin');
Route::post('/karyawan/tambah-karyawan-proses/sip', [karyawanController::class, 'tambahKaryawanProses'])->middleware('admin');
Route::get('/karyawan/detail/{id}/sp', [karyawanController::class, 'detail'])->middleware('admin');
Route::get('/karyawan/detail/{id}/sps', [karyawanController::class, 'detail'])->middleware('admin');
Route::get('/karyawan/detail/{id}/sip', [karyawanController::class, 'detail'])->middleware('admin');
Route::post('/karyawan/proses-edit/{id}/sp', [karyawanController::class, 'editKaryawanProses'])->middleware('admin');
Route::post('/karyawan/proses-edit/{id}/sps', [karyawanController::class, 'editKaryawanProses'])->middleware('admin');
Route::post('/karyawan/proses-edit/{id}/sip', [karyawanController::class, 'editKaryawanProses'])->middleware('admin');
Route::get('/karyawan/delete/{id}/sp', [karyawanController::class, 'deleteKaryawan'])->middleware('admin');
Route::get('/karyawan/delete/{id}/sps', [karyawanController::class, 'deleteKaryawan'])->middleware('admin');
Route::get('/karyawan/delete/{id}/sip', [karyawanController::class, 'deleteKaryawan'])->middleware('admin');
Route::get('/karyawan/edit-password/{id}/sp', [karyawanController::class, 'editPassword'])->middleware('admin');
Route::get('/karyawan/edit-password/{id}/sps', [karyawanController::class, 'editPassword'])->middleware('admin');
Route::get('/karyawan/edit-password/{id}/sip', [karyawanController::class, 'editPassword'])->middleware('admin');
Route::post('/karyawan/edit-password-proses/{id}/sp', [karyawanController::class, 'editPasswordProses'])->middleware('admin');
Route::post('/karyawan/edit-password-proses/{id}/sps', [karyawanController::class, 'editPasswordProses'])->middleware('admin');
Route::post('/karyawan/edit-password-proses/{id}/sip', [karyawanController::class, 'editPasswordProses'])->middleware('admin');
Route::post('/karyawan/ImportKaryawan/sp', [karyawanController::class, 'ImportKaryawan'])->middleware('admin');
Route::post('/karyawan/ImportKaryawan/sps', [karyawanController::class, 'ImportKaryawan'])->middleware('admin');
Route::post('/karyawan/ImportKaryawan/sip', [karyawanController::class, 'ImportKaryawan'])->middleware('admin');
Route::post('/karyawan/ImportUpdateKaryawan/sp', [karyawanController::class, 'ImportUpdateKaryawan'])->middleware('admin');
Route::post('/karyawan/ImportUpdateKaryawan/sps', [karyawanController::class, 'ImportUpdateKaryawan'])->middleware('admin');
Route::post('/karyawan/ImportUpdateKaryawan/sip', [karyawanController::class, 'ImportUpdateKaryawan'])->middleware('admin');
Route::get('/karyawan/ExportKaryawan/sp', [karyawanController::class, 'ExportKaryawan'])->middleware('admin');
Route::get('/karyawan/ExportKaryawan/sps', [karyawanController::class, 'ExportKaryawan'])->middleware('admin');
Route::get('/karyawan/ExportKaryawan/sip', [karyawanController::class, 'ExportKaryawan'])->middleware('admin');
Route::get('/karyawan/pdfKaryawan/sps', [karyawanController::class, 'download_pdf_karyawan'])->middleware('admin');
Route::get('/karyawan/pdfKaryawan/sp', [karyawanController::class, 'download_pdf_karyawan'])->middleware('admin');
Route::get('/karyawan/pdfKaryawan/sip', [karyawanController::class, 'download_pdf_karyawan'])->middleware('admin');

Route::get('/karyawan_non_aktif/sp', [karyawanController::class, 'karyawan_non_aktif'])->middleware('admin');
Route::get('/karyawan_non_aktif/sps', [karyawanController::class, 'karyawan_non_aktif'])->middleware('admin');
Route::get('/karyawan_non_aktif/sip', [karyawanController::class, 'karyawan_non_aktif'])->middleware('admin');
Route::get('/database_karyawan_non_aktif/sp', [karyawanController::class, 'database_karyawan_non_aktif'])->middleware('admin');
Route::get('/database_karyawan_non_aktif/sps', [karyawanController::class, 'database_karyawan_non_aktif'])->middleware('admin');
Route::get('/database_karyawan_non_aktif/sip', [karyawanController::class, 'database_karyawan_non_aktif'])->middleware('admin');
Route::post('/karyawan/non_aktif_proses', [karyawanController::class, 'non_aktif_proses'])->middleware('admin');

Route::get('/karyawan_ingin_bergabung/sp', [karyawanController::class, 'karyawan_ingin_bergabung'])->middleware('admin');
Route::get('/karyawan_ingin_bergabung/sps', [karyawanController::class, 'karyawan_ingin_bergabung'])->middleware('admin');
Route::get('/karyawan_ingin_bergabung/sip', [karyawanController::class, 'karyawan_ingin_bergabung'])->middleware('admin');

Route::get('/karyawan/karyawan_masa_tenggang_kontrak/sp', [karyawanController::class, 'karyawan_masa_tenggang_kontrak'])->middleware('admin');
Route::get('/karyawan/karyawan_masa_tenggang_kontrak/sps', [karyawanController::class, 'karyawan_masa_tenggang_kontrak'])->middleware('admin');
Route::get('/karyawan/karyawan_masa_tenggang_kontrak/sip', [karyawanController::class, 'karyawan_masa_tenggang_kontrak'])->middleware('admin');
Route::get('/karyawan/database_karyawan_masa_tenggang_kontrak/sp', [karyawanController::class, 'database_karyawan_masa_tenggang_kontrak'])->middleware('admin');
Route::get('/karyawan/database_karyawan_masa_tenggang_kontrak/sps', [karyawanController::class, 'database_karyawan_masa_tenggang_kontrak'])->middleware('admin');
Route::get('/karyawan/database_karyawan_masa_tenggang_kontrak/sip', [karyawanController::class, 'database_karyawan_masa_tenggang_kontrak'])->middleware('admin');
Route::post('/karyawan/upddate_kontrak_proses', [karyawanController::class, 'upddate_kontrak_proses'])->middleware('admin');

Route::get('/users/sp', [karyawanController::class, 'index_users'])->middleware('admin');
Route::get('/users/sps', [karyawanController::class, 'index_users'])->middleware('admin');
Route::get('/users/sip', [karyawanController::class, 'index_users'])->middleware('admin');
Route::get('/users_bulanan-datatable/sp', [karyawanController::class, 'datatable_users_bulanan'])->middleware('admin');
Route::get('/users_harian-datatable/sp', [karyawanController::class, 'datatable_users_harian'])->middleware('admin');
Route::get('/users_bulanan-datatable/sps', [karyawanController::class, 'datatable_users_bulanan'])->middleware('admin');
Route::get('/users_harian-datatable/sps', [karyawanController::class, 'datatable_users_harian'])->middleware('admin');
Route::get('/users_bulanan-datatable/sip', [karyawanController::class, 'datatable_users_bulanan'])->middleware('admin');
Route::get('/users_harian-datatable/sip', [karyawanController::class, 'datatable_users_harian'])->middleware('admin');


// STRUKTUR ORGANISASI
Route::get('/struktur_organisasi/sp', [StrukturOrganisasiController::class, 'index'])->middleware('admin');
Route::get('/struktur_organisasi/sps', [StrukturOrganisasiController::class, 'index'])->middleware('admin');
Route::get('/struktur_organisasi/sip', [StrukturOrganisasiController::class, 'index'])->middleware('admin');

// CUTI
Route::get('/cuti/sp', [CutiController::class, 'index'])->middleware('admin');
Route::get('/cuti/sps', [CutiController::class, 'index'])->middleware('admin');
Route::get('/cuti/sip', [CutiController::class, 'index'])->middleware('admin');
Route::get('/cuti/datatable-cuti_tahunan/sp', [CutiController::class, 'datatable_cuti_tahunan'])->middleware('admin');
Route::get('/cuti/datatable-cuti_tahunan/sps', [CutiController::class, 'datatable_cuti_tahunan'])->middleware('admin');
Route::get('/cuti/datatable-cuti_tahunan/sip', [CutiController::class, 'datatable_cuti_tahunan'])->middleware('admin');
Route::get('/cuti/datatable-diluar_cuti_tahunan/sp', [CutiController::class, 'datatable_diluar_cuti_tahunan'])->middleware('admin');
Route::get('/cuti/datatable-diluar_cuti_tahunan/sps', [CutiController::class, 'datatable_diluar_cuti_tahunan'])->middleware('admin');
Route::get('/cuti/datatable-diluar_cuti_tahunan/sip', [CutiController::class, 'datatable_diluar_cuti_tahunan'])->middleware('admin');

// CETAK CUTI
Route::get('/cuti/cetak_form_cuti/{id}', [CutiController::class, 'cetak_form_cuti'])->middleware('admin');
Route::get('/cuti/ExportCuti/{kategori}/{holding}', [CutiController::class, 'ExportCuti'])->middleware('admin');


// IZIN
Route::get('/izin/sp', [IzinController::class, 'index'])->middleware('admin');
Route::get('/izin/sps', [IzinController::class, 'index'])->middleware('admin');
Route::get('/izin/sip', [IzinController::class, 'index'])->middleware('admin');
Route::get('/izin/datatable-terlambat/sp', [IzinController::class, 'datatable_terlambat'])->middleware('admin');
Route::get('/izin/datatable-terlambat/sps', [IzinController::class, 'datatable_terlambat'])->middleware('admin');
Route::get('/izin/datatable-terlambat/sip', [IzinController::class, 'datatable_terlambat'])->middleware('admin');
Route::get('/izin/datatable-pulangcepat/sp', [IzinController::class, 'datatable_pulangcepat'])->middleware('admin');
Route::get('/izin/datatable-pulangcepat/sps', [IzinController::class, 'datatable_pulangcepat'])->middleware('admin');
Route::get('/izin/datatable-pulangcepat/sip', [IzinController::class, 'datatable_pulangcepat'])->middleware('admin');
Route::get('/izin/datatable-keluar_kantor/sp', [IzinController::class, 'datatable_keluar_kantor'])->middleware('admin');
Route::get('/izin/datatable-keluar_kantor/sps', [IzinController::class, 'datatable_keluar_kantor'])->middleware('admin');
Route::get('/izin/datatable-keluar_kantor/sip', [IzinController::class, 'datatable_keluar_kantor'])->middleware('admin');
Route::get('/izin/datatable-sakit/sp', [IzinController::class, 'datatable_sakit'])->middleware('admin');
Route::get('/izin/datatable-sakit/sps', [IzinController::class, 'datatable_sakit'])->middleware('admin');
Route::get('/izin/datatable-sakit/sip', [IzinController::class, 'datatable_sakit'])->middleware('admin');
Route::get('/izin/datatable-tidak_masuk/sp', [IzinController::class, 'datatable_tidak_masuk'])->middleware('admin');
Route::get('/izin/datatable-tidak_masuk/sps', [IzinController::class, 'datatable_tidak_masuk'])->middleware('admin');
Route::get('/izin/datatable-tidak_masuk/sip', [IzinController::class, 'datatable_tidak_masuk'])->middleware('admin');

// CETAK IZIN
Route::get('/izin/cetak_form_izin/{id}', [IzinController::class, 'cetak_form_izin']);
Route::get('/izin/ExportIzin/{kategori}/{holding}', [IzinController::class, 'ExportIzin']);


// PENUGASAN
Route::get('/penugasan/sp', [PenugasanController::class, 'index'])->middleware('admin');
Route::get('/penugasan/sps', [PenugasanController::class, 'index'])->middleware('admin');
Route::get('/penugasan/sip', [PenugasanController::class, 'index'])->middleware('admin');
Route::get('/penugasan/datatable-penugasan/sp', [PenugasanController::class, 'datatable_penugasan'])->middleware('admin');
Route::get('/penugasan/datatable-penugasan/sps', [PenugasanController::class, 'datatable_penugasan'])->middleware('admin');
Route::get('/penugasan/datatable-penugasan/sip', [PenugasanController::class, 'datatable_penugasan'])->middleware('admin');
// CETAK PENUGASAN
Route::get('/penugasan/cetak_admin_form_penugasan/{id}', [PenugasanController::class, 'cetak_admin_form_penugasan'])->middleware('admin');
Route::get('/penugasan/ExportPenugasan/{kategori}/{holding}', [PenugasanController::class, 'ExportPenugasan']);

// SHIFT
Route::get('/shift/sp', [ShiftController::class, 'index'])->middleware('admin');
Route::get('/shift/sps', [ShiftController::class, 'index'])->middleware('admin');
Route::get('/shift/sip', [ShiftController::class, 'index'])->middleware('admin');
Route::get('/shift-datatable/sp', [ShiftController::class, 'datatable'])->middleware('admin');
Route::get('/shift-datatable/sps', [ShiftController::class, 'datatable'])->middleware('admin');
Route::get('/shift-datatable/sip', [ShiftController::class, 'datatable'])->middleware('admin');
Route::get('/shift/edit/sp', [ShiftController::class, 'edit'])->middleware('admin');
Route::get('/shift/edit/sps', [ShiftController::class, 'edit'])->middleware('admin');
Route::get('/shift/edit/sip', [ShiftController::class, 'edit'])->middleware('admin');
Route::get('/shift/create/sp', [ShiftController::class, 'create'])->middleware('admin');
Route::get('/shift/create/sps', [ShiftController::class, 'create'])->middleware('admin');
Route::get('/shift/create/sip', [ShiftController::class, 'create'])->middleware('admin');
Route::post('/shift/store/sp', [ShiftController::class, 'store'])->middleware('admin');
Route::post('/shift/store/sps', [ShiftController::class, 'store'])->middleware('admin');
Route::post('/shift/store/sip', [ShiftController::class, 'store'])->middleware('admin');
Route::post('/shift/update/sp', [ShiftController::class, 'update'])->middleware('admin');
Route::post('/shift/update/sps', [ShiftController::class, 'update'])->middleware('admin');
Route::post('/shift/update/sip', [ShiftController::class, 'update'])->middleware('admin');
Route::get('/shift/delete/{id}/sp', [ShiftController::class, 'destroy'])->middleware('admin');
Route::get('/shift/delete/{id}/sps', [ShiftController::class, 'destroy'])->middleware('admin');
Route::get('/shift/delete/{id}/sip', [ShiftController::class, 'destroy'])->middleware('admin');

// mapping shift
Route::get('/karyawan/shift/{id}/sp', [karyawanController::class, 'shift'])->middleware('admin');
Route::get('/karyawan/shift/{id}/sps', [karyawanController::class, 'shift'])->middleware('admin');
Route::get('/karyawan/shift/{id}/sip', [karyawanController::class, 'shift'])->middleware('admin');
Route::get('/karyawan/mapping_shift_datatable/{id}/sp', [karyawanController::class, 'mapping_shift_datatable'])->middleware('admin');
Route::get('/karyawan/mapping_shift_datatable/{id}/sps', [karyawanController::class, 'mapping_shift_datatable'])->middleware('admin');
Route::get('/karyawan/mapping_shift_datatable/{id}/sip', [karyawanController::class, 'mapping_shift_datatable'])->middleware('admin');
Route::post('/karyawan/shift/proses-tambah-shift/sp', [karyawanController::class, 'prosesTambahShift'])->middleware('admin');
Route::post('/karyawan/shift/proses-tambah-shift/sps', [karyawanController::class, 'prosesTambahShift'])->middleware('admin');
Route::post('/karyawan/shift/proses-tambah-shift/sip', [karyawanController::class, 'prosesTambahShift'])->middleware('admin');
Route::get('/karyawan/delete-shift/{id}/sp', [karyawanController::class, 'deleteShift'])->middleware('admin');
Route::get('/karyawan/delete-shift/{id}/sps', [karyawanController::class, 'deleteShift'])->middleware('admin');
Route::get('/karyawan/delete-shift/{id}/sip', [karyawanController::class, 'deleteShift'])->middleware('admin');
Route::get('/karyawan/edit-shift/{id}/sp', [karyawanController::class, 'editShift'])->middleware('admin');
Route::get('/karyawan/edit-shift/{id}/sps', [karyawanController::class, 'editShift'])->middleware('admin');
Route::get('/karyawan/edit-shift/{id}/sip', [karyawanController::class, 'editShift'])->middleware('admin');

// mapping shift NEW
Route::get('/karyawan/mapping_shift/sp', [MappingShiftController::class, 'mapping_shift_index'])->middleware('admin');
Route::get('/karyawan/mapping_shift/sps', [MappingShiftController::class, 'mapping_shift_index'])->middleware('admin');
Route::get('/karyawan/mapping_shift/sip', [MappingShiftController::class, 'mapping_shift_index'])->middleware('admin');
Route::get('/mapping_shift_datatable/sp', [MappingShiftController::class, 'mapping_shift_datatable'])->middleware('admin');
Route::get('/mapping_shift_datatable/sps', [MappingShiftController::class, 'mapping_shift_datatable'])->middleware('admin');
Route::get('/mapping_shift_datatable/sip', [MappingShiftController::class, 'mapping_shift_datatable'])->middleware('admin');
Route::post('/shift/proses-tambah-shift/sp', [MappingShiftController::class, 'prosesTambahShift'])->middleware('admin');
Route::post('/shift/proses-tambah-shift/sps', [MappingShiftController::class, 'prosesTambahShift'])->middleware('admin');
Route::post('/shift/proses-tambah-shift/sip', [MappingShiftController::class, 'prosesTambahShift'])->middleware('admin');
Route::get('/karyawan/delete-shift/sp', [MappingShiftController::class, 'deleteShift'])->middleware('admin');
Route::get('/karyawan/delete-shift/sps', [MappingShiftController::class, 'deleteShift'])->middleware('admin');
Route::get('/karyawan/delete-shift/sip', [MappingShiftController::class, 'deleteShift'])->middleware('admin');
Route::get('/karyawan/edit-shift/sp', [MappingShiftController::class, 'editShift'])->middleware('admin');
Route::get('/karyawan/edit-shift/sps', [MappingShiftController::class, 'editShift'])->middleware('admin');
Route::get('/karyawan/edit-shift/sip', [MappingShiftController::class, 'editShift'])->middleware('admin');

Route::get('/mapping_shift/get_divisi', [MappingShiftController::class, 'get_divisi'])->middleware('admin');
Route::get('/mapping_shift/get_bagian', [MappingShiftController::class, 'get_bagian'])->middleware('admin');
Route::get('/mapping_shift/get_jabatan', [MappingShiftController::class, 'get_jabatan'])->middleware('admin');

//
Route::get('/karyawan/get_departemen', [karyawanController::class, 'get_departemen'])->middleware('admin');
Route::get('/karyawan/get_divisi', [karyawanController::class, 'get_divisi'])->middleware('admin');
Route::get('/karyawan/get_bagian', [karyawanController::class, 'get_bagian'])->middleware('admin');
Route::get('/karyawan/get_jabatan', [karyawanController::class, 'get_jabatan'])->middleware('admin');

// INVENTARIS
Route::get('/inventaris/sp', [InventarisController::class, 'index'])->middleware('admin');
Route::get('/inventaris-datatable/sp', [InventarisController::class, 'datatable'])->middleware('admin');
Route::get('/inventaris/sps', [InventarisController::class, 'index'])->middleware('admin');
Route::get('/inventaris-datatable/sps', [InventarisController::class, 'datatable'])->middleware('admin');
Route::get('/inventaris/sip', [InventarisController::class, 'index'])->middleware('admin');
Route::get('/inventaris-datatable/sip', [InventarisController::class, 'datatable'])->middleware('admin');
Route::post('/inventaris/tambah-inventaris-proses/sp', [InventarisController::class, 'tambahInventarisProses'])->middleware('admin');
Route::post('/inventaris/tambah-inventaris-proses/sps', [InventarisController::class, 'tambahInventarisProses'])->middleware('admin');
Route::post('/inventaris/tambah-inventaris-proses/sip', [InventarisController::class, 'tambahInventarisProses'])->middleware('admin');
Route::post('/inventaris/proses-edit/sp', [InventarisController::class, 'editInventarisProses'])->middleware('admin');
Route::post('/inventaris/proses-edit/sps', [InventarisController::class, 'editInventarisProses'])->middleware('admin');
Route::post('/inventaris/proses-edit/sip', [InventarisController::class, 'editInventarisProses'])->middleware('admin');

// ACCESS
Route::get('/access/sp', [AccessController::class, 'index'])->middleware('admin');
Route::get('/access-datatable/sp', [AccessController::class, 'datatable'])->middleware('admin');
Route::get('/access/sps', [AccessController::class, 'index'])->middleware('admin');
Route::get('/access-datatable/sps', [AccessController::class, 'datatable'])->middleware('admin');
Route::get('/access/sip', [AccessController::class, 'index'])->middleware('admin');
Route::get('/access-datatable/sip', [AccessController::class, 'datatable'])->middleware('admin');
Route::get('/access/add_access/{id}/sp', [AccessController::class, 'add_access'])->middleware('admin');
Route::post('/access/access_save_add/sp', [AccessController::class, 'access_save_add'])->middleware('admin');
Route::get('/access/add_access/{id}/sps', [AccessController::class, 'add_access'])->middleware('admin');
Route::post('/access/access_save_add/sps', [AccessController::class, 'access_save_add'])->middleware('admin');
Route::get('/access/add_access/{id}/sip', [AccessController::class, 'add_access'])->middleware('admin');
Route::post('/access/access_save_add/sip', [AccessController::class, 'access_save_add'])->middleware('admin');

// Route::put('/karyawan/proses-edit-shift/{id}', [karyawanController::class, 'prosesEditShift'])->middleware('auth');
// Route::get('/absen', [AbsenController::class, 'index'])->middleware('auth');
// Route::get('/my-location', [AbsenController::class, 'myLocation'])->middleware('auth');
// Route::put('/absen/masuk/{id}', [AbsenController::class, 'absenMasuk'])->middleware('auth');
// Route::put('/absen/pulang/{id}', [AbsenController::class, 'absenPulang'])->middleware('auth');
Route::get('/data-absen', [AbsenController::class, 'dataAbsen'])->middleware('admin');
Route::get('/data-absen/{id}/edit-masuk', [AbsenController::class, 'editMasuk'])->middleware('admin');
// Route::get('/maps/{lat}/{long}', [AbsenController::class, 'maps'])->middleware('auth');
Route::put('/data-absen/{id}/proses-edit-masuk', [AbsenController::class, 'prosesEditMasuk'])->middleware('admin');
Route::get('/data-absen/{id}/edit-pulang', [AbsenController::class, 'editPulang'])->middleware('admin');
Route::put('/data-absen/{id}/proses-edit-pulang', [AbsenController::class, 'prosesEditPulang'])->middleware('admin');
Route::delete('/data-absen/{id}/delete', [AbsenController::class, 'deleteAdmin'])->middleware('admin');
// Route::get('/my-absen', [AbsenController::class, 'myAbsen'])->middleware('auth');
// Route::get('/lembur', [LemburController::class, 'index'])->middleware('auth');
// Route::post('/lembur/masuk', [LemburController::class, 'masuk'])->middleware('auth');
// Route::put('/lembur/pulang/{id}', [LemburController::class, 'pulang'])->middleware('auth');
Route::get('/data-lembur', [LemburController::class, 'dataLembur'])->middleware('admin');
// Route::get('/my-lembur', [LemburController::class, 'myLembur'])->middleware('auth');
Route::get('/rekap-data/sp', [RekapDataController::class, 'index'])->middleware('admin');
Route::get('/rekap-data/sps', [RekapDataController::class, 'index'])->middleware('admin');
Route::get('/rekap-data/sip', [RekapDataController::class, 'index'])->middleware('admin');
Route::get('/rekap-data/detail/{id}/sp', [RekapDataController::class, 'detail_index'])->middleware('admin');
Route::get('/rekap-data/detail/{id}/sps', [RekapDataController::class, 'detail_index'])->middleware('admin');
Route::get('/rekap-data/detail/{id}/sip', [RekapDataController::class, 'detail_index'])->middleware('admin');
Route::get('/rekap-data/ExportAbsensi/sp', [RekapDataController::class, 'ExportAbsensi'])->middleware('admin');
Route::get('/rekap-data/ExportAbsensi/sps', [RekapDataController::class, 'ExportAbsensi'])->middleware('admin');
Route::get('/rekap-data/ExportAbsensi/sip', [RekapDataController::class, 'ExportAbsensi'])->middleware('admin');
Route::get('/rekapdata-datatable/sp', [RekapDataController::class, 'datatable'])->middleware('admin');
Route::get('/rekapdata-datatable/sps', [RekapDataController::class, 'datatable'])->middleware('admin');
Route::get('/rekapdata-datatable/sip', [RekapDataController::class, 'datatable'])->middleware('admin');
Route::get('/rekapdata-detail_datatable/{id}/sp', [RekapDataController::class, 'detail_datatable'])->middleware('admin');
Route::get('/rekapdata-detail_datatable/{id}/sps', [RekapDataController::class, 'detail_datatable'])->middleware('admin');
Route::get('/rekapdata-detail_datatable/{id}/sip', [RekapDataController::class, 'detail_datatable'])->middleware('admin');
Route::get('/rekapdata-datatable_harian/sp', [RekapDataController::class, 'datatable_harian'])->middleware('admin');
Route::get('/rekapdata-datatable_harian/sps', [RekapDataController::class, 'datatable_harian'])->middleware('admin');
Route::get('/rekapdata-datatable_harian/sip', [RekapDataController::class, 'datatable_harian'])->middleware('admin');

// CETAK FORM
Route::get('/rekapdata/cetak_form_izin/{id}', [RekapDataController::class, 'cetak_form_izin']);
Route::get('/rekapdata/cetak_form_cuti/{id}', [RekapDataController::class, 'cetak_form_cuti']);
Route::get('/rekapdata/cetak_form_penugasan/{id}', [RekapDataController::class, 'cetak_form_penugasan']);

Route::get('/rekapdata/get_divisi', [RekapDataController::class, 'get_divisi'])->middleware('admin');
Route::get('/rekapdata/get_bagian', [RekapDataController::class, 'get_bagian'])->middleware('admin');
Route::get('/rekapdata/get_jabatan', [RekapDataController::class, 'get_jabatan'])->middleware('admin');

// Import
Route::post('/rekapdata/ImportAbsensi/sp', [RekapDataController::class, 'ImportAbsensi'])->middleware('admin');
Route::post('/rekapdata/ImportAbsensi/sps', [RekapDataController::class, 'ImportAbsensi'])->middleware('admin');
Route::post('/rekapdata/ImportAbsensi/sip', [RekapDataController::class, 'ImportAbsensi'])->middleware('admin');
// Route::get('/cuti', [CutiController::class, 'index'])->middleware('auth');
// Route::post('/cuti/tambah', [CutiController::class, 'tambah'])->middleware('auth');
// Route::delete('/cuti/delete/{id}', [CutiController::class, 'delete'])->middleware('auth');
// Route::get('/cuti/edit/{id}', [CutiController::class, 'edit'])->middleware('auth');
// Route::put('/cuti/proses-edit/{id}', [CutiController::class, 'editProses'])->middleware('auth');
Route::get('/data-cuti', [CutiController::class, 'dataCuti'])->middleware('admin');
Route::get('/data-cuti/tambah', [CutiController::class, 'tambahAdmin'])->middleware('admin');
Route::post('/data-cuti/getuserid', [CutiController::class, 'getUserId'])->middleware('admin');
Route::post('/data-cuti/proses-tambah', [CutiController::class, 'tambahAdminProses'])->middleware('admin');
Route::delete('/data-cuti/delete/{id}', [CutiController::class, 'deleteAdmin'])->middleware('admin');
Route::get('/data-cuti/edit/{id}', [CutiController::class, 'editAdmin'])->middleware('admin');
Route::put('/data-cuti/edit-proses/{id}', [CutiController::class, 'editAdminProses'])->middleware('admin');
// Route::get('/my-profile', [KaryawanController::class, 'myProfile'])->middleware('auth');
// Route::put('/my-profile/update/{id}', [KaryawanController::class, 'myProfileUpdate'])->middleware('auth');
// Route::get('/my-profile/edit-password', [KaryawanController::class, 'editPassMyProfile'])->middleware('auth');
// Route::put('/my-profile/edit-password-proses/{id}', [KaryawanController::class, 'editPassMyProfileProses'])->middleware('auth');
Route::get('/lokasi-kantor/sp', [LokasiController::class, 'index'])->middleware('admin');
Route::get('/lokasi-kantor/sps', [LokasiController::class, 'index'])->middleware('admin');
Route::get('/lokasi-kantor/sip', [LokasiController::class, 'index'])->middleware('admin');
Route::get('/lokasi-kantor/tambah_lokasi/sp', [LokasiController::class, 'tambah_lokasi'])->middleware('admin');
Route::get('/lokasi-kantor/tambah_lokasi/sps', [LokasiController::class, 'tambah_lokasi'])->middleware('admin');
Route::get('/lokasi-kantor/tambah_lokasi/sip', [LokasiController::class, 'tambah_lokasi'])->middleware('admin');
Route::get('/lokasi-datatable/sp', [LokasiController::class, 'datatable'])->middleware('admin');
Route::get('/lokasi-datatable/sps', [LokasiController::class, 'datatable'])->middleware('admin');
Route::get('/lokasi-datatable/sip', [LokasiController::class, 'datatable'])->middleware('admin');
Route::post('/lokasi-kantor/add/sp', [LokasiController::class, 'addLokasi'])->middleware('admin');
Route::post('/lokasi-kantor/add/sps', [LokasiController::class, 'addLokasi'])->middleware('admin');
Route::post('/lokasi-kantor/add/sip', [LokasiController::class, 'addLokasi'])->middleware('admin');
Route::post('/lokasi-kantor/edit/sp', [LokasiController::class, 'updateLokasi'])->middleware('admin');
Route::post('/lokasi-kantor/edit/sps', [LokasiController::class, 'updateLokasi'])->middleware('admin');
Route::post('/lokasi-kantor/edit/sip', [LokasiController::class, 'updateLokasi'])->middleware('admin');
Route::get('/lokasi-kantor/delete/{id}/sp', [LokasiController::class, 'deleteLokasi'])->middleware('admin');
Route::get('/lokasi-kantor/delete/{id}/sps', [LokasiController::class, 'deleteLokasi'])->middleware('admin');
Route::get('/lokasi-kantor/delete/{id}/sip', [LokasiController::class, 'deleteLokasi'])->middleware('admin');
Route::put('/lokasi-kantor/radius/{id}/sp', [LokasiController::class, 'updateRadiusLokasi'])->middleware('admin');
Route::put('/lokasi-kantor/radius/{id}/sps', [LokasiController::class, 'updateRadiusLokasi'])->middleware('admin');
Route::put('/lokasi-kantor/radius/{id}/sip', [LokasiController::class, 'updateRadiusLokasi'])->middleware('admin');
Route::get('/lokasi_kantor/get_lokasi', [LokasiController::class, 'get_lokasi'])->middleware('admin');

// reset Cuti
Route::get('/reset-cuti/sp', [KaryawanController::class, 'resetCuti'])->middleware('admin');
Route::get('/reset-cuti/sps', [KaryawanController::class, 'resetCuti'])->middleware('admin');
Route::get('/reset-cuti/sip', [KaryawanController::class, 'resetCuti'])->middleware('admin');
Route::put('/reset-cuti/{id}/sp', [KaryawanController::class, 'resetCutiProses'])->middleware('admin');
Route::put('/reset-cuti/{id}/sps', [KaryawanController::class, 'resetCutiProses'])->middleware('admin');
Route::put('/reset-cuti/{id}/sip', [KaryawanController::class, 'resetCutiProses'])->middleware('admin');
// MASTER DEPARTEMEN
Route::get('/departemen/sp', [DepartemenController::class, 'index'])->middleware('admin');
Route::get('/departemen/sps', [DepartemenController::class, 'index'])->middleware('admin');
Route::get('/departemen/sip', [DepartemenController::class, 'index'])->middleware('admin');
Route::get('/departemen-datatable/sp', [DepartemenController::class, 'datatable'])->middleware('admin');
Route::get('/departemen-datatable/sps', [DepartemenController::class, 'datatable'])->middleware('admin');
Route::get('/departemen-datatable/sip', [DepartemenController::class, 'datatable'])->middleware('admin');
Route::get('/departemen/create/sp', [DepartemenController::class, 'create'])->middleware('admin');
Route::get('/departemen/create/sps', [DepartemenController::class, 'create'])->middleware('admin');
Route::get('/departemen/create/sip', [DepartemenController::class, 'create'])->middleware('admin');
Route::post('/departemen/insert/sp', [DepartemenController::class, 'insert'])->middleware('admin');
Route::post('/departemen/insert/sps', [DepartemenController::class, 'insert'])->middleware('admin');
Route::post('/departemen/insert/sip', [DepartemenController::class, 'insert'])->middleware('admin');
Route::get('/departemen/edit/{id}/sp', [DepartemenController::class, 'edit'])->middleware('admin');
Route::get('/departemen/edit/{id}/sps', [DepartemenController::class, 'edit'])->middleware('admin');
Route::get('/departemen/edit/{id}/sip', [DepartemenController::class, 'edit'])->middleware('admin');
Route::post('/departemen/update/sp', [DepartemenController::class, 'update'])->middleware('admin');
Route::post('/departemen/update/sps', [DepartemenController::class, 'update'])->middleware('admin');
Route::post('/departemen/update/sip', [DepartemenController::class, 'update'])->middleware('admin');
Route::get('/departemen/delete/{id}/sp', [DepartemenController::class, 'delete'])->middleware('admin');
Route::get('/departemen/delete/{id}/sps', [DepartemenController::class, 'delete'])->middleware('admin');
Route::get('/departemen/delete/{id}/sip', [DepartemenController::class, 'delete'])->middleware('admin');
Route::post('/departemen/ImportDepartemen/sp', [DepartemenController::class, 'ImportDepartemen'])->middleware('admin');
Route::post('/departemen/ImportDepartemen/sps', [DepartemenController::class, 'ImportDepartemen'])->middleware('admin');
Route::post('/departemen/ImportDepartemen/sip', [DepartemenController::class, 'ImportDepartemen'])->middleware('admin');
Route::get('/departemen/divisi-datatable/{id?}/sp', [DepartemenController::class, 'divisi_datatable'])->middleware('admin');
Route::get('/departemen/divisi-datatable/{id?}/sps', [DepartemenController::class, 'divisi_datatable'])->middleware('admin');
Route::get('/departemen/divisi-datatable/{id?}/sip', [DepartemenController::class, 'divisi_datatable'])->middleware('admin');
Route::get('/departemen/karyawandepartemen-datatable/{id?}/sp', [DepartemenController::class, 'karyawandepartemen_datatable'])->middleware('admin');
Route::get('/departemen/karyawandepartemen-datatable/{id?}/sps', [DepartemenController::class, 'karyawandepartemen_datatable'])->middleware('admin');
Route::get('/departemen/karyawandepartemen-datatable/{id?}/sip', [DepartemenController::class, 'karyawandepartemen_datatable'])->middleware('admin');
// MASTER DIVISI
Route::get('/divisi/sp', [DivisiController::class, 'index'])->middleware('admin');
Route::get('/divisi/sps', [DivisiController::class, 'index'])->middleware('admin');
Route::get('/divisi/sip', [DivisiController::class, 'index'])->middleware('admin');
Route::get('/divisi-datatable/sp', [DivisiController::class, 'datatable'])->middleware('admin');
Route::get('/divisi-datatable/sps', [DivisiController::class, 'datatable'])->middleware('admin');
Route::get('/divisi-datatable/sip', [DivisiController::class, 'datatable'])->middleware('admin');
Route::get('/divisi/create/sp', [DivisiController::class, 'create'])->middleware('admin');
Route::get('/divisi/create/sps', [DivisiController::class, 'create'])->middleware('admin');
Route::get('/divisi/create/sip', [DivisiController::class, 'create'])->middleware('admin');
Route::post('/divisi/insert/sp', [DivisiController::class, 'insert'])->middleware('admin');
Route::post('/divisi/insert/sps', [DivisiController::class, 'insert'])->middleware('admin');
Route::post('/divisi/insert/sip', [DivisiController::class, 'insert'])->middleware('admin');
Route::get('/divisi/edit/{id}/sp', [DivisiController::class, 'edit'])->middleware('admin');
Route::get('/divisi/edit/{id}/sps', [DivisiController::class, 'edit'])->middleware('admin');
Route::get('/divisi/edit/{id}/sip', [DivisiController::class, 'edit'])->middleware('admin');
Route::post('/divisi/update/sp', [DivisiController::class, 'update'])->middleware('admin');
Route::post('/divisi/update/sps', [DivisiController::class, 'update'])->middleware('admin');
Route::post('/divisi/update/sip', [DivisiController::class, 'update'])->middleware('admin');
Route::get('/divisi/delete/{id}/sp', [DivisiController::class, 'delete'])->middleware('admin');
Route::get('/divisi/delete/{id}/sps', [DivisiController::class, 'delete'])->middleware('admin');
Route::get('/divisi/delete/{id}/sip', [DivisiController::class, 'delete'])->middleware('admin');
Route::post('/divisi/ImportDivisi/sp', [DivisiController::class, 'ImportDivisi'])->middleware('admin');
Route::post('/divisi/ImportDivisi/sps', [DivisiController::class, 'ImportDivisi'])->middleware('admin');
Route::post('/divisi/ImportDivisi/sip', [DivisiController::class, 'ImportDivisi'])->middleware('admin');
Route::get('/divisi/bagian-datatable/{id?}/sp', [DivisiController::class, 'bagian_datatable'])->middleware('admin');
Route::get('/divisi/bagian-datatable/{id?}/sps', [DivisiController::class, 'bagian_datatable'])->middleware('admin');
Route::get('/divisi/bagian-datatable/{id?}/sip', [DivisiController::class, 'bagian_datatable'])->middleware('admin');
Route::get('/divisi/karyawandivisi-datatable/{id?}/sp', [DivisiController::class, 'karyawandivisi_datatable'])->middleware('admin');
Route::get('/divisi/karyawandivisi-datatable/{id?}/sps', [DivisiController::class, 'karyawandivisi_datatable'])->middleware('admin');
Route::get('/divisi/karyawandivisi-datatable/{id?}/sip', [DivisiController::class, 'karyawandivisi_datatable'])->middleware('admin');

// MASTER BAGIAN
Route::get('/bagian/sp', [BagianController::class, 'index'])->middleware('admin');
Route::get('/bagian/sps', [BagianController::class, 'index'])->middleware('admin');
Route::get('/bagian/sip', [BagianController::class, 'index'])->middleware('admin');
Route::get('/bagian-datatable/sp', [BagianController::class, 'datatable'])->middleware('admin');
Route::get('/bagian-datatable/sps', [BagianController::class, 'datatable'])->middleware('admin');
Route::get('/bagian-datatable/sip', [BagianController::class, 'datatable'])->middleware('admin');
Route::get('/bagian/create/sp', [BagianController::class, 'create'])->middleware('admin');
Route::get('/bagian/create/sps', [BagianController::class, 'create'])->middleware('admin');
Route::get('/bagian/create/sip', [BagianController::class, 'create'])->middleware('admin');
Route::post('/bagian/insert/sp', [BagianController::class, 'insert'])->middleware('admin');
Route::post('/bagian/insert/sps', [BagianController::class, 'insert'])->middleware('admin');
Route::post('/bagian/insert/sip', [BagianController::class, 'insert'])->middleware('admin');
Route::get('/bagian/edit/{id}/sp', [BagianController::class, 'edit'])->middleware('admin');
Route::get('/bagian/edit/{id}/sps', [BagianController::class, 'edit'])->middleware('admin');
Route::get('/bagian/edit/{id}/sip', [BagianController::class, 'edit'])->middleware('admin');
Route::post('/bagian/update/sp', [BagianController::class, 'update'])->middleware('admin');
Route::post('/bagian/update/sps', [BagianController::class, 'update'])->middleware('admin');
Route::post('/bagian/update/sip', [BagianController::class, 'update'])->middleware('admin');
Route::get('/bagian/delete/{id}/sp', [BagianController::class, 'delete'])->middleware('admin');
Route::get('/bagian/delete/{id}/sps', [BagianController::class, 'delete'])->middleware('admin');
Route::get('/bagian/delete/{id}/sip', [BagianController::class, 'delete'])->middleware('admin');
Route::get('/bagian/get_divisi/{id}', [BagianController::class, 'get_divisi'])->middleware('admin');
Route::post('/bagian/ImportBagian/sp', [BagianController::class, 'ImportBagian'])->middleware('admin');
Route::post('/bagian/ImportBagian/sps', [BagianController::class, 'ImportBagian'])->middleware('admin');
Route::post('/bagian/ImportBagian/sip', [BagianController::class, 'ImportBagian'])->middleware('admin');
Route::get('/jabatan/jabatan-datatable/{id?}/sp', [BagianController::class, 'jabatan_datatable'])->middleware('admin');
Route::get('/jabatan/jabatan-datatable/{id?}/sps', [BagianController::class, 'jabatan_datatable'])->middleware('admin');
Route::get('/jabatan/jabatan-datatable/{id?}/sip', [BagianController::class, 'jabatan_datatable'])->middleware('admin');
Route::get('/jabatan/karyawanjabatan-datatable/{id?}/sp', [BagianController::class, 'karyawanjabatan_datatable'])->middleware('admin');
Route::get('/jabatan/karyawanjabatan-datatable/{id?}/sps', [BagianController::class, 'karyawanjabatan_datatable'])->middleware('admin');
Route::get('/jabatan/karyawanjabatan-datatable/{id?}/sip', [BagianController::class, 'karyawanjabatan_datatable'])->middleware('admin');


// MASTER JABATAN
Route::get('/jabatan/sp', [jabatanController::class, 'index'])->middleware('admin');
Route::get('/jabatan/sps', [jabatanController::class, 'index'])->middleware('admin');
Route::get('/jabatan/sip', [jabatanController::class, 'index'])->middleware('admin');
Route::get('/detail_jabatan/{id?}/sp', [jabatanController::class, 'detail_jabatan'])->middleware('admin');
Route::get('/detail_jabatan/{id?}/sps', [jabatanController::class, 'detail_jabatan'])->middleware('admin');
Route::get('/detail_jabatan/{id?}/sip', [jabatanController::class, 'detail_jabatan'])->middleware('admin');
Route::get('/jabatan-datatable/{id?}/sp', [jabatanController::class, 'datatable'])->middleware('admin');
Route::get('/jabatan-datatable/{id?}/sps', [jabatanController::class, 'datatable'])->middleware('admin');
Route::get('/jabatan-datatable/{id?}/sip', [jabatanController::class, 'datatable'])->middleware('admin');
Route::get('/bawahanjabatan-datatable/{id?}/sp', [jabatanController::class, 'bawahan_datatable'])->middleware('admin');
Route::get('/bawahanjabatan-datatable/{id?}/sps', [jabatanController::class, 'bawahan_datatable'])->middleware('admin');
Route::get('/bawahanjabatan-datatable/{id?}/sip', [jabatanController::class, 'bawahan_datatable'])->middleware('admin');
Route::get('/karyawanjabatan-datatable/{id?}/sp', [jabatanController::class, 'karyawan_datatable'])->middleware('admin');
Route::get('/karyawanjabatan-datatable/{id?}/sps', [jabatanController::class, 'karyawan_datatable'])->middleware('admin');
Route::get('/karyawanjabatan-datatable/{id?}/sip', [jabatanController::class, 'karyawan_datatable'])->middleware('admin');
Route::get('/jabatan/create/sp', [jabatanController::class, 'create'])->middleware('admin');
Route::get('/jabatan/create/sps', [jabatanController::class, 'create'])->middleware('admin');
Route::get('/jabatan/create/sip', [jabatanController::class, 'create'])->middleware('admin');
Route::post('/jabatan/insert/sp', [jabatanController::class, 'insert'])->middleware('admin');
Route::post('/jabatan/insert/sps', [jabatanController::class, 'insert'])->middleware('admin');
Route::post('/jabatan/insert/sip', [jabatanController::class, 'insert'])->middleware('admin');
Route::get('/jabatan/edit/{id}/sp', [jabatanController::class, 'edit'])->middleware('admin');
Route::get('/jabatan/edit/{id}/sps', [jabatanController::class, 'edit'])->middleware('admin');
Route::get('/jabatan/edit/{id}/sip', [jabatanController::class, 'edit'])->middleware('admin');
Route::post('/jabatan/update/sp', [jabatanController::class, 'update'])->middleware('admin');
Route::post('/jabatan/update/sps', [jabatanController::class, 'update'])->middleware('admin');
Route::post('/jabatan/update/sip', [jabatanController::class, 'update'])->middleware('admin');
Route::get('/jabatan/delete/{id}/sp', [jabatanController::class, 'delete'])->middleware('admin');
Route::get('/jabatan/delete/{id}/sps', [jabatanController::class, 'delete'])->middleware('admin');
Route::get('/jabatan/delete/{id}/sip', [jabatanController::class, 'delete'])->middleware('admin');
Route::get('/jabatan/get_bagian/{id}', [jabatanController::class, 'get_bagian'])->middleware('admin');
Route::post('/jabatan/ImportJabatan/sp', [jabatanController::class, 'ImportJabatan'])->middleware('admin');
Route::post('/jabatan/ImportJabatan/sps', [jabatanController::class, 'ImportJabatan'])->middleware('admin');
Route::post('/jabatan/ImportJabatan/sip', [jabatanController::class, 'ImportJabatan'])->middleware('admin');

Route::get('/atasan/get_jabatan/sp', [jabatanController::class, 'get_atasan'])->middleware('admin');
Route::get('/atasan/get_jabatan/sps', [jabatanController::class, 'get_atasan'])->middleware('admin');
Route::get('/atasan/get_jabatan/sip', [jabatanController::class, 'get_atasan'])->middleware('admin');
Route::get('/atasan/edit/get_jabatan/sp', [jabatanController::class, 'get_atasan_edit'])->middleware('admin');
Route::get('/atasan/edit/get_jabatan/sps', [jabatanController::class, 'get_atasan_edit'])->middleware('admin');
Route::get('/atasan/edit/get_jabatan/sip', [jabatanController::class, 'get_atasan_edit'])->middleware('admin');
// GET ATASAN 1 & 2
Route::get('/karyawan/atasan/get_jabatan/sp', [karyawanController::class, 'get_atasan'])->middleware('admin');
Route::get('/karyawan/atasan/get_jabatan/sps', [karyawanController::class, 'get_atasan'])->middleware('admin');
Route::get('/karyawan/atasan/get_jabatan/sip', [karyawanController::class, 'get_atasan'])->middleware('admin');
Route::get('/karyawan/atasan2/get_jabatan/sp', [karyawanController::class, 'get_atasan2'])->middleware('admin');
Route::get('/karyawan/atasan2/get_jabatan/sps', [karyawanController::class, 'get_atasan2'])->middleware('admin');
Route::get('/karyawan/atasan2/get_jabatan/sip', [karyawanController::class, 'get_atasan2'])->middleware('admin');
// GET ALAMAT
Route::get('/karyawan/get_kabupaten/{id}', [karyawanController::class, 'get_kabupaten'])->middleware('admin');
Route::get('/karyawan/get_kecamatan/{id}', [karyawanController::class, 'get_kecamatan'])->middleware('admin');
Route::get('/karyawan/get_desa/{id}', [karyawanController::class, 'get_desa'])->middleware('admin');
// DOKUMEN
Route::get('/dokumen', [DokumenController::class, 'index'])->middleware('admin');
Route::get('/dokumen/tambah', [DokumenController::class, 'tambah'])->middleware('admin');
Route::post('/dokumen/tambah-proses', [DokumenController::class, 'tambahProses'])->middleware('admin');
Route::get('/dokumen/edit/{id}', [DokumenController::class, 'edit'])->middleware('admin');
Route::put('/dokumen/edit-proses/{id}', [DokumenController::class, 'editProses'])->middleware('admin');
Route::delete('/dokumen/delete/{id}', [DokumenController::class, 'delete'])->middleware('admin');
// Route::get('/my-dokumen', [DokumenController::class, 'myDokumen'])->middleware('auth');
// Route::get('/my-dokumen/tambah', [DokumenController::class, 'myDokumenTambah'])->middleware('auth');
// Route::post('/my-dokumen/tambah-proses', [DokumenController::class, 'myDokumenTambahProses'])->middleware('auth');
// Route::get('/my-dokumen/edit/{id}', [DokumenController::class, 'myDokumenEdit'])->middleware('auth');
// Route::put('/my-dokumen/edit-proses/{id}', [DokumenController::class, 'myDokumenEditProses'])->middleware('auth');
// Route::delete('/my-dokumen/delete/{id}', [DokumenController::class, 'myDokumenDelete'])->middleware('auth');
Route::get('/logout', [authController::class, 'logout'])->name('logout');

// RECRUITMENT DASHBOARD ADMIN
Route::get('/recruitment/sp', [RecruitmentController::class, 'index'])->middleware('admin');
Route::get('/recruitment/sps', [RecruitmentController::class, 'index'])->middleware('admin');
Route::get('/recruitment/sip', [RecruitmentController::class, 'index'])->middleware('admin');
Route::get('/recruitment-datatable/sp', [RecruitmentController::class, 'datatable'])->middleware('admin');
Route::get('/recruitment-datatable/sps', [RecruitmentController::class, 'datatable'])->middleware('admin');
Route::get('/recruitment-datatable/sip', [RecruitmentController::class, 'datatable'])->middleware('admin');
Route::get('/recruitment/create/sp', [RecruitmentController::class, 'create'])->middleware('admin');
Route::get('/recruitment/create/sps', [RecruitmentController::class, 'create'])->middleware('admin');
Route::get('/recruitment/create/sip', [RecruitmentController::class, 'create'])->middleware('admin');
Route::post('/recruitment/insert/sp', [RecruitmentController::class, 'insert'])->middleware('admin');
Route::post('/recruitment/insert/sps', [RecruitmentController::class, 'insert'])->middleware('admin');
Route::post('/recruitment/insert/sip', [RecruitmentController::class, 'insert'])->middleware('admin');
Route::get('/recruitment/edit/{id}/sp', [RecruitmentController::class, 'edit'])->middleware('admin');
Route::get('/recruitment/edit/{id}/sps', [RecruitmentController::class, 'edit'])->middleware('admin');
Route::get('/recruitment/edit/{id}/sip', [RecruitmentController::class, 'edit'])->middleware('admin');
Route::post('/recruitment/update/sp', [RecruitmentController::class, 'update'])->middleware('admin');
Route::post('/recruitment/update/sps', [RecruitmentController::class, 'update'])->middleware('admin');
Route::post('/recruitment/update/sip', [RecruitmentController::class, 'update'])->middleware('admin');
Route::get('/recruitment/delete/{id}/sp', [RecruitmentController::class, 'delete'])->middleware('admin');
Route::get('/recruitment/delete/{id}/sps', [RecruitmentController::class, 'delete'])->middleware('admin');
Route::get('/recruitment/delete/{id}/sip', [RecruitmentController::class, 'delete'])->middleware('admin');

// RECRUITMENT DASHBOARD USER
Route::get('/recruitment-user', [RecruitmentController::class, 'recruitment_user']);
Route::get('/recruitment-user/add/{id}', [RecruitmentController::class, 'recruitment_user_add']);
Route::post('/recruitment-user/add-proccess/{id}', [RecruitmentController::class, 'recruitment_user_add_proccess']);


Route::get('optimize', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');


    Artisan::call('view:cache');
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('optimize');

    // Alert::success('success', 'Optimization Success..');
    echo 'ok';
    // return redirect('/home')->with('success', 'Optimization Success..');
});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
