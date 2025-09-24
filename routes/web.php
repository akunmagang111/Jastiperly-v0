<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AiapplicationController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ComponentpageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RoleandaccessController;
use App\Http\Controllers\CryptocurrencyController;

use App\Http\Controllers\_admin\AdminDashboardController;
use App\Http\Controllers\_finance\FinanceDashboardController;
use App\Http\Controllers\_superadmin\SuperadminDashboardController;


// ------------------- AUTHENTICATION (Breeze + Custom) -------------------
Route::get('/login', [AuthenticatedSessionController::class, 'signIn'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
Route::get('/register', [AuthenticatedSessionController::class, 'signUp']);
Route::get('/logoutnih', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

// ===========================space for landing page============================
Route::get('/', function () {
    return redirect('/login');
});

// Dashboard default (redirect sesuai role)
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin' => redirect('/admin/dashboard'),
        'finance' => redirect('/finance/dashboard'),
        'superadmin' => redirect('/superadmin/dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// =================================================================================================================
// --------------------------------------------------- ADMIN -------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/dashboard2', [AdminDashboardController::class, 'index2'])->name('admin.index2');
    Route::get('/dashboard3', [AdminDashboardController::class, 'index3'])->name('admin.index3');
    Route::get('/dashboard4', [AdminDashboardController::class, 'index4'])->name('admin.index4');
    Route::get('/dashboard5', [AdminDashboardController::class, 'index5'])->name('admin.index5');
    Route::get('/dashboard6', [AdminDashboardController::class, 'index6'])->name('admin.index6');
    Route::get('/dashboard7', [AdminDashboardController::class, 'index7'])->name('admin.index7');
    Route::get('/dashboard8', [AdminDashboardController::class, 'index8'])->name('admin.index8');
    Route::get('/dashboard9', [AdminDashboardController::class, 'index9'])->name('admin.index9');
    Route::get('/dashboard10', [AdminDashboardController::class, 'index10'])->name('admin.index10');
});


// --------------------------------------------------- FINANCE -------------------------------------------------------
Route::middleware(['auth', 'role:finance'])->prefix('finance')->group(function () {
    Route::get('/dashboard', [FinanceDashboardController::class, 'index'])->name('finance.index');

    Route::get('/dashboard2', [FinanceDashboardController::class, 'index2'])->name('finance.index2');
    // Buy Transaction Delete
    Route::delete('/finance/buy-transactions/{id}', [FinanceDashboardController::class, 'destroyBuy'])
        ->name('finance.buy.destroy');
    // Send Transaction Delete
    Route::delete('/finance/send-transactions/{id}', [FinanceDashboardController::class, 'destroySend'])
        ->name('finance.send.destroy');

    Route::get('/dashboard3', [FinanceDashboardController::class, 'index3'])->name('finance.index3');
    Route::post('/commissions/{id}/validate', [FinanceDashboardController::class, 'validateCommission'])
    ->name('commissions.validate');
    Route::delete('/{id}', [FinanceDashboardController::class, 'destroy'])->name('commissions.destroy');
    
    Route::get('/dashboard4', [FinanceDashboardController::class, 'index4'])->name('finance.index4');
    Route::get('/transactions/{id}', [FinanceDashboardController::class, 'show'])
        ->name('finance.transactions.show');


    Route::get('/dashboard5', [FinanceDashboardController::class, 'index5'])->name('finance.index5');
    Route::put('/finance/refunds/{id}', [FinanceDashboardController::class, 'updateRefund'])->name('finance.refund.update');

    Route::get('/dashboard6', [FinanceDashboardController::class, 'index6'])->name('finance.index6');
    Route::post('/update-profile', [FinanceDashboardController::class, 'updateProfile'])->name('finance.updateProfile');
    Route::post('/update-password', [FinanceDashboardController::class, 'updatePassword'])->name('finance.updatePassword');
    Route::post('/update-profile-image', [FinanceDashboardController::class, 'updateProfileImage'])
    ->name('finance.updateProfileImage');
    Route::post('/finance/update-idcard', [FinanceDashboardController::class, 'updateIdCard'])->name('finance.updateIdCard');
    Route::post('/finance/update-passport', [FinanceDashboardController::class, 'updatePassport'])->name('finance.updatePassport');


    Route::get('/dashboard7', [FinanceDashboardController::class, 'index7'])->name('finance.index7');
    Route::get('/dashboard8', [FinanceDashboardController::class, 'index8'])->name('finance.index8');
    Route::get('/dashboard9', [FinanceDashboardController::class, 'index9'])->name('finance.index9');
    Route::get('/dashboard10', [FinanceDashboardController::class, 'index10'])->name('finance.index10');
});


// --------------------------------------------------- SUPERADMIN --------------------------------------------------
Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');


    // Profile
    Route::get('/profile', [SuperadminDashboardController::class, 'profile'])->name('viewProfile');

    // Users
    Route::get('/users', [SuperadminDashboardController::class, 'index2'])->name('users.index');
    Route::get('/users/export', [SuperadminDashboardController::class, 'usersExport'])->name('users.export');
    Route::get('/users/{user}', [SuperadminDashboardController::class, 'usersShow'])->name('users.show');
    Route::get('/users/{user}/edit', [SuperadminDashboardController::class, 'usersEdit'])->name('users.edit');
    Route::put('/users/{user}', [SuperadminDashboardController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{user}', [SuperadminDashboardController::class, 'usersDestroy'])->name('users.destroy');

    
    // =================== Products ===================
Route::get('products', [SuperadminDashboardController::class, 'index3'])->name('products.index');
Route::get('products/create', [SuperadminDashboardController::class, 'create'])->name('products.create');
Route::post('products/store', [SuperadminDashboardController::class, 'store'])->name('products.store');
Route::get('products/{id}', [SuperadminDashboardController::class, 'showProduct'])->name('products.show');
Route::get('products/{id}/edit', [SuperadminDashboardController::class, 'editProduct'])->name('products.edit');
Route::put('products/{id}', [SuperadminDashboardController::class, 'updateProduct'])->name('products.update');
Route::delete('products/{id}', [SuperadminDashboardController::class, 'destroyProduct'])->name('products.destroy');
Route::post('products/{id}/validate', [SuperadminDashboardController::class, 'validateProduct'])->name('products.validate');
Route::get('products-export', [SuperadminDashboardController::class, 'exportProducts'])->name('products.export');


// =================== Transactions ===================
Route::get('/transactions', [SuperadminDashboardController::class, 'index4'])->name('transactions.index');
Route::get('/transactions/export', [SuperadminDashboardController::class, 'exportTransactions'])->name('transactions.export');
Route::get('/transactions/{id}/edit', [SuperadminDashboardController::class, 'editTransaction'])->name('transactions.edit');
Route::get('/transactions/{id}', [SuperadminDashboardController::class, 'showTransaction'])->name('transactions.show');
Route::put('/transactions/{id}', [SuperadminDashboardController::class, 'updateTransaction'])->name('transactions.updateTransaction');
Route::delete('/transactions/{id}', [SuperadminDashboardController::class, 'destroyTransaction'])->name('transactions.destroy');

   // =================== Refunds ===================
Route::get('/refunds', [SuperadminDashboardController::class, 'refundsIndex'])->name('refunds.index');
Route::get('/refunds/export', [SuperadminDashboardController::class, 'exportRefunds'])->name('refunds.export');
Route::get('/refunds/{id}', [SuperadminDashboardController::class, 'refundsShow'])->name('refunds.show');
Route::get('/refunds/{id}/edit', [SuperadminDashboardController::class, 'refundsEdit'])->name('refunds.edit');
Route::put('/refunds/{id}', [SuperadminDashboardController::class, 'refundsUpdate'])->name('refunds.update');
Route::delete('/refunds/{id}', [SuperadminDashboardController::class, 'refundsDestroy'])->name('refunds.destroy');



    // Settings
    Route::get('/settings', [SuperadminDashboardController::class, 'settingsIndex'])->name('settings.index');
    Route::post('/settings/update', [SuperadminDashboardController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/preference', [SuperadminDashboardController::class, 'settingsUpdatePreference'])->name('settings.preference');
    Route::post('/settings/password', [SuperadminDashboardController::class, 'settingsUpdatePassword'])->name('settings.password');
});




// -----------------------------------------------------------------------------------------------------------------
// =================================================================================================================

// ----------------------------------------- ROUTE LAMA (dibungkus auth) -------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard lama
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard/index', 'index')->name('index');
    });

    // Home
    Route::controller(HomeController::class)->group(function () {
        Route::get('calendar', 'calendar')->name('calendar');
        Route::get('chatmessage', 'chatMessage')->name('chatMessage');
        Route::get('chatempty', 'chatempty')->name('chatempty');
        Route::get('email', 'email')->name('email');
        Route::get('error', 'error1')->name('error');
        Route::get('faq', 'faq')->name('faq');
        Route::get('gallery', 'gallery')->name('gallery');
        Route::get('kanban', 'kanban')->name('kanban');
        Route::get('pricing', 'pricing')->name('pricing');
        Route::get('termscondition', 'termsCondition')->name('termsCondition');
        Route::get('widgets', 'widgets')->name('widgets');
        Route::get('chatprofile', 'chatProfile')->name('chatProfile');
        Route::get('veiwdetails', 'veiwDetails')->name('veiwDetails');
        Route::get('blankPage', 'blankPage')->name('blankPage');
        Route::get('comingSoon', 'comingSoon')->name('comingSoon');
        Route::get('maintenance', 'maintenance')->name('maintenance');
        Route::get('starred', 'starred')->name('starred');
        Route::get('testimonials', 'testimonials')->name('testimonials');
    });

    // aiApplication
    Route::prefix('aiapplication')->controller(AiapplicationController::class)->group(function () {
        Route::get('/codegenerator', 'codeGenerator')->name('codeGenerator');
        Route::get('/codegeneratornew', 'codeGeneratorNew')->name('codeGeneratorNew');
        Route::get('/imagegenerator', 'imageGenerator')->name('imageGenerator');
        Route::get('/textgeneratornew', 'textGeneratorNew')->name('textGeneratorNew');
        Route::get('/textgenerator', 'textGenerator')->name('textGenerator');
        Route::get('/videogenerator', 'videoGenerator')->name('videoGenerator');
        Route::get('/voicegenerator', 'voiceGenerator')->name('voiceGenerator');
    });

    // Authentication UI
    Route::prefix('authentication')->controller(AuthenticationController::class)->group(function () {
        Route::get('/forgotpassword', 'forgotPassword')->name('forgotPassword');
        Route::get('/signin', 'signin')->name('signin');
        Route::get('/signup', 'signup')->name('signup');
    });

    // Chart
    Route::prefix('chart')->controller(ChartController::class)->group(function () {
        Route::get('/columnchart', 'columnChart')->name('columnChart');
        Route::get('/linechart', 'lineChart')->name('lineChart');
        Route::get('/piechart', 'pieChart')->name('pieChart');
    });

    // Componentpage
    Route::prefix('componentspage')->controller(ComponentpageController::class)->group(function () {
        Route::get('/alert', 'alert')->name('alert');
        Route::get('/avatar', 'avatar')->name('avatar');
        Route::get('/badges', 'badges')->name('badges');
        Route::get('/button', 'button')->name('button');
        Route::get('/calendar', 'calendar')->name('calendar');
        Route::get('/card', 'card')->name('card');
        Route::get('/carousel', 'carousel')->name('carousel');
        Route::get('/colors', 'colors')->name('colors');
        Route::get('/dropdown', 'dropdown')->name('dropdown');
        Route::get('/imageupload', 'imageUpload')->name('imageUpload');
        Route::get('/list', 'list')->name('list');
        Route::get('/pagination', 'pagination')->name('pagination');
        Route::get('/progress', 'progress')->name('progress');
        Route::get('/radio', 'radio')->name('radio');
        Route::get('/starrating', 'starRating')->name('starRating');
        Route::get('/switch', 'switch')->name('switch');
        Route::get('/tabs', 'tabs')->name('tabs');
        Route::get('/tags', 'tags')->name('tags');
        Route::get('/tooltip', 'tooltip')->name('tooltip');
        Route::get('/typography', 'typography')->name('typography');
        Route::get('/videos', 'videos')->name('videos');
    });

    // Forms
    Route::prefix('forms')->controller(FormsController::class)->group(function () {
        Route::get('/form-layout', 'formLayout')->name('formLayout');
        Route::get('/form-validation', 'formValidation')->name('formValidation');
        Route::get('/form', 'form')->name('form');
        Route::get('/wizard', 'wizard')->name('wizard');
    });

    // Invoice
    Route::prefix('invoice')->controller(InvoiceController::class)->group(function () {
        Route::get('/invoice-add', 'invoiceAdd')->name('invoiceAdd');
        Route::get('/invoice-edit', 'invoiceEdit')->name('invoiceEdit');
        Route::get('/invoice-list', 'invoiceList')->name('invoiceList');
        Route::get('/invoice-preview', 'invoicePreview')->name('invoicePreview');
    });

    // Settings
    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('/company', 'company')->name('company');
        Route::get('/currencies', 'currencies')->name('currencies');
        Route::get('/language', 'language')->name('language');
        Route::get('/notification', 'notification')->name('notification');
        Route::get('/notification-alert', 'notificationAlert')->name('notificationAlert');
        Route::get('/payment-gateway', 'paymentGateway')->name('paymentGateway');
        Route::get('/theme', 'theme')->name('theme');
    });

   // Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [SuperadminDashboardController::class, 'profileIndex'])->name('profile.index');
    Route::put('/profile', [SuperadminDashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::put('/profile/password', [SuperadminDashboardController::class, 'profilePassword'])->name('profile.password');
});


    // Table
    Route::prefix('table')->controller(TableController::class)->group(function () {
        Route::get('/tablebasic', 'tableBasic')->name('tableBasic');
        Route::get('/tabledata', 'tableData')->name('tableData');
    });

    // Users
   // Route::prefix('users')->controller(UsersController::class)->group(function () {
   //     Route::get('/add-user', 'addUser')->name('addUser');
   //     Route::get('/users-grid', 'usersGrid')->name('usersGrid');
   //     Route::get('/users-list', 'usersList')->name('usersList');
   //     Route::get('/view-profile', 'viewProfile')->name('viewProfile');
   // });

    // Blog
    Route::prefix('blog')->controller(BlogController::class)->group(function () {
        Route::get('/addBlog', 'addBlog')->name('addBlog');
        Route::get('/blog', 'blog')->name('blog');
        Route::get('/blogDetails', 'blogDetails')->name('blogDetails');
    });

    // Role & Access
    Route::prefix('roleandaccess')->controller(RoleandaccessController::class)->group(function () {
        Route::get('/assignRole', 'assignRole')->name('assignRole');
        Route::get('/roleAaccess', 'roleAaccess')->name('roleAaccess');
    });

    // Cryptocurrency
    Route::prefix('cryptocurrency')->controller(CryptocurrencyController::class)->group(function () {
        Route::get('/marketplace', 'marketplace')->name('marketplace');
        Route::get('/marketplacedetails', 'marketplaceDetails')->name('marketplaceDetails');
        Route::get('/portfolio', 'portfolio')->name('portfolio');
        Route::get('/wallet', 'wallet')->name('wallet');
    });
});