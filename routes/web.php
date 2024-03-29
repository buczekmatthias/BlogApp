<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WarrantController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\TagController;
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

Route::controller(AppController::class)->name("app.")->group(function () {
    Route::get("/", 'index')->name('homepage');
    Route::get("/search", 'search')->name('search');
});

Route::controller(SecurityController::class)->prefix('security')->name('security.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'handleLogin');

        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'handleRegister');
    });

    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::controller(TagController::class)->name('tags.')->prefix('tags')->group(function () {
    Route::get('/', 'list')->name('list');
    Route::get('/{tag:name}', 'view')->name('view');
});

Route::controller(AuthorController::class)->name('authors.')->prefix('authors')->group(function () {
    Route::get('/', 'list')->name('list');
});

Route::controller(ArticleController::class)->name('articles.')->prefix('articles')->group(function () {
    Route::get('/', 'list')->name('list');

    Route::get('/images/remove', 'removeImage')->name('removeImage');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'handleCreate');
    Route::get('/create/layout', 'createLayout')->name('createLayout');
    Route::post('/create/layout', 'handleCreateLayout');

    Route::get('/{article:slug}/edit', 'edit')->name('edit');
    Route::post('/{article:slug}/edit', 'handleEdit');
    Route::get('/{article:slug}/edit/layout', 'editLayout')->name('editLayout');
    Route::post('/{article:slug}/edit/layout', 'handleEditLayout');

    Route::get('/{article:slug}', 'view')->name('view');

    Route::middleware('auth')->group(function () {
        Route::post('/{article:slug}/comment', 'addComment')->name('newComment');
        Route::get('/{article:slug}/like', 'like')->name('like');
        Route::get('/{article:slug}/bookmark', 'bookmark')->name('bookmark');
    });
});

Route::controller(UserController::class)->name('user.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/settings', 'updateSettings');
        Route::get('/u/{user:username}/follow', 'newFollower')->name('newFollow');
    });
    Route::get('/u/{user:username}/{view?}', 'profile')->name('profile');
});

Route::post('/submit-report', [ReportController::class, 'submitReport'])->name('submitReport')->middleware('auth');

// Admin routes
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::controller(AdminArticleController::class)->prefix('articles')->name('articles.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{article:slug}/edit', 'edit')->name('edit');
        Route::get('/{article:slug}/delete', 'delete')->name('delete');
    });

    Route::controller(CommentController::class)->prefix('comments')->name('comments.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{comment}/delete', 'delete')->name('delete');
    });

    Route::controller(AdminReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{report}/delete', 'delete')->name('delete');
    });

    Route::controller(AdminTagController::class)->prefix('tags')->name('tags.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::post('/create', 'create')->name('create');
        Route::post('/{tag}/edit', 'handleEdit')->name('edit');
        Route::get('/{tag}/delete', 'delete')->name('delete');
    });

    Route::controller(AdminUserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::post('/{user:username}/roles', 'changeRoles')->name('changeRoles');
        Route::get('/{user:username}/disable', 'disable')->name('disable');
        Route::get('/{user:username}/delete', 'delete')->name('delete');
    });

    Route::controller(WarrantController::class)->prefix('warrants')->name('warrants.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::post('/create', 'handleCreate')->name('create');
        Route::post('/{warrant}/edit', 'handleEdit')->name('edit');
        Route::get('/{warrant}/delete', 'delete')->name('delete');
    });
});
