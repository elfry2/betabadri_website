<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// Controllers Public & Auth
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\Admin\AuthController;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OfficeAttendanceController;
use App\Http\Controllers\Admin\LeaveController; // Controller Baru
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CareerAdminController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\ExpenseController;

// Controllers Mentor Area
use App\Http\Controllers\Mentor\DashboardController as MentorDashboard;
use App\Http\Controllers\Mentor\StudentController as MentorStudent;
use App\Http\Controllers\Mentor\ActivityController;
use App\Http\Controllers\Mentor\GradeController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/page/family', [PublicController::class, 'team'])->name('public.family');
Route::redirect('/page/mentor', '/page/family', 301);
Route::get('/page/galeri', [PublicController::class, 'gallery'])->name('public.gallery');
Route::get('/page/testimonials', [PublicController::class, 'testimonials'])->name('public.testimonials');

Route::prefix('page/programs')->name('programs.')->group(function() {
    Route::get('/', [PublicController::class, 'programs'])->name('index');
    Route::get('/modular', [PublicController::class, 'modular'])->name('modular');
    Route::get('/electronika', [PublicController::class, 'electronika'])->name('electronika');
    Route::get('/programming', [PublicController::class, 'programming'])->name('programming');
    Route::get('/game', [PublicController::class, 'game'])->name('game');
    Route::get('/coming-soon', function() { return view('pages/programs/coming-soon'); })->name('coming-soon');
});

Route::get('/page/visi', function () { return view('pages/visi'); });
Route::get('/page/kontak', function () { return view('pages/kontak'); });
Route::get('/page/karir', function () { return view('pages/karir'); });
Route::get('/page/solution', function () { return view('pages/solution'); })->name('public.solution');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::post('/career/submit', [CareerController::class, 'store'])->name('career.submit');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('change.lang');

/*
|--------------------------------------------------------------------------
| 2. AUTENTIKASI
|--------------------------------------------------------------------------
*/
Route::get('page/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login-process', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 3. ADMIN PANEL (Prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- PENGELUARAN, CUTI, & ABSENSI (ADMIN, STAFF, MENTOR) ---
    Route::middleware(['role:admin,staff,mentor'])->group(function () {

        // Absensi Kantor (Dipindahkan ke sini agar Mentor bisa akses)
        Route::get('/attendance', [OfficeAttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/clock-in', [OfficeAttendanceController::class, 'clockIn'])->name('attendance.clockIn');
        Route::put('/attendance/clock-out/{id}', [OfficeAttendanceController::class, 'clockOut'])->name('attendance.clockOut');
        Route::get('/attendance/export', [OfficeAttendanceController::class, 'exportExcel'])->name('attendance.export');

        Route::resource('expenses', ExpenseController::class);

        // Modul Cuti
        Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
        Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/leaves/{id}', [LeaveController::class, 'show'])->name('leaves.show');
        Route::post('/leaves/mark-all-read', [LeaveController::class, 'markAllRead'])->name('leaves.markAllRead');

        // ==========================================
        // TAMBAHKAN INI: Modul Pusat Dokumen (E-Archive)
        // ==========================================
        Route::get('/documents', [\App\Http\Controllers\Admin\DocumentController::class, 'index'])->name('documents.index');
        Route::post('/documents', [\App\Http\Controllers\Admin\DocumentController::class, 'store'])->name('documents.store');
        Route::put('/documents/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'update'])->name('documents.update');
        Route::get('/documents/{id}/download', [\App\Http\Controllers\Admin\DocumentController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // --- KONTEN WEB (ADMIN & STAFF SAJA) ---
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::resource('testimonials', TestimonialController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('galleries', GalleryController::class);
        Route::resource('mentors', MentorController::class);
    });

    // --- AKADEMIK (ADMIN & MENTOR) ---
    Route::middleware(['role:admin,mentor'])->group(function () {
        Route::resource('schools', SchoolController::class);
        Route::get('/schools/{id}/export-pdf', [SchoolController::class, 'exportPdf'])->name('schools.export_pdf');
        Route::resource('students', StudentController::class);
        Route::resource('projects', ProjectController::class);
    });

    // --- KHUSUS SUPER ADMIN (Role: Admin) ---
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('careers', CareerAdminController::class);
        Route::resource('programs', ProgramController::class);

        // Keuangan & Pembayaran
        Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
        Route::get('/finance/school/{id}', [FinanceController::class, 'show'])->name('finance.show');
        Route::post('/finance/pay', [FinanceController::class, 'store'])->name('finance.store');

        // Approval Pengeluaran (Expenses)
        Route::put('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
        Route::put('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');

        // Approval Cuti (Leaves)
        Route::put('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::put('/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    });
});

/*
|--------------------------------------------------------------------------
| 4. MENTOR AREA (Prefix: /mentor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [MentorDashboard::class, 'index'])->name('dashboard');

    Route::get('/students/create', [MentorStudent::class, 'create'])->name('students.create');
    Route::post('/students', [MentorStudent::class, 'store'])->name('students.store');

    Route::get('/activities', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/activity/create', [ActivityController::class, 'create'])->name('activity.create');
    Route::post('/activity', [ActivityController::class, 'store'])->name('activity.store');
    Route::get('/activity/export', [ActivityController::class, 'export'])->name('activity.export');
    Route::get('/activity/{id}', [ActivityController::class, 'show'])->name('activity.show');

    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
});

/*
|--------------------------------------------------------------------------
| 5. API & SYSTEM UTILITIES
|--------------------------------------------------------------------------
*/
// Route untuk update sistem database di hosting tanpa terminal
Route::get('/update-app-system', function() {
    \Illuminate\Support\Facades\Artisan::call('migrate', ["--force" => true]);
    return "Database Migrated!";
});

Route::get('/api/school-programs/{id}', function($id) {
    return response()->json(\App\Models\Program::where('school_id', $id)->get());
})->name('api.school.programs');

Route::get('/cek-uang', function() {
    $pembayaran = \App\Models\Payment::all();
    $lunas = $pembayaran->filter(fn($p) => in_array(strtolower($p->status), ['paid', 'settlement', 'success', 'capture']));
    return [
        'SYSTEM_INFO' => ['Time' => date('Y-m-d H:i:s')],
        'STATS' => [
            'Total' => $pembayaran->count(),
            'Lunas_Count' => $lunas->count(),
            'Total_Uang' => number_format($lunas->sum('amount')),
        ]
    ];
});
