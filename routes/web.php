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
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\IzinUserController;
use App\Http\Controllers\CutiUserController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FingerController;
use App\Http\Controllers\HoldingController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\KaryawanKesehatanController;
use App\Http\Controllers\KaryawanRiwayatController;
use App\Http\Controllers\MappingShiftController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\PenugasanUserController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\RecruitmentLaporanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserKaryawanController;
use App\Http\Controllers\RecruitmentUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\SummernoteController;
use App\Http\Controllers\UjianUserController;
use App\Models\Jabatan;
use App\Models\FingerUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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
use Maliklibs\Zkteco\Lib\ZKTeco;

Route::get('/test-user', function () {
    return DB::connection('solution_access')->select('SELECT TOP 5 * FROM USERINFO');
});
Route::middleware('auth:web', 'log.activity')->group(function () {
    Route::post('/logout', [authController::class, 'logout']);
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
    Route::get('/izin/get_filter_month', [IzinUserController::class, 'get_filter_month']);
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
    Route::get('/cuti/get_filter_month', [CutiUserController::class, 'get_filter_month']);

    Route::get('/penugasan/dashboard', [PenugasanUserController::class, 'index']);
    Route::get('/penugasan/detail/edit/{id}', [PenugasanUserController::class, 'penugasanEdit']);
    Route::get('/penugasan/detail/delete/{id}', [PenugasanUserController::class, 'penugasanDelete']);
    Route::post('/penugasan/detail/update/{id}', [PenugasanUserController::class, 'penugasanUpdate']);
    Route::get('/penugasan/approve/diminta/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::get('/penugasan/approve/disahkan/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::get('/penugasan/approve/diproseshrd/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::get('/penugasan/approve/diprosesfinance/show/{id}', [PenugasanUserController::class, 'approveShow']);
    Route::post('/penugasan/approve/diminta/ttd/{id}', [PenugasanUserController::class, 'approvePenugasan']);
    Route::get('/penugasan/get_filter_month', [PenugasanUserController::class, 'get_filter_month']);

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

    //Interview Manager
    Route::get('/interview/dashboard', [InterviewController::class, 'index']);
    Route::get('/interview/detail/{id}', [InterviewController::class, 'detail']);
    Route::get('/interview/pdfUserKaryawan/{id}', [InterviewController::class, 'pdfUserKaryawan']);
    Route::post('/interview/approve/proses', [InterviewController::class, 'prosesInterview']);




    // Menu bar
    Route::get('/history', [HistoryUserController::class, 'index'])->name('history');

    // Route::get('/absen', [HomeUserController::class, 'HomeAbsen'])->name('absen');
    // PROFILE USER
    Route::get('/profile', [ProfileUserController::class, 'index'])->name('profile');

    Route::get('/detail_profile', [ProfileUserController::class, 'detail_profile']);
    Route::post('/save_detail_profile', [ProfileUserController::class, 'save_detail_profile']);

    Route::get('/detail_alamat', [ProfileUserController::class, 'detail_alamat']);
    Route::post('/save_detail_alamat', [ProfileUserController::class, 'save_detail_alamat']);

    Route::get('/detail_account', [ProfileUserController::class, 'detail_account']);
    Route::post('/save_detail_account', [ProfileUserController::class, 'save_detail_account']);

    Route::get('/change_photoprofile_camera', [ProfileUserController::class, 'change_photoprofile_camera']);
    Route::post('/save_capture_profile', [ProfileUserController::class, 'save_capture_profile']);

    Route::get('/profile/lihat_jabatan', [ProfileUserController::class, 'lihat_jabatan']);
    Route::get('/profile/lihat_kontrak_kerja', [ProfileUserController::class, 'lihat_kontrak_kerja']);
    Route::get('/profile/lihat_dokumen', [ProfileUserController::class, 'lihat_dokumen']);
    Route::get('/profile/lihat_struktur_organisasi', [ProfileUserController::class, 'lihat_struktur_organisasi']);
    Route::get('/profile/lihat_rekan_kerja', [ProfileUserController::class, 'lihat_rekan_kerja']);


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
});
Route::get('/tes', [authController::class, 'tes'])->name('tes');
Route::get('/', [authController::class, 'index'])->name('login');
Route::get('/register', [authController::class, 'register'])->middleware('guest');
Route::post('/register-proses', [authController::class, 'registerProses'])->middleware('guest');
Route::post('/login-proses', [authController::class, 'loginProses'])->middleware('guest');
Route::get('/dashboard', [dashboardController::class, 'index'])->middleware('admin');

Route::middleware('admin')->group(function () {
    Route::get('/dashboard/hrd/{holding}', [dashboardController::class, 'index']);
    Route::get('/dashboard/portal/{holding}', [dashboardController::class, 'index_portal']);
    Route::get('/dashboard/option/{holding}', [dashboardController::class, 'dashboard_option']);
    Route::get('/dashboard/holding', [dashboardController::class, 'holding'])->name('dashboard_holding');
    Route::get('/get_grafik_absensi_karyawan/{holding}', [dashboardController::class, 'get_grafik_absensi_karyawan']);
    Route::get('/graph_Dashboard_All/{holding}', [dashboardController::class, 'graph_Dashboard_All']);

    Route::get('/activity-logs/sp', [ActivityLogController::class, 'index']);
    Route::get('/activity-logs/sps', [ActivityLogController::class, 'index']);
    Route::get('/activity-logs/sip', [ActivityLogController::class, 'index']);
    Route::get('/activity-datatable/sp', [ActivityLogController::class, 'datatable']);
    Route::get('/activity-datatable/sps', [ActivityLogController::class, 'datatable']);
    Route::get('/activity-datatable/sip', [ActivityLogController::class, 'datatable']);
    // MASTER KARYAWAN
    Route::put('/karyawan/proses-edit-shift/sp', [karyawanController::class, 'prosesEditShift']);
    Route::put('/karyawan/proses-edit-shift/sps', [karyawanController::class, 'prosesEditShift']);
    Route::put('/karyawan/proses-edit-shift/sip', [karyawanController::class, 'prosesEditShift']);
    Route::get('/karyawan/{holding}', [karyawanController::class, 'index']);
    Route::get('/karyawan_bulanan-datatable/{holding}', [karyawanController::class, 'datatable_bulanan']);
    Route::get('/karyawan_harian-datatable/{holding}', [karyawanController::class, 'datatable_harian']);
    Route::get('/karyawan/{holding}', [karyawanController::class, 'index']);
    Route::get('/karyawan_harian-datatable/{holding}', [karyawanController::class, 'datatable_harian']);
    Route::get('/karyawan/tambah-karyawan/{holding}', [karyawanController::class, 'tambahKaryawan']);
    Route::post('/karyawan/tambah-karyawan-proses', [karyawanController::class, 'tambahKaryawanProses']);
    Route::get('/karyawan/detail/{id}/{holding}', [karyawanController::class, 'detail']);
    Route::post('/karyawan/proses-edit/{id}/sp', [karyawanController::class, 'editKaryawanProses']);
    Route::post('/karyawan/proses-edit/{id}/sps', [karyawanController::class, 'editKaryawanProses']);
    Route::post('/karyawan/proses-edit/{id}/sip', [karyawanController::class, 'editKaryawanProses']);
    Route::get('/karyawan/delete/{id}/sp', [karyawanController::class, 'deleteKaryawan']);
    Route::get('/karyawan/delete/{id}/sps', [karyawanController::class, 'deleteKaryawan']);
    Route::get('/karyawan/delete/{id}/sip', [karyawanController::class, 'deleteKaryawan']);
    Route::post('/karyawan/ImportKaryawan/sp', [karyawanController::class, 'ImportKaryawan']);
    Route::post('/karyawan/ImportKaryawan/sps', [karyawanController::class, 'ImportKaryawan']);
    Route::post('/karyawan/ImportKaryawan/sip', [karyawanController::class, 'ImportKaryawan']);
    Route::post('/karyawan/ImportUpdateKaryawan/sp', [karyawanController::class, 'ImportUpdateKaryawan']);
    Route::post('/karyawan/ImportUpdateKaryawan/sps', [karyawanController::class, 'ImportUpdateKaryawan']);
    Route::post('/karyawan/ImportUpdateKaryawan/sip', [karyawanController::class, 'ImportUpdateKaryawan']);
    Route::get('/karyawan/ExportKaryawan/{holding}', [karyawanController::class, 'ExportKaryawan']);
    Route::get('/karyawan/pdfKaryawan/{holding}', [karyawanController::class, 'download_pdf_karyawan']);

    // PENDIDIKAN KARYAWAN
    Route::get('/karyawan/pendidikan/{id}', [karyawanController::class, 'pendidikan_datatable']);
    Route::post('/karyawan/AddPendidikan/', [karyawanController::class, 'add_pendidikan']);
    Route::post('/karyawan/UpdatePendidikan/', [karyawanController::class, 'update_pendidikan']);
    Route::post('/karyawan/DeletePendidikan/', [karyawanController::class, 'delete_pendidikan']);

    // Riwayat Pekerjaan
    Route::get('/karyawan/riwayat/{id}', [KaryawanRiwayatController::class, 'riwayat_datatable']);
    Route::post('/karyawan/riwayat_update', [karyawanRiwayatController::class, 'riwayat_update']);
    Route::post('/karyawan/riwayat_post', [karyawanRiwayatController::class, 'riwayat_post']);
    Route::post('/karyawan/delete_riwayat/{id}', [karyawanRiwayatController::class, 'delete_riwayat']);
    Route::get('/karyawan/button_riwayat/{id?}', [karyawanRiwayatController::class, 'button_riwayat'])->name('button_riwayat');

    // kesehatan
    Route::get('/cpanel/cv/kesehatan/kesehatan_get/{id}', [KaryawanKesehatanController::class, 'kesehatan_get'])->name('kesehatan_get');
    Route::post('/cpanel/cv/kesehatan/kesehatan_post', [KaryawanKesehatanController::class, 'kesehatan_post'])->name('kesehatan_post');

    Route::post('/cpanel/cv/kesehatan/pengobatan_post', [KaryawanKesehatanController::class, 'pengobatan_post'])->name('pengobatan_post');
    Route::post('/cpanel/cv/kesehatan/pengobatan_delete', [KaryawanKesehatanController::class, 'pengobatan_delete'])->name('pengobatan_delete');
    Route::post('/cpanel/cv/kesehatan/pengobatan_reset', [KaryawanKesehatanController::class, 'pengobatan_reset'])->name('pengobatan_reset');
    Route::get('/cpanel/cv/kesehatan/dt_pengobatan/{id}', [KaryawanKesehatanController::class, 'dt_pengobatan'])->name('dt_pengobatan');
    Route::get('/cpanel/cv/kesehatan/pengobatan_count/{id}', [KaryawanKesehatanController::class, 'pengobatan_count'])->name('pengobatan_count');

    Route::post('/cpanel/cv/kesehatan/rumah_sakit_post', [KaryawanKesehatanController::class, 'rumah_sakit_post'])->name('rumah_sakit_post');
    Route::post('/cpanel/cv/kesehatan/rumah_sakit_delete', [KaryawanKesehatanController::class, 'rumah_sakit_delete'])->name('rumah_sakit_delete');
    Route::post('/cpanel/cv/kesehatan/rumah_sakit_reset', [KaryawanKesehatanController::class, 'rumah_sakit_reset'])->name('rumah_sakit_reset');
    Route::get('/cpanel/cv/kesehatan/dt_rumah_sakit/{id}', [KaryawanKesehatanController::class, 'dt_rumah_sakit'])->name('dt_rumah_sakit');
    Route::get('/cpanel/cv/kesehatan/rumah_sakit_count/{id}', [KaryawanKesehatanController::class, 'rumah_sakit_count'])->name('rumah_sakit_count');


    Route::post('/cpanel/cv/kesehatan/kecelakaan_post', [KaryawanKesehatanController::class, 'kecelakaan_post'])->name('kecelakaan_post');
    Route::post('/cpanel/cv/kesehatan/kecelakaan_delete', [KaryawanKesehatanController::class, 'kecelakaan_delete'])->name('kecelakaan_delete');
    Route::post('/cpanel/cv/kesehatan/kecelakaan_reset', [KaryawanKesehatanController::class, 'kecelakaan_reset'])->name('kecelakaan_reset');
    Route::get('/cpanel/cv/kesehatan/rumah_sakit_count', [KaryawanKesehatanController::class, 'rumah_sakit_count'])->name('rumah_sakit_count');
    Route::get('/cpanel/cv/kesehatan/dt_kecelakaan/{id}', [KaryawanKesehatanController::class, 'dt_kecelakaan'])->name('dt_kecelakaan');
    Route::get('/cpanel/cv/kesehatan/kecelakaan_count/{id}', [KaryawanKesehatanController::class, 'kecelakaan_count'])->name('kecelakaan_count');
    // kesehatan end

    // KEAHLIAN KARYAWAN
    Route::get('/karyawan/keahlian/{id}', [karyawanController::class, 'keahlian_datatable']);
    Route::post('/karyawan/AddKeahlian/', [karyawanController::class, 'add_keahlian']);
    Route::post('/karyawan/UpdateKeahlian/', [karyawanController::class, 'update_keahlian']);
    Route::post('/karyawan/DeleteKeahlian/', [karyawanController::class, 'delete_keahlian']);

    Route::get('/karyawan_non_aktif/sp', [karyawanController::class, 'karyawan_non_aktif']);
    Route::get('/karyawan_non_aktif/sps', [karyawanController::class, 'karyawan_non_aktif']);
    Route::get('/karyawan_non_aktif/sip', [karyawanController::class, 'karyawan_non_aktif']);
    Route::get('/database_karyawan_non_aktif/sp', [karyawanController::class, 'database_karyawan_non_aktif']);
    Route::get('/database_karyawan_non_aktif/sps', [karyawanController::class, 'database_karyawan_non_aktif']);
    Route::get('/database_karyawan_non_aktif/sip', [karyawanController::class, 'database_karyawan_non_aktif']);
    Route::post('/karyawan/non_aktif_proses', [karyawanController::class, 'non_aktif_proses']);

    Route::get('/karyawan_ingin_bergabung/sp', [karyawanController::class, 'karyawan_ingin_bergabung']);
    Route::get('/karyawan_ingin_bergabung/sps', [karyawanController::class, 'karyawan_ingin_bergabung']);
    Route::get('/karyawan_ingin_bergabung/sip', [karyawanController::class, 'karyawan_ingin_bergabung']);

    Route::get('/karyawan/karyawan_masa_tenggang_kontrak/sp', [karyawanController::class, 'karyawan_masa_tenggang_kontrak']);
    Route::get('/karyawan/karyawan_masa_tenggang_kontrak/sps', [karyawanController::class, 'karyawan_masa_tenggang_kontrak']);
    Route::get('/karyawan/karyawan_masa_tenggang_kontrak/sip', [karyawanController::class, 'karyawan_masa_tenggang_kontrak']);
    Route::get('/karyawan/database_karyawan_masa_tenggang_kontrak/sp', [karyawanController::class, 'database_karyawan_masa_tenggang_kontrak']);
    Route::get('/karyawan/database_karyawan_masa_tenggang_kontrak/sps', [karyawanController::class, 'database_karyawan_masa_tenggang_kontrak']);
    Route::get('/karyawan/database_karyawan_masa_tenggang_kontrak/sip', [karyawanController::class, 'database_karyawan_masa_tenggang_kontrak']);
    Route::post('/karyawan/update_kontrak_proses', [karyawanController::class, 'update_kontrak_proses']);

    Route::get('/users/{holding}', [UserKaryawanController::class, 'index_users']);
    Route::get('/users_finger/{holding}', [UserKaryawanController::class, 'index_finger']);
    Route::post('/users/prosesTambahUser/sp', [UserKaryawanController::class, 'prosesTambahUser']);
    Route::post('/users/prosesTambahUser/sps', [UserKaryawanController::class, 'prosesTambahUser']);
    Route::post('/users/prosesTambahUser/sip', [UserKaryawanController::class, 'prosesTambahUser']);
    Route::get('/users_bulanan-datatable/{holding}', [UserKaryawanController::class, 'datatable_users_bulanan']);
    Route::get('/users_harian-datatable/{holding}', [UserKaryawanController::class, 'datatable_users_harian']);
    Route::get('/users_finger-datatable/{holding}', [UserKaryawanController::class, 'users_finger_datatable']);
    Route::get('/users/edit-password/{id}/sp', [UserKaryawanController::class, 'editPassword']);
    Route::get('/users/edit-password/{id}/sps', [UserKaryawanController::class, 'editPassword']);
    Route::get('/users/edit-password/{id}/sip', [UserKaryawanController::class, 'editPassword']);
    Route::post('/users/edit-password-proses/{id}/sp', [UserKaryawanController::class, 'editPasswordProses']);
    Route::post('/users/edit-password-proses/{id}/sps', [UserKaryawanController::class, 'editPasswordProses']);
    Route::post('/users/edit-password-proses/{id}/sip', [UserKaryawanController::class, 'editPasswordProses']);
    Route::post('/users/non_aktif_proses', [UserKaryawanController::class, 'non_aktif_proses']);
    Route::post('/users/aktif_proses', [UserKaryawanController::class, 'aktif_proses']);
    Route::post('/users/ImportUser/sp', [UserKaryawanController::class, 'ImportUser']);
    Route::post('/users/ImportUser/sps', [UserKaryawanController::class, 'ImportUser']);
    Route::post('/users/ImportUser/sip', [UserKaryawanController::class, 'ImportUser']);
    Route::post('/users/ImportUpdateUser/sp', [UserKaryawanController::class, 'ImportUpdateUser']);
    Route::post('/users/ImportUpdateUser/sps', [UserKaryawanController::class, 'ImportUpdateUser']);
    Route::post('/users/ImportUpdateUser/sip', [UserKaryawanController::class, 'ImportUpdateUser']);
    Route::get('/users/ExportUser/sp', [UserKaryawanController::class, 'ExportUser']);
    Route::get('/users/ExportUser/sps', [UserKaryawanController::class, 'ExportUser']);
    Route::get('/users/ExportUser/sip', [UserKaryawanController::class, 'ExportUser']);
    Route::get('/users/pdfUserKaryawan/sps', [UserKaryawanController::class, 'download_pdf_user_karyawan']);
    Route::get('/users/pdfUserKaryawan/sp', [UserKaryawanController::class, 'download_pdf_user_karyawan']);
    Route::get('/users/pdfUserKaryawan/sip', [UserKaryawanController::class, 'download_pdf_user_karyawan']);

    // mapping shift
    Route::get('/karyawan/shift/{id}/sp', [karyawanController::class, 'shift']);
    Route::get('/karyawan/shift/{id}/sps', [karyawanController::class, 'shift']);
    Route::get('/karyawan/shift/{id}/sip', [karyawanController::class, 'shift']);
    Route::get('/karyawan/mapping_shift_datatable/{id}/{holding}', [karyawanController::class, 'mapping_shift_datatable']);
    Route::post('/karyawan/shift/proses-tambah-shift/sp', [karyawanController::class, 'prosesTambahShift']);
    Route::post('/karyawan/shift/proses-tambah-shift/sps', [karyawanController::class, 'prosesTambahShift']);
    Route::post('/karyawan/shift/proses-tambah-shift/sip', [karyawanController::class, 'prosesTambahShift']);
    Route::get('/karyawan/delete-shift/{id}/sp', [karyawanController::class, 'deleteShift']);
    Route::get('/karyawan/delete-shift/{id}/sps', [karyawanController::class, 'deleteShift']);
    Route::get('/karyawan/delete-shift/{id}/sip', [karyawanController::class, 'deleteShift']);
    Route::get('/karyawan/edit-shift/{id}/sp', [karyawanController::class, 'editShift']);
    Route::get('/karyawan/edit-shift/{id}/sps', [karyawanController::class, 'editShift']);
    Route::get('/karyawan/edit-shift/{id}/sip', [karyawanController::class, 'editShift']);

    // mapping shift NEW
    Route::get('/karyawan/mapping_shift/{holding}', [MappingShiftController::class, 'mapping_shift_index']);
    Route::get('/karyawan/mapping_shift/detail/{id}/{holding}', [MappingShiftController::class, 'mapping_shift_detail_index']);
    Route::get('/karyawan/get_karyawan_mapping/{holding}', [MappingShiftController::class, 'get_karyawan_mapping']);
    Route::get('/karyawan/mapping_shift_datatable/{holding}', [MappingShiftController::class, 'mapping_shift_datatable']);
    Route::get('/karyawan/mapping_shift_detail_datatable/{id}/{holding}', [MappingShiftController::class, 'mapping_shift_detail_datatable']);
    Route::post('/karyawan/mapping_shift/proses-tambah-shift/{holding}', [MappingShiftController::class, 'prosesTambahDetailShift']);
    Route::get('/karyawan/mapping_calendar/{holding}', [MappingShiftController::class, 'mapping_calendar']);
    Route::get('/karyawan/mapping_calendar/getDetailTanggal/{holding}', [MappingShiftController::class, 'getDetailTanggal']);
    Route::get('/mapping_shift/get_columns/{holding}', [MappingShiftController::class, 'get_columns']);

    Route::post('/shift/proses-tambah-shift/sp', [MappingShiftController::class, 'prosesTambahShift']);
    Route::post('/shift/proses-tambah-shift/sps', [MappingShiftController::class, 'prosesTambahShift']);
    Route::post('/shift/proses-tambah-shift/sip', [MappingShiftController::class, 'prosesTambahShift']);
    Route::get('/karyawan/delete-shift/sp', [MappingShiftController::class, 'deleteShift']);
    Route::get('/karyawan/delete-shift/sps', [MappingShiftController::class, 'deleteShift']);
    Route::get('/karyawan/delete-shift/sip', [MappingShiftController::class, 'deleteShift']);
    Route::get('/karyawan/edit-shift/sp', [MappingShiftController::class, 'editShift']);
    Route::get('/karyawan/edit-shift/sps', [MappingShiftController::class, 'editShift']);
    Route::get('/karyawan/edit-shift/sip', [MappingShiftController::class, 'editShift']);

    Route::get('/mapping_shift/get_divisi', [MappingShiftController::class, 'get_divisi']);
    Route::get('/mapping_shift/get_bagian', [MappingShiftController::class, 'get_bagian']);
    Route::get('/mapping_shift/get_jabatan', [MappingShiftController::class, 'get_jabatan']);

    // Mapping User
    Route::get('mapping_shift/dashboard/', [MappingShiftController::class, 'index']);
    Route::get('mapping_shift/tambah_mapping/', [MappingShiftController::class, 'tambah_mapping']);
    Route::get('mapping_shift/get_shiftData/', [MappingShiftController::class, 'getShiftData']);
    Route::get('mapping_shift/getKaryawanMappingShift', [MappingShiftController::class, 'getKaryawanMappingShift']);
    Route::post('/mapping_shift/addMappingShift', [MappingShiftController::class, 'addMappingShift']);
    Route::post('/mapping_shift/update_mapping_shift', [MappingShiftController::class, 'update_mapping_shift']);
    Route::post('/mapping_shift/delete_mapping_shift', [MappingShiftController::class, 'delete_mapping_shift']);

    Route::post('/mapping_shift/prosesAddMappingShift/{holding}', [MappingShiftController::class, 'prosesAddMappingShift']);
    Route::post('/karyawan/mapping_shift/prosesEditMappingShift/{holding}', [MappingShiftController::class, 'prosesEditMappingShift']);


    //APPROVAL
    Route::get('/approval/mapping_shift', [ApprovalController::class, 'mapping_shift']);
    // STRUKTUR ORGANISASI
    Route::get('/struktur_organisasi/{holding}', [StrukturOrganisasiController::class, 'index']);

    // CUTI
    Route::get('/cuti/sp', [CutiController::class, 'index']);
    Route::get('/cuti/sps', [CutiController::class, 'index']);
    Route::get('/cuti/sip', [CutiController::class, 'index']);
    Route::get('/cuti/datatable-cuti_tahunan/sp', [CutiController::class, 'datatable_cuti_tahunan']);
    Route::get('/cuti/datatable-cuti_tahunan/sps', [CutiController::class, 'datatable_cuti_tahunan']);
    Route::get('/cuti/datatable-cuti_tahunan/sip', [CutiController::class, 'datatable_cuti_tahunan']);
    Route::get('/cuti/datatable-diluar_cuti_tahunan/sp', [CutiController::class, 'datatable_diluar_cuti_tahunan']);
    Route::get('/cuti/datatable-diluar_cuti_tahunan/sps', [CutiController::class, 'datatable_diluar_cuti_tahunan']);
    Route::get('/cuti/datatable-diluar_cuti_tahunan/sip', [CutiController::class, 'datatable_diluar_cuti_tahunan']);

    // CETAK CUTI
    Route::get('/cuti/cetak_form_cuti/{id}', [CutiController::class, 'cetak_form_cuti']);
    Route::get('/cuti/ExportCuti/{kategori}/{holding}', [CutiController::class, 'ExportCuti']);


    // IZIN
    Route::get('/izin/sp', [IzinController::class, 'index']);
    Route::get('/izin/sps', [IzinController::class, 'index']);
    Route::get('/izin/sip', [IzinController::class, 'index']);
    Route::get('/izin/datatable-terlambat/sp', [IzinController::class, 'datatable_terlambat']);
    Route::get('/izin/datatable-terlambat/sps', [IzinController::class, 'datatable_terlambat']);
    Route::get('/izin/datatable-terlambat/sip', [IzinController::class, 'datatable_terlambat']);
    Route::get('/izin/datatable-pulangcepat/sp', [IzinController::class, 'datatable_pulangcepat']);
    Route::get('/izin/datatable-pulangcepat/sps', [IzinController::class, 'datatable_pulangcepat']);
    Route::get('/izin/datatable-pulangcepat/sip', [IzinController::class, 'datatable_pulangcepat']);
    Route::get('/izin/datatable-keluar_kantor/sp', [IzinController::class, 'datatable_keluar_kantor']);
    Route::get('/izin/datatable-keluar_kantor/sps', [IzinController::class, 'datatable_keluar_kantor']);
    Route::get('/izin/datatable-keluar_kantor/sip', [IzinController::class, 'datatable_keluar_kantor']);
    Route::get('/izin/datatable-sakit/sp', [IzinController::class, 'datatable_sakit']);
    Route::get('/izin/datatable-sakit/sps', [IzinController::class, 'datatable_sakit']);
    Route::get('/izin/datatable-sakit/sip', [IzinController::class, 'datatable_sakit']);
    Route::get('/izin/datatable-tidak_masuk/sp', [IzinController::class, 'datatable_tidak_masuk']);
    Route::get('/izin/datatable-tidak_masuk/sps', [IzinController::class, 'datatable_tidak_masuk']);
    Route::get('/izin/datatable-tidak_masuk/sip', [IzinController::class, 'datatable_tidak_masuk']);

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
    Route::get('/shift/{holding}', [ShiftController::class, 'index'])->middleware('admin');
    Route::get('/shift-datatable/{holding}', [ShiftController::class, 'datatable'])->middleware('admin');
    Route::get('/shift/create/{holding}', [ShiftController::class, 'create'])->middleware('admin');
    Route::post('/shift/store/{holding}', [ShiftController::class, 'store']);
    Route::post('/shift/update/{holding}', [ShiftController::class, 'update'])->middleware('admin');
    Route::get('/shift/delete/{id}/{holding}', [ShiftController::class, 'destroy'])->middleware('admin');
    Route::get('/karyawan/shift/{id}/{holding}', [karyawanController::class, 'shift'])->middleware('admin');
    Route::get('/karyawan/mapping_shift_datatable/{id}/{holding}', [karyawanController::class, 'mapping_shift_datatable'])->middleware('admin');
    Route::post('/karyawan/shift/proses-tambah-shift/{holding}', [karyawanController::class, 'prosesTambahShift'])->middleware('admin');
    Route::get('/karyawan/delete-shift/{id}/{holding}', [karyawanController::class, 'deleteShift'])->middleware('admin');
    Route::get('/karyawan/edit-shift/{id}/{holding}', [karyawanController::class, 'editShift'])->middleware('admin');

    // FINGER MACHINE
    Route::get('/finger/{holding}', [FingerController::class, 'index'])->middleware('admin');
    Route::get('/finger-datatable/{holding}', [FingerController::class, 'datatable'])->middleware('admin');
    Route::post('/finger/store/{holding}', [FingerController::class, 'store'])->middleware('admin');
    Route::post('/finger/update/{holding}', [FingerController::class, 'update'])->middleware('admin');
    Route::get('/finger/delete/{id}/{holding}', [FingerController::class, 'destroy'])->middleware('admin');

    //
    Route::get('/karyawan/get_departemen/{id}', [karyawanController::class, 'get_departemen'])->middleware('admin');
    Route::get('/karyawan/get_divisi/{id}', [karyawanController::class, 'get_divisi'])->middleware('admin');
    Route::get('/karyawan/get_bagian/{id}', [karyawanController::class, 'get_bagian'])->middleware('admin');
    Route::get('/karyawan/get_jabatan/{id}', [karyawanController::class, 'get_jabatan'])->middleware('admin');

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

    // HOLDING
    Route::get('/holding/{holding}', [HoldingController::class, 'index'])->middleware('admin');
    Route::get('/holding-datatable/{holding}', [HoldingController::class, 'datatable'])->middleware('admin');

    // SITES
    Route::get('/site/{holding}', [SitesController::class, 'index'])->middleware('admin');
    Route::get('/site-datatable/{holding}', [SitesController::class, 'datatable'])->middleware('admin');
    Route::get('/site/tambah_site/{holding}', [SitesController::class, 'tambah_site'])->middleware('admin');
    Route::post('/site/addSite/{holding}', [SitesController::class, 'addSite'])->middleware('admin');

    // LOKASI
    Route::get('/lokasi/{holding}', [LokasiController::class, 'index'])->middleware('admin');
    Route::get('/lokasi/tambah_lokasi/{holding}', [LokasiController::class, 'tambah_lokasi'])->middleware('admin');
    Route::get('/lokasi-datatable/{holding}', [LokasiController::class, 'datatable'])->middleware('admin');
    Route::post('/lokasi/add/{holding}', [LokasiController::class, 'addLokasi']);
    Route::post('/lokasi/edit/{holding}', [LokasiController::class, 'updateLokasi']);
    Route::get('/lokasi/delete/{id}/{holding}', [LokasiController::class, 'deleteLokasi'])->middleware('admin');
    Route::put('/lokasi/radius/{id}/{holding}', [LokasiController::class, 'updateRadiusLokasi'])->middleware('admin');
    Route::get('/lokasi/get_lokasi/{holding}', [LokasiController::class, 'get_lokasi'])->middleware('admin');

    // reset Cuti
    Route::get('/reset-cuti/sp', [KaryawanController::class, 'resetCuti'])->middleware('admin');
    Route::get('/reset-cuti/sps', [KaryawanController::class, 'resetCuti'])->middleware('admin');
    Route::get('/reset-cuti/sip', [KaryawanController::class, 'resetCuti'])->middleware('admin');
    Route::put('/reset-cuti/{id}/sp', [KaryawanController::class, 'resetCutiProses'])->middleware('admin');
    Route::put('/reset-cuti/{id}/sps', [KaryawanController::class, 'resetCutiProses'])->middleware('admin');
    Route::put('/reset-cuti/{id}/sip', [KaryawanController::class, 'resetCutiProses'])->middleware('admin');
    // MASTER DEPARTEMEN
    Route::get('/departemen/{holding}', [DepartemenController::class, 'index'])->middleware('admin');
    Route::get('/departemen-datatable/{holding}', [DepartemenController::class, 'datatable'])->middleware('admin');
    Route::post('/departemen/insert/{holding}', [DepartemenController::class, 'insert'])->middleware('admin');
    Route::get('/departemen/edit/{id}/{holding}', [DepartemenController::class, 'edit'])->middleware('admin');
    Route::post('/departemen/update/{holding}', [DepartemenController::class, 'update'])->middleware('admin');
    Route::get('/departemen/delete/{id}/{holding}', [DepartemenController::class, 'delete'])->middleware('admin');
    Route::post('/departemen/ImportDepartemen/{holding}', [DepartemenController::class, 'ImportDepartemen'])->middleware('admin');
    Route::get('/departemen/divisi-datatable/{id?}/{holding}', [DepartemenController::class, 'divisi_datatable'])->middleware('admin');
    Route::get('/departemen/karyawandepartemen-datatable/{id?}/{holding}', [DepartemenController::class, 'karyawandepartemen_datatable'])->middleware('admin');
    // MASTER DIVISI
    Route::get('/divisi/{holding}', [DivisiController::class, 'index'])->middleware('admin');
    Route::get('/divisi-datatable/{holding}', [DivisiController::class, 'datatable'])->middleware('admin');
    Route::get('/divisi/create/{holding}', [DivisiController::class, 'create'])->middleware('admin');
    Route::post('/divisi/insert/{holding}', [DivisiController::class, 'insert'])->middleware('admin');
    Route::get('/divisi/edit/{id}/{holding}', [DivisiController::class, 'edit'])->middleware('admin');
    Route::post('/divisi/update/{holding}', [DivisiController::class, 'update'])->middleware('admin');
    Route::get('/divisi/delete/{id}/{holding}', [DivisiController::class, 'delete'])->middleware('admin');
    Route::post('/divisi/ImportDivisi/{holding}', [DivisiController::class, 'ImportDivisi'])->middleware('admin');
    Route::get('/divisi/bagian-datatable/{id?}/{holding}', [DivisiController::class, 'bagian_datatable'])->middleware('admin');
    Route::get('/divisi/karyawandivisi-datatable/{id?}/{holding}', [DivisiController::class, 'karyawandivisi_datatable'])->middleware('admin');

    // MASTER BAGIAN
    Route::get('/bagian/{holding}', [BagianController::class, 'index'])->middleware('admin');
    Route::get('/bagian-datatable/{holding}', [BagianController::class, 'datatable'])->middleware('admin');
    Route::get('/bagian/create/{holding}', [BagianController::class, 'create'])->middleware('admin');
    Route::post('/bagian/insert/{holding}', [BagianController::class, 'insert'])->middleware('admin');
    Route::get('/bagian/edit/{id}/{holding}', [BagianController::class, 'edit'])->middleware('admin');
    Route::post('/bagian/update/{holding}', [BagianController::class, 'update'])->middleware('admin');
    Route::get('/bagian/delete/{id}/{holding}', [BagianController::class, 'delete'])->middleware('admin');
    Route::get('/bagian/get_divisi/{id}/{holding}', [BagianController::class, 'get_divisi'])->middleware('admin');
    Route::get('/bagian/get_bagian/{id}', [BagianController::class, 'get_bagian'])->middleware('admin');
    Route::post('/bagian/ImportBagian/{holding}', [BagianController::class, 'ImportBagian'])->middleware('admin');
    Route::get('/jabatan/jabatan-datatable/{id?}/{holding}', [BagianController::class, 'jabatan_datatable'])->middleware('admin');
    Route::get('/jabatan/karyawanjabatan-datatable/{id?}/{holding}', [BagianController::class, 'karyawanjabatan_datatable'])->middleware('admin');
    Route::get('/jabatan/get_jabatan/{id}', [JabatanController::class, 'get_jabatan'])->middleware('admin');



    Route::get('/inventaris/sp', [InventarisController::class, 'index']);
    Route::get('/inventaris-datatable/sp', [InventarisController::class, 'datatable']);
    Route::get('/inventaris/sps', [InventarisController::class, 'index']);
    Route::get('/inventaris-datatable/sps', [InventarisController::class, 'datatable']);
    Route::get('/inventaris/sip', [InventarisController::class, 'index']);
    Route::get('/inventaris-datatable/sip', [InventarisController::class, 'datatable']);
    Route::post('/inventaris/tambah-inventaris-proses/sp', [InventarisController::class, 'tambahInventarisProses']);
    Route::post('/inventaris/tambah-inventaris-proses/sps', [InventarisController::class, 'tambahInventarisProses']);
    Route::post('/inventaris/tambah-inventaris-proses/sip', [InventarisController::class, 'tambahInventarisProses']);
    Route::post('/inventaris/proses-edit/sp', [InventarisController::class, 'editInventarisProses']);
    Route::post('/inventaris/proses-edit/sps', [InventarisController::class, 'editInventarisProses']);
    Route::post('/inventaris/proses-edit/sip', [InventarisController::class, 'editInventarisProses']);

    // ACCESS
    Route::get('/access/{holding}', [AccessController::class, 'index']);
    Route::get('/access-datatable/{holding}', [AccessController::class, 'datatable']);
    Route::get('/access/role_access_datatable/{id}/{holding}', [AccessController::class, 'role_access_datatable']);
    Route::get('/access/add_access/{id}/{holding}', [AccessController::class, 'add_access']);
    Route::post('/access/access_save_add/{holding}', [AccessController::class, 'access_save_add']);
    Route::get('/data-absen', [AbsenController::class, 'dataAbsen']);
    Route::get('/data-absen/{id}/edit-masuk', [AbsenController::class, 'editMasuk']);
    Route::put('/data-absen/{id}/proses-edit-masuk', [AbsenController::class, 'prosesEditMasuk']);
    Route::get('/data-absen/{id}/edit-pulang', [AbsenController::class, 'editPulang']);
    Route::put('/data-absen/{id}/proses-edit-pulang', [AbsenController::class, 'prosesEditPulang']);
    Route::delete('/data-absen/{id}/delete', [AbsenController::class, 'deleteAdmin']);
    Route::get('/data-lembur', [LemburController::class, 'dataLembur']);

    // MASTER MENU
    Route::get('/menu/{holding}', [MenuController::class, 'index']);
    Route::post('/menu/save_all_change', [MenuController::class, 'save_all_change']);

    // ROLE
    Route::get('/role/{holding}', [RoleController::class, 'index']);
    Route::get('/role-datatable/{holding}', [RoleController::class, 'datatable']);
    Route::get('/menu-datatable/{holding}', [RoleController::class, 'datatable_menu']);
    Route::get('/role/add_role/{id}/{holding}', [RoleController::class, 'add_role']);
    Route::post('/role/role_save_add/{holding}', [RoleController::class, 'role_save_add']);


    Route::get('/rekap-data/sp', [RekapDataController::class, 'index']);
    Route::get('/rekap-data/sps', [RekapDataController::class, 'index']);
    Route::get('/rekap-data/sip', [RekapDataController::class, 'index']);
    Route::get('/rekap-data/ExportAbsensi/sp', [RekapDataController::class, 'ExportAbsensi']);
    Route::get('/rekap-data/ExportAbsensi/sps', [RekapDataController::class, 'ExportAbsensi']);
    Route::get('/rekap-data/ExportAbsensi/sip', [RekapDataController::class, 'ExportAbsensi']);
    Route::get('/rekapdata-datatable/sp', [RekapDataController::class, 'datatable']);
    Route::get('/rekapdata-datatable/sps', [RekapDataController::class, 'datatable']);
    Route::get('/rekapdata-datatable/sip', [RekapDataController::class, 'datatable']);
    Route::get('/rekapdata-detail_datatable/{id}/sp', [RekapDataController::class, 'detail_datatable']);
    Route::get('/rekapdata-detail_datatable/{id}/sps', [RekapDataController::class, 'detail_datatable']);
    Route::get('/rekapdata-detail_datatable/{id}/sip', [RekapDataController::class, 'detail_datatable']);
    Route::get('/rekapdata-datatable_harian/sp', [RekapDataController::class, 'datatable_harian']);
    Route::get('/rekapdata-datatable_harian/sps', [RekapDataController::class, 'datatable_harian']);
    Route::get('/rekapdata-datatable_harian/sip', [RekapDataController::class, 'datatable_harian']);
    Route::get('/rekapdata/get_grafik_absensi', [RekapDataController::class, 'get_grafik_absensi']);

    // CETAK FORM
    Route::get('/rekapdata/cetak_form_izin/{id}', [RekapDataController::class, 'cetak_form_izin']);
    Route::get('/rekapdata/cetak_form_cuti/{id}', [RekapDataController::class, 'cetak_form_cuti']);
    Route::get('/rekapdata/cetak_form_penugasan/{id}', [RekapDataController::class, 'cetak_form_penugasan']);

    Route::get('/rekapdata/get_divisi', [RekapDataController::class, 'get_divisi']);
    Route::get('/rekapdata/get_bagian', [RekapDataController::class, 'get_bagian']);
    Route::get('/rekapdata/get_jabatan', [RekapDataController::class, 'get_jabatan']);

    // Import
    Route::post('/rekapdata/ImportAbsensi/sp', [RekapDataController::class, 'ImportAbsensi']);
    Route::post('/rekapdata/ImportAbsensi/sps', [RekapDataController::class, 'ImportAbsensi']);
    Route::post('/rekapdata/ImportAbsensi/sip', [RekapDataController::class, 'ImportAbsensi']);


    // REPORT ABSENSI
    Route::get('/report/{holding}', [ReportController::class, 'index']);
    Route::get('/report-datatable/{holding}', [ReportController::class, 'datatable']);
    Route::get('/report-datatable_finger/{holding}', [ReportController::class, 'datatable_finger']);
    Route::get('/report/get_divisi/{holding}', [ReportController::class, 'get_divisi']);
    Route::get('/report/get_bagian/{holding}', [ReportController::class, 'get_bagian']);
    Route::get('/report/get_jabatan/{holding}', [ReportController::class, 'get_jabatan']);
    Route::get('/report/get_columns/{holding}', [ReportController::class, 'get_columns']);
    Route::get('/report/get_filter_month', [ReportController::class, 'get_filter_month']);
    Route::get('/report/ExportReport', [ReportController::class, 'ExportReport']);

    Route::get('/report_kedisiplinan/{holding}', [ReportController::class, 'index_kedisiplinan']);
    Route::get('/report_kedisiplinan1/{holding}', [ReportController::class, 'index_kedisiplinan1']);
    Route::get('/report_kedisiplinan-datatable/{holding}', [ReportController::class, 'datatable_kedisiplinan']);
    Route::get('/report_kedisiplinan-datatable1/{holding}', [ReportController::class, 'datatable_kedisiplinan1']);
    Route::get('/report_kedisiplinan/get_divisi/{holding}', [ReportController::class, 'get_divisi']);
    Route::get('/report_kedisiplinan/get_bagian/{holding}', [ReportController::class, 'get_bagian']);
    Route::get('/report_kedisiplinan/get_jabatan/{holding}', [ReportController::class, 'get_jabatan']);
    Route::get('/report_kedisiplinan/get_columns/{holding}', [ReportController::class, 'get_columns_kedisiplinan']);
    Route::get('/report_kedisiplinan/get_grafik_absensi/{holding}', [ReportController::class, 'get_grafik_absensi']);

    // DETAIL REPORT KEDISIPLINAN 
    Route::get('/report_kedisiplinan/detail/{id}/{holding}', [ReportController::class, 'detail_index_kedisiplinan']);
    Route::get('/report_kedisiplinan/detail_datatable/{id}/{holding}', [ReportController::class, 'detail_datatable']);

    // Export
    Route::get('/report_kedisiplinan/RekapAbsensiKedisiplinan/{holding}', [ExportController::class, 'RekapAbsensiKedisiplinan']);

    // reset Cuti
    Route::get('/reset-cuti/sp', [KaryawanController::class, 'resetCuti']);
    Route::get('/reset-cuti/sps', [KaryawanController::class, 'resetCuti']);
    Route::get('/reset-cuti/sip', [KaryawanController::class, 'resetCuti']);
    Route::put('/reset-cuti/{id}/sp', [KaryawanController::class, 'resetCutiProses']);
    Route::put('/reset-cuti/{id}/sps', [KaryawanController::class, 'resetCutiProses']);
    Route::put('/reset-cuti/{id}/sip', [KaryawanController::class, 'resetCutiProses']);
    // MASTER DEPARTEMEN
    Route::get('/departemen/sp', [DepartemenController::class, 'index']);
    Route::get('/departemen/sps', [DepartemenController::class, 'index']);
    Route::get('/departemen/sip', [DepartemenController::class, 'index']);
    Route::get('/departemen-datatable/sp', [DepartemenController::class, 'datatable']);
    Route::get('/departemen-datatable/sps', [DepartemenController::class, 'datatable']);
    Route::get('/departemen-datatable/sip', [DepartemenController::class, 'datatable']);
    Route::get('/departemen/create/sp', [DepartemenController::class, 'create']);
    Route::get('/departemen/create/sps', [DepartemenController::class, 'create']);
    Route::get('/departemen/create/sip', [DepartemenController::class, 'create']);
    Route::post('/departemen/insert/sp', [DepartemenController::class, 'insert']);
    Route::post('/departemen/insert/sps', [DepartemenController::class, 'insert']);
    Route::post('/departemen/insert/sip', [DepartemenController::class, 'insert']);
    Route::get('/departemen/edit/{id}/sp', [DepartemenController::class, 'edit']);
    Route::get('/departemen/edit/{id}/sps', [DepartemenController::class, 'edit']);
    Route::get('/departemen/edit/{id}/sip', [DepartemenController::class, 'edit']);
    Route::post('/departemen/update/sp', [DepartemenController::class, 'update']);
    Route::post('/departemen/update/sps', [DepartemenController::class, 'update']);
    Route::post('/departemen/update/sip', [DepartemenController::class, 'update']);
    Route::get('/departemen/delete/{id}/sp', [DepartemenController::class, 'delete']);
    Route::get('/departemen/delete/{id}/sps', [DepartemenController::class, 'delete']);
    Route::get('/departemen/delete/{id}/sip', [DepartemenController::class, 'delete']);
    Route::post('/departemen/ImportDepartemen/sp', [DepartemenController::class, 'ImportDepartemen']);
    Route::post('/departemen/ImportDepartemen/sps', [DepartemenController::class, 'ImportDepartemen']);
    Route::post('/departemen/ImportDepartemen/sip', [DepartemenController::class, 'ImportDepartemen']);
    Route::get('/departemen/divisi-datatable/{id?}/sp', [DepartemenController::class, 'divisi_datatable']);
    Route::get('/departemen/divisi-datatable/{id?}/sps', [DepartemenController::class, 'divisi_datatable']);
    Route::get('/departemen/divisi-datatable/{id?}/sip', [DepartemenController::class, 'divisi_datatable']);
    Route::get('/departemen/karyawandepartemen-datatable/{id?}/sp', [DepartemenController::class, 'karyawandepartemen_datatable']);
    Route::get('/departemen/karyawandepartemen-datatable/{id?}/sps', [DepartemenController::class, 'karyawandepartemen_datatable']);
    Route::get('/departemen/karyawandepartemen-datatable/{id?}/sip', [DepartemenController::class, 'karyawandepartemen_datatable']);
    // MASTER DIVISI
    Route::get('/divisi/sp', [DivisiController::class, 'index']);
    Route::get('/divisi/sps', [DivisiController::class, 'index']);
    Route::get('/divisi/sip', [DivisiController::class, 'index']);
    Route::get('/divisi-datatable/sp', [DivisiController::class, 'datatable']);
    Route::get('/divisi-datatable/sps', [DivisiController::class, 'datatable']);
    Route::get('/divisi-datatable/sip', [DivisiController::class, 'datatable']);
    Route::get('/divisi/create/sp', [DivisiController::class, 'create']);
    Route::get('/divisi/create/sps', [DivisiController::class, 'create']);
    Route::get('/divisi/create/sip', [DivisiController::class, 'create']);
    Route::post('/divisi/insert/sp', [DivisiController::class, 'insert']);
    Route::post('/divisi/insert/sps', [DivisiController::class, 'insert']);
    Route::post('/divisi/insert/sip', [DivisiController::class, 'insert']);
    Route::get('/divisi/edit/{id}/sp', [DivisiController::class, 'edit']);
    Route::get('/divisi/edit/{id}/sps', [DivisiController::class, 'edit']);
    Route::get('/divisi/edit/{id}/sip', [DivisiController::class, 'edit']);
    Route::post('/divisi/update/sp', [DivisiController::class, 'update']);
    Route::post('/divisi/update/sps', [DivisiController::class, 'update']);
    Route::post('/divisi/update/sip', [DivisiController::class, 'update']);
    Route::get('/divisi/delete/{id}/sp', [DivisiController::class, 'delete']);
    Route::get('/divisi/delete/{id}/sps', [DivisiController::class, 'delete']);
    Route::get('/divisi/delete/{id}/sip', [DivisiController::class, 'delete']);
    Route::post('/divisi/ImportDivisi/sp', [DivisiController::class, 'ImportDivisi']);
    Route::post('/divisi/ImportDivisi/sps', [DivisiController::class, 'ImportDivisi']);
    Route::post('/divisi/ImportDivisi/sip', [DivisiController::class, 'ImportDivisi']);
    Route::get('/divisi/bagian-datatable/{id?}/sp', [DivisiController::class, 'bagian_datatable']);
    Route::get('/divisi/bagian-datatable/{id?}/sps', [DivisiController::class, 'bagian_datatable']);
    Route::get('/divisi/bagian-datatable/{id?}/sip', [DivisiController::class, 'bagian_datatable']);
    Route::get('/divisi/karyawandivisi-datatable/{id?}/sp', [DivisiController::class, 'karyawandivisi_datatable']);
    Route::get('/divisi/karyawandivisi-datatable/{id?}/sps', [DivisiController::class, 'karyawandivisi_datatable']);
    Route::get('/divisi/karyawandivisi-datatable/{id?}/sip', [DivisiController::class, 'karyawandivisi_datatable']);

    // MASTER BAGIAN
    Route::get('/bagian/sp', [BagianController::class, 'index']);
    Route::get('/bagian/sps', [BagianController::class, 'index']);
    Route::get('/bagian/sip', [BagianController::class, 'index']);
    Route::get('/bagian-datatable/sp', [BagianController::class, 'datatable']);
    Route::get('/bagian-datatable/sps', [BagianController::class, 'datatable']);
    Route::get('/bagian-datatable/sip', [BagianController::class, 'datatable']);
    Route::get('/bagian/create/sp', [BagianController::class, 'create']);
    Route::get('/bagian/create/sps', [BagianController::class, 'create']);
    Route::get('/bagian/create/sip', [BagianController::class, 'create']);
    Route::post('/bagian/insert/sp', [BagianController::class, 'insert']);
    Route::post('/bagian/insert/sps', [BagianController::class, 'insert']);
    Route::post('/bagian/insert/sip', [BagianController::class, 'insert']);
    Route::get('/bagian/edit/{id}/sp', [BagianController::class, 'edit']);
    Route::get('/bagian/edit/{id}/sps', [BagianController::class, 'edit']);
    Route::get('/bagian/edit/{id}/sip', [BagianController::class, 'edit']);
    Route::post('/bagian/update/sp', [BagianController::class, 'update']);
    Route::post('/bagian/update/sps', [BagianController::class, 'update']);
    Route::post('/bagian/update/sip', [BagianController::class, 'update']);
    Route::get('/bagian/delete/{id}/sp', [BagianController::class, 'delete']);
    Route::get('/bagian/delete/{id}/sps', [BagianController::class, 'delete']);
    Route::get('/bagian/delete/{id}/sip', [BagianController::class, 'delete']);
    Route::get('/bagian/get_divisi/{id}', [BagianController::class, 'get_divisi']);
    Route::post('/bagian/ImportBagian/sp', [BagianController::class, 'ImportBagian']);
    Route::post('/bagian/ImportBagian/sps', [BagianController::class, 'ImportBagian']);
    Route::post('/bagian/ImportBagian/sip', [BagianController::class, 'ImportBagian']);
    Route::get('/jabatan/jabatan-datatable/{id?}/sp', [BagianController::class, 'jabatan_datatable']);
    Route::get('/jabatan/jabatan-datatable/{id?}/sps', [BagianController::class, 'jabatan_datatable']);
    Route::get('/jabatan/jabatan-datatable/{id?}/sip', [BagianController::class, 'jabatan_datatable']);
    Route::get('/jabatan/karyawanjabatan-datatable/{id?}/sp', [BagianController::class, 'karyawanjabatan_datatable']);
    Route::get('/jabatan/karyawanjabatan-datatable/{id?}/sps', [BagianController::class, 'karyawanjabatan_datatable']);
    Route::get('/jabatan/karyawanjabatan-datatable/{id?}/sip', [BagianController::class, 'karyawanjabatan_datatable']);


    // MASTER JABATAN
    Route::get('/jabatan/{holding}', [jabatanController::class, 'index']);
    Route::get('/detail_jabatan/{id?}/{holding}', [jabatanController::class, 'detail_jabatan']);
    Route::get('/jabatan-datatable/{id?}/{holding}', [jabatanController::class, 'datatable']);
    Route::get('/bawahanjabatan-datatable/{id?}/{holding}', [jabatanController::class, 'bawahan_datatable']);
    Route::get('/karyawanjabatan-datatable/{id?}/{holding}', [jabatanController::class, 'karyawan_datatable']);
    Route::get('/jabatan/create/{holding}', [jabatanController::class, 'create']);
    Route::post('/jabatan/insert/{holding}', [jabatanController::class, 'insert']);
    Route::get('/jabatan/edit/{id}/{holding}', [jabatanController::class, 'edit']);
    Route::post('/jabatan/update/{holding}', [jabatanController::class, 'update']);
    Route::get('/jabatan/delete/{id}/{holding}', [jabatanController::class, 'delete']);
    Route::get('/jabatan/get_bagian/{id}/{holding}', [jabatanController::class, 'get_bagian']);
    Route::post('/jabatan/ImportJabatan/{holding}', [jabatanController::class, 'ImportJabatan']);

    Route::get('/atasan/get_jabatan/{holding}', [jabatanController::class, 'get_atasan']);
    Route::get('/atasan/edit/get_jabatan/{holding}', [jabatanController::class, 'get_atasan_edit']);
    // GET ATASAN 1 & 2
    Route::get('/karyawan/atasan/get_jabatan/sp', [karyawanController::class, 'get_atasan']);
    Route::get('/karyawan/atasan/get_jabatan/sps', [karyawanController::class, 'get_atasan']);
    Route::get('/karyawan/atasan/get_jabatan/sip', [karyawanController::class, 'get_atasan']);
    Route::get('/karyawan/atasan2/get_jabatan/sp', [karyawanController::class, 'get_atasan2']);
    Route::get('/karyawan/atasan2/get_jabatan/sps', [karyawanController::class, 'get_atasan2']);
    Route::get('/karyawan/atasan2/get_jabatan/sip', [karyawanController::class, 'get_atasan2']);
    // GET ALAMAT
    Route::get('/karyawan/get_kabupaten/{id}', [karyawanController::class, 'get_kabupaten']);
    Route::get('/karyawan/get_kecamatan/{id}', [karyawanController::class, 'get_kecamatan']);
    Route::get('/karyawan/get_desa/{id}', [karyawanController::class, 'get_desa']);
    // DOKUMEN
    Route::get('/dokumen', [DokumenController::class, 'index']);
    Route::get('/dokumen/tambah', [DokumenController::class, 'tambah']);
    Route::post('/dokumen/tambah-proses', [DokumenController::class, 'tambahProses']);
    Route::get('/dokumen/edit/{id}', [DokumenController::class, 'edit']);
    Route::put('/dokumen/edit-proses/{id}', [DokumenController::class, 'editProses']);
    Route::delete('/dokumen/delete/{id}', [DokumenController::class, 'delete']);
});
// MIDLEWARE HRD
Route::get('/logout', [authController::class, 'logout'])->name('logout');

// Include file route tambahan
require __DIR__ . '/api_hrd.php';

// RECRUITMENT DASHBOARD ADMIN heheheghe
Route::get('/pg-data-recruitment/{holding}', [RecruitmentController::class, 'pg_recruitment'])->middleware('admin');

Route::post('/recruitment/create/{holding}', [RecruitmentController::class, 'create'])->middleware('admin');

Route::post('/recruitment/update', [RecruitmentController::class, 'update'])->middleware('admin');

Route::get('/recruitment/delete/{id?}/{holding}', [RecruitmentController::class, 'delete'])->middleware('admin');

Route::get('/dt/data-recruitment/{holding}', [RecruitmentController::class, 'dt_recruitment'])->middleware('admin');
Route::get('/recruitment/update/status-recruitment/{id?}/{holding}', [RecruitmentController::class, 'update_status'])->middleware('admin');

Route::get('/pg/data-list-pelamar/{id?}/{holding}', [RecruitmentController::class, 'pg_list_pelamar'])->middleware('admin');
Route::get('/pg/data-list-user_meta/{id?}/{holding}', [RecruitmentController::class, 'user_meta'])->middleware('admin');
Route::get('/pg/data-list-user_kandidat/{id?}/{holding}', [RecruitmentController::class, 'user_kandidat'])->middleware('admin');
Route::get('/pg/data-list-user_wait/{id?}/{holding}', [RecruitmentController::class, 'user_wait'])->middleware('admin');
Route::get('/pg/data-list-user_reject/{id?}/{holding}', [RecruitmentController::class, 'user_reject'])->middleware('admin');


Route::get('/pg/pelamar-detail/{id?}/{holding}', [RecruitmentController::class, 'pelamar_detail'])->middleware('admin');

Route::get('/pg/pelamar-detail_pdf/{id?}/{holding}', [RecruitmentController::class, 'pelamar_detail_pdf'])->middleware('admin');
Route::get('/pg/pelamar-nilai_pdf/{id?}', [RecruitmentController::class, 'pelamar_nilai_pdf'])->middleware('admin');

Route::post('/pg/pelamar-detail-ubah/{holding}', [RecruitmentController::class, 'pelamar_detail_ubah'])->middleware('admin');


Route::get('/pg-data-interview/{holding}', [RecruitmentController::class, 'pg_data_interview'])->middleware('admin');
Route::get('/dt/data-interview/{holding}', [RecruitmentController::class, 'dt_data_interview'])->middleware('admin');
Route::get('/dt/data-interview1/{holding}', [RecruitmentController::class, 'dt_data_interview1'])->middleware('admin');
Route::get('/dt/data-interview2/{holding}', [RecruitmentController::class, 'dt_data_interview2'])->middleware('admin');
Route::get('/dt/data-interview3/{holding}', [RecruitmentController::class, 'dt_data_interview3'])->middleware('admin');
Route::get('/dt/data-data_ujian_user/{id}/{holding}', [RecruitmentController::class, 'data_ujian_user'])->middleware('admin');

Route::get('/dt/data-get_data_esai/{id}/{holding}', [UjianUserController::class, 'dt_ujian_esai'])->middleware('admin');

Route::get('/dt/data-dt_interview_user/{id}/{holding}', [UjianUserController::class, 'dt_interview_user'])->middleware('admin');

Route::post('/dt/interview_user_post', [UjianUserController::class, 'interview_user_post'])->middleware('admin');
Route::get('/dt/get_catatan_interview/{id}', [UjianUserController::class, 'get_catatan_interview'])->middleware('admin');
Route::post('/dt/update_catatan', [UjianUserController::class, 'update_catatan'])->middleware('admin');
Route::get('/dt/dt_catatan/{id}', [UjianUserController::class, 'dt_catatan'])->middleware('admin');

Route::get('/dt/data-get_data_pg/{id}/{holding}', [UjianUserController::class, 'dt_ujian_pg'])->middleware('admin');

Route::post('/dt/data-interview/penilaian_esai', [UjianUserController::class, 'penilaian_esai'])->name('penilaian_esai')->middleware('admin');

Route::get('/dt/data-get_esai_interview/{id}/{id_user}/{holding}', [UjianUserController::class, 'show_esai'])->middleware('admin');

Route::get('/dt/data-get_interview_user/{id}/{id_user}/sp', [UjianUserController::class, 'show_interview_user'])->middleware('admin');
Route::get('/dt/data-get_interview_user/{id}/{id_user}/sps', [UjianUserController::class, 'show_interview_user'])->middleware('admin');
Route::get('/dt/data-get_interview_user/{id}/{id_user}/sip', [UjianUserController::class, 'show_interview_user'])->middleware('admin');

Route::get('/dt/data-get_pg_interview/{id}/{id_user}/{holding}', [UjianUserController::class, 'show_pg'])->middleware('admin');

Route::post('/dt/data-interview/presensi_recruitment_update', [RecruitmentController::class, 'presensi_recruitment_update'])->middleware('admin');
Route::post('/dt/data-interview/ranking_update_status', [RecruitmentController::class, 'ranking_update_status'])->middleware('admin');
Route::post('/dt/data-interview/integrasi', [RecruitmentController::class, 'user_integrasi'])->middleware('admin');


Route::get('/pg-data-ujian/{holding}', [RecruitmentController::class, 'pg_ujian'])->middleware('admin');

Route::post('/pg-data-pembobotan_post', [RecruitmentController::class, 'pembobotan_post'])->middleware('admin');
Route::get('/pg-data-dt_pembobotan', [RecruitmentController::class, 'dt_pembobotan'])->name('dt_pembobotan')->middleware('admin');

Route::get('/dt-data-list-ujian/{holding}', [RecruitmentController::class, 'dt_ujian'])->middleware('admin');

Route::get('/dt-data-list-esai/{holding}', [RecruitmentController::class, 'dt_esai'])->middleware('admin');

Route::get('/dt-data-list-ujian_kategori/{holding}', [RecruitmentController::class, 'dt_ujian_kategori'])->middleware('admin');

Route::get('/dt-data-list-interview_admin/{holding}', [RecruitmentController::class, 'dt_interview_admin'])->middleware('admin');

Route::get('/dt_referensi', [RecruitmentController::class, 'dt_referensi'])->middleware('admin');
Route::post('/referensi_add', [RecruitmentController::class, 'referensi_add'])->middleware('admin');
Route::post('/delete_referensi', [RecruitmentController::class, 'delete_referensi'])->middleware('admin');
Route::post('/referensi_update', [RecruitmentController::class, 'referensi_update'])->middleware('admin');



Route::post('ujian_kategori_post', [RecruitmentController::class, 'ujian_kategori_post'])->middleware('admin');
Route::post('delete_ujian_kategori', [RecruitmentController::class, 'delete_ujian_kategori'])->middleware('admin');
Route::post('ujian_kategori_update', [RecruitmentController::class, 'ujian_kategori_update'])->middleware('admin');

Route::post('interview_admin_post', [RecruitmentController::class, 'interview_admin_post'])->middleware('admin');
Route::post('interview_admin_update', [RecruitmentController::class, 'interview_admin_update'])->middleware('admin');
Route::post('interview_admin_delete', [RecruitmentController::class, 'interview_admin_delete'])->middleware('admin');

Route::get('/edit-ujian/{kode?}/{holding}', [RecruitmentController::class, 'edit_ujian'])->middleware('admin');

Route::get('/show-esai/{kode?}/{holding}', [RecruitmentController::class, 'show_esai'])->middleware('admin');

Route::get('/edit-esai/{kode?}/{holding}', [RecruitmentController::class, 'edit_esai'])->middleware('admin');

Route::get('/pg-data-ujian/ujian_pg/{holding}', [RecruitmentController::class, 'pg_ujian_pg'])->middleware('admin');

Route::get('/pg-data-ujian/ujian_pg_esai/{holding}', [RecruitmentController::class, 'pg_esai_pg'])->middleware('admin');

Route::post('/ujian/ujian-pg-store', [RecruitmentController::class, 'ujian_pg_store'])->middleware('admin');
Route::post('/ujian/ujian-pg-update', [RecruitmentController::class, 'ujian_pg_update'])->middleware('admin');
Route::post('/ujian/esai-pg-store', [RecruitmentController::class, 'esai_pg_store'])->middleware('admin');
Route::post('/ujian/esai-pg-update', [RecruitmentController::class, 'esai_pg_update'])->middleware('admin');
Route::post('/ujian/esai-pg-update', [RecruitmentController::class, 'esai_pg_update'])->middleware('admin');

// SUMMERNOTE
Route::post('/summernote/upload', [SummernoteController::class, 'upload'])->name('summernote_upload');
Route::post('/summernote/delete', [SummernoteController::class, 'delete'])->name('summernote_delete');
Route::get('/summernote/unduh/{file}', [SummernoteController::class, 'unduh']);
Route::post('/summernote/delete_file', [SummernoteController::class, 'delete_file']);

Route::post('/nilai-interview-hrd/update/sp', [RecruitmentController::class, 'nilai_interview_hrd'])->middleware('admin');
Route::post('/nilai-interview-hrd/update/sps', [RecruitmentController::class, 'nilai_interview_hrd'])->middleware('admin');
Route::post('/nilai-interview-hrd/update/sip', [RecruitmentController::class, 'nilai_interview_hrd'])->middleware('admin');

Route::post('/nilai-interview-manager/update/sp', [RecruitmentController::class, 'nilai_interview_manager'])->middleware('admin');
Route::post('/nilai-interview-manager/update/sps', [RecruitmentController::class, 'nilai_interview_manager'])->middleware('admin');
Route::post('/nilai-interview-manager/update/sip', [RecruitmentController::class, 'nilai_interview_manager'])->middleware('admin');

Route::get('/pg-data-ranking/{holding}', [RecruitmentController::class, 'pg_ranking'])->middleware('admin');

Route::get('/dt/data-ranking/{holding}', [RecruitmentController::class, 'dt_data_ranking'])->middleware('admin');

Route::get('/pg/data-list-ranking/{id?}/{holding}', [RecruitmentController::class, 'pg_list_ranking'])->middleware('admin');

Route::get('/dt/data-list-ranking/{id?}/{holding}', [RecruitmentController::class, 'dt_list_ranking'])->middleware('admin');

Route::get('/dt/data-list-progres/{id?}/{holding}', [RecruitmentController::class, 'dt_list_progres'])->middleware('admin');

Route::get('/konfirmasi-interview/{email?}/tidak-konfirmasi', [RecruitmentUserController::class, 'tidak_konfirmasi']);
Route::get('/konfirmasi-interview/{email?}/konfirmasi', [RecruitmentUserController::class, 'konfirmasi']);

Route::get('/tes', function () {
    return view('admin.recruitment-users.email.email_interview');
})->name('tes');

//Laporan pelamar
Route::get('/report_pelamar/{holding}', [RecruitmentLaporanController::class, 'report_pelamar'])->middleware('admin');
Route::get('/laporan_recruitment/{holding}', [RecruitmentLaporanController::class, 'index'])->middleware('admin');
Route::get('/detail_riwayat/{id}/{holding}', [RecruitmentLaporanController::class, 'detail_riwayat'])->middleware('admin');
Route::get('/dt_laporan_recruitment/{holding}', [RecruitmentLaporanController::class, 'dt_laporan_recruitment'])->middleware('admin');
Route::get('/dt_laporan_recruitment_print/{holding}', [RecruitmentLaporanController::class, 'dt_laporan_recruitment_print'])->middleware('admin');
Route::get('/dt_riwayat_recruitment/{id}/{holding}', [RecruitmentLaporanController::class, 'dt_riwayat_recruitment'])->middleware('admin');
Route::get('/report_recruitment/get_divisi/{holding}', [RecruitmentLaporanController::class, 'get_divisi']);
Route::get('/report_recruitment/get_bagian/{holding}', [RecruitmentLaporanController::class, 'get_bagian']);
Route::get('/report_recruitment/get_jabatan/{holding}', [RecruitmentLaporanController::class, 'get_jabatan']);
//laporan pelamar end
Route::get('/report_recruitment/{holding}', [RecruitmentLaporanController::class, 'laporan_recruitment'])->middleware('admin');
Route::get('/dt_laporan_recruitment2/{holding}', [RecruitmentLaporanController::class, 'dt_laporan_recruitment2'])->middleware('admin');

Route::get('/report_per_divisi/{holding}', [RecruitmentLaporanController::class, 'report_per_divisi'])->middleware('admin');
Route::get('/detail_per_divisi/{id}/{holding}', [RecruitmentLaporanController::class, 'detail_per_divisi'])->middleware('admin');
Route::get('/dt_per_divisi/{holding}', [RecruitmentLaporanController::class, 'dt_per_divisi'])->middleware('admin');
Route::get('/dt_per_divisi_print/{holding}', [RecruitmentLaporanController::class, 'dt_per_divisi_print'])->middleware('admin');

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

Route::middleware('auth:web', 'log.activity')->group(function () {
    Route::get('api/get_home', [ApiController::class, 'get_home']);
    // API
});

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