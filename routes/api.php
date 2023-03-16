<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StrandController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SeniorSubjectController;
use App\Http\Controllers\JuniorSubjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\JHSController;
use App\Http\Controllers\SHSController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionLogController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('auth-password', [AuthController::class, 'changePassword']);
Route::post('auth-signin', [AuthController::class, 'signin']);
Route::post('admin-profile', [AuthController::class,'updateAdmin']);
Route::resource('auth', AuthController::class);
Route::resource('school', SchoolController::class);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('get-student', [DashboardController::class, 'getStudent']);
Route::get('get-enrolled', [DashboardController::class, 'getEnrolled']);
Route::get('get-section', [DashboardController::class, 'getSection']);

Route::get('get-active', [SchoolYearController::class, 'getActiveSY']);
Route::post('set-active', [SchoolYearController::class, 'setActive']);
Route::get('get-sy', [SchoolYearController::class, 'getSchoolYear']);
Route::resource('school-year', SchoolYearController::class);
Route::resource('strand', StrandController::class);
Route::get('list-teacher', [TeacherController::class, 'listTeacher']);
Route::resource('teacher', TeacherController::class);
Route::resource('section', SectionController::class);
Route::resource('senior-subject', SeniorSubjectController::class);
Route::resource('junior-subject', JuniorSubjectController::class);

Route::get('enroll-schedule', [ScheduleController::class, 'getEnrollSchedule']);
Route::get('academic-year', [ScheduleController::class, 'defaultAcademicYear']);
Route::get('list-strand', [ScheduleController::class, 'getStrand']);
Route::get('list-section/{id}', [ScheduleController::class, 'getSection']);
Route::get('list-subject/{id}', [ScheduleController::class, 'getSubject']);
Route::resource('schedule', ScheduleController::class);
Route::post('student-restore', [StudentController::class,'restoreStudent']);
Route::get('student-archive', [StudentController::class,'archiveStudent']);
Route::resource('student', StudentController::class);
Route::resource('jhs-grade', JHSController::class);
Route::resource('shs-grade', SHSController::class);
Route::get('enroll-auth', [EnrollController::class, 'getActiveEnrolled']);
Route::post('enroll-drop', [EnrollController::class, 'dropEnr']);
Route::get('enroll-history', [EnrollController::class, 'enrollmentHistory']);
Route::resource('enroll', EnrollController::class);

Route::post('users-pass', [UserController::class, 'changePassword']);
Route::resource('users', UserController::class);
Route::resource('class-list', ClassListController::class);
Route::get('student-list', [StudentController::class, 'listOfStudent']);
Route::get('auth-user', [AuthController::class, 'authUser']);
Route::get('auth-year', [EnrollController::class, 'authEnrollYear']);
Route::resource('transaction-log', TransactionLogController::class);


Route::middleware('auth:sanctum')->group(function () {
});