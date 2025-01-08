<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboarController;
use App\Http\Controllers\KandidatController;
use App\Http\Controllers\PemiluController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::get('/helloworld', function () {
    return 'helloworld';
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'indexLogin')->name('view.login');
    Route::post('/login', 'login')->name('post.login');
    Route::post('/logout', 'logout')->name('post.logout')->middleware('auth');
});

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile/me', 'profile')->name('profile')->middleware('auth');
    Route::post('/update/profile/me', 'updateProfile')->name('profile.update')->middleware('auth');
    Route::post('/update-image/profile/me', 'updateProfileImage')->name('profile.update.image')->middleware('auth');
});

Route::group(['middleware' => ['auth', 'role:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [DashboarController::class, 'dashboard'])->name('admin.dashboard');

    Route::prefix('manage')->group(function () {
        Route::prefix('class')->controller(ClassController::class)->group(function () {
            Route::get('/',  'manageClass')->name('admin.manage.class');
            Route::post('/add',  'addClass')->name('admin.manage.class.add');
            Route::post('/import',  'importClass')->name('admin.manage.class.import');
            Route::post('/{id}/update',  'updateClass')->name('admin.manage.class.update');
            Route::delete('/{slug}/delete',  'deleteClass')->name('admin.manage.class.delete');

            Route::get('/data', 'data')->name('admin.class.data');
            Route::get('/{id}/data', 'dataById')->name('admin.class.data.id');
        });

        Route::prefix('users')->controller(UserController::class)->group(function () {
            Route::get('/', 'manageUser')->name('admin.manage.users');
            Route::post('/add', 'addUser')->name('admin.manage.user.add');
            Route::post('/import', 'importUser')->name('admin.manage.user.import');
            Route::get('/export', 'exportUser')->name('admin.manage.user.export');
            Route::post('/{id}/update', 'updateUser')->name('admin.manage.user.update');
            Route::delete('/{username}/delete', 'deleteUser')->name('admin.manage.user.delete');

            Route::get('/data', 'data')->name('admin.user.data');
            Route::get('/data/admin', 'dataAdmin')->name('admin.user.data.admin');
            Route::get('/{id}/data', 'dataById')->name('admin.user.data.id');
        });

        Route::prefix('pemilu')->group(function () {
            Route::controller(PemiluController::class)->group(function () {
                Route::get('', 'managePemilu')->name('admin.manage.pemilu');
                Route::post('/add', 'addPemilu')->name('admin.manage.pemilu.add');
                Route::post('/{slug}/edit', 'updatePemilu')->name('admin.manage.pemilu.edit');
                Route::delete('/{slug}/delete', 'deletePemilu')->name('admin.manage.pemilu.delete');

                Route::get('/data', [PemiluController::class, 'data'])->name('admin.pemilu.data');
                Route::get('/{slug}/data', [PemiluController::class, 'dataBySlug'])->name('admin.pemilu.data.slug');
                Route::get('/{slug}/result/data', [PemiluController::class, 'dataResultVoting'])->name('admin.result.data');
                Route::get('/{slug}/vote-logs/data', [PemiluController::class, 'dataVoteLogs'])->name('admin.vote-logs.data');
            });

            Route::controller(KandidatController::class)->group(function () {
                Route::get('/{slug}/kandidat', 'kandidatPemilu')->name('admin.manage.pemilu.kandidat');
                Route::get('/{slug}/kandidat/export/result', 'exportResultPdf')->name('admin.manage.pemilu.export.result');
                Route::post('/{slug}/kandidat/add', 'addKandidatPemilu')->name('admin.manage.pemilu.kandidat.add');
                Route::post('/{slug}/kandidat/{id}/update', 'updateKandidatPemilu')->name('admin.manage.pemilu.kandidat.update');
                Route::delete('/{slug}/kandidat/{id}/delete', 'deleteKandidatPemilu')->name('admin.manage.pemilu.kandidat.delete');

                Route::get('/{slug}/kandidat/data', 'data')->name('admin.kandidat.data');
                Route::get('/{slug}/kandidat/{id}/data', 'dataById')->name('admin.kandidat.data.id');
            });
        });
    });
});

Route::group(['middleware' => ['auth'], 'prefix' => 'user'], function () {
    Route::get('/dashboard', [DashboarController::class, 'dashboard'])->name('user.dashboard');

    Route::get('/pemilu/{slug}/kandidat/{id}/data', [KandidatController::class, 'dataById'])->name('user.kandidat.data');
    Route::get('/event/pemilu/{slug}/join', [DashboarController::class, 'joinPemilu'])->name('user.pemilu.join')->middleware('verify.pemilu.password');
    Route::post('/event/pemilu/{slug}/kandidat/{id}/vote', [DashboarController::class, 'votePemilu'])->name('user.pemilu.vote');
    Route::post('/pemilu/{slug}/verify-password/join', [DashboarController::class, 'verifyPasswordJoin'])->name('user.pemilu.verify-password.join');
});

Route::get('/copyright', function () {
    return view('copyright');
})->name('copyright');
