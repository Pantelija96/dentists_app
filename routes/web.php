<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkOrderStatusController;
use App\Http\Controllers\WorkTypeController;
use App\Http\Controllers\WorkTypeMaterialController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('index');});
Route::post('/theme/toggle', function () {
    $current = session('theme', 'dark');
    $new = $current === 'dark' ? 'light' : 'dark';
    session(['theme' => $new]);
    return response()->json(['theme' => $new]);
})->name('theme.toggle');

Route::get('/login', [AuthController::class, 'showLogin'])->name('showLogin');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/signup', [AuthController::class, 'showSignUp'])->name('showSignUp');
Route::post('/signup', [AuthController::class, 'firstStepSignUp'])->name('firstStepSignUp');

Route::get('/additionalinformation', [AuthController::class, 'showAdditionalInformation'])->name('showAdditionalInformation');
Route::post('/additionalinformation', [AuthController::class, 'secondStepSignUp'])->name('secondStepSignUp');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::prefix('work_type')->group(function () {
        Route::get('/materials/{workType}', [WorkTypeController::class, 'materials'])->name('work_type.materials');
        Route::get('/{workType}/material/{material}/parameters', [WorkTypeController::class, 'parameters'])->name('work_type.material.parameters');
    });

    Route::prefix('work-orders')->group(function () {
        Route::get('/add-new', [WorkController::class, 'showAddNew'])->name('work.show.addNew');
        Route::post('/add', [WorkController::class, 'store'])->name('work.store');
        Route::get('/inspect/{id}', [WorkController::class, 'inspect'])->name('work.inspect');
        Route::get('/download-all/{id}', [WorkController::class, 'downloadAll'])->name('work.download');
        Route::post('/addcomment/{work_order}', [WorkController::class, 'addcomment'])->name('work.addcomment');
        Route::get('/remove/{work_order}', [WorkController::class, 'remove'])->name('work.remove');
        Route::get('/edit/{id}', [WorkController::class, 'edit'])->name('work.edit');
        Route::post('/update/{id}', [WorkController::class, 'update'])->name('work.update');
        Route::get('/publish/{work_order}', [WorkController::class, 'publish'])->name('work.publish');
    });

    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/inspect/{id}', [UserController::class, 'inspect'])->name('user.inspect');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/{notification}/read',[NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    });
});


Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::prefix('users')->group(function () {
        Route::get('/approve/{id}', [UserController::class, 'approve'])->name('admin.users.approve');
        Route::get('/deny/{id}', [UserController::class, 'deny'])->name('admin.users.deny');
        Route::get('/{user}', [UserController::class, 'destroy'])->name('admin.users.delete');
        Route::get('/users-work-orders/{user}', [AdminController::class, 'showUsersWorkOrders'])->name('admin.users.work-orders');
    });

    Route::prefix('work-orders')->group(function () {
        Route::delete('/{work_order}', [WorkController::class, 'destroy'])->name('admin.work.delete');
        Route::post('/changestatus/{work_order}', [WorkController::class, 'changestatus'])->name('admin.work.changestatus');
    });
});







Route::get('/_optimize', function () {

    Artisan::call('optimize');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('view:clear');
    Artisan::call('view:cache');

    return response()->json([
        'status' => 'ok',
        'message' => 'Application optimized successfully'
    ]);
});

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(
        in_array($locale, config('app.supported_locales')),
        400
    );
//    app()->setLocale($locale);
    session(['locale' => $locale]);
    return back();
});










//
//
//
//Route::middleware(['super-admin'])->group(function () {});
//
//
//Route::middleware(['auth'])->group(function () {
//
//    Route::prefix('admin')->group(function () {
//
//        Route::get('/approveuser/{id}', [AdminController::class, 'approveuser'])->name('admin.approveuser');
//        Route::get('/denied/{id}', [AdminController::class, 'denied'])->name('admin.denied');
//        Route::get('/inspect/{id}', [AdminController::class, 'inspect'])->name('admin.inspect');
//
//
//        Route::prefix('regions')->group(function () {
//            Route::get('/{id?}', [RegionsController::class, 'regionsIndex'])->name('regions.index');
//            Route::post('/create', [RegionsController::class, 'regionsCreate'])->name('regions.create');
//            Route::post('/edit/{region}', [RegionsController::class, 'regionsEdit'])->name('regions.edit');
//            Route::post('/delete/{region}', [RegionsController::class, 'regionsDelete'])->name('regions.delete');
//        });
//
//        Route::prefix('workstatus')->group(function () {
//            Route::get('/{id?}', [WorkOrderStatusController::class, 'index'])->name('workstatus.index');
//            Route::post('/create', [WorkOrderStatusController::class, 'create'])->name('workstatus.create');
//            Route::post('/edit/{status}', [WorkOrderStatusController::class, 'edit'])->name('workstatus.edit');
//            Route::post('/delete/{status}', [WorkOrderStatusController::class, 'delete'])->name('workstatus.delete');
//        });
//
//        Route::prefix('delivery')->group(function () {
//            Route::get('/{id?}', [DeliveryController::class, 'deliveryIndex'])->name('delivery.index');
//            Route::post('/create', [DeliveryController::class, 'deliveryCreate'])->name('delivery.create');
//            Route::post('/edit/{delivery}', [DeliveryController::class, 'deliveryEdit'])->name('delivery.edit');
//            Route::post('/delete/{delivery}', [DeliveryController::class, 'deliveryDelete'])->name('delivery.delete');
//        });
//
//        Route::prefix('worktype')->group(function () {
//            Route::get('/{id?}', [WorkTypeController::class, 'index'])->name('worktype.index');
//            Route::post('/create', [WorkTypeController::class, 'create'])->name('worktype.create');
//            Route::post('/edit/{type}', [WorkTypeController::class, 'edit'])->name('worktype.edit');
//            Route::post('/delete/{type}', [WorkTypeController::class, 'delete'])->name('worktype.delete');
//        });
//
//        Route::prefix('materials')->group(function () {
//            Route::get('/{id?}', [WorkTypeMaterialController::class, 'index'])->name('materials.index');
//            Route::post('/create', [WorkTypeMaterialController::class, 'create'])->name('materials.create');
//            Route::post('/edit/{material}', [WorkTypeMaterialController::class, 'edit'])->name('materials.edit');
//            Route::post('/delete/{material}', [WorkTypeMaterialController::class, 'delete'])->name('materials.delete');
//        });
//    });
//
//
//
//
//
//
//});
//
//
//
//Route::middleware(['auth'])->group(function () {
//
//    Route::prefix('user')->middleware(['auth'])->group(function () {
//        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
//        Route::get('/inspect/{id}', [UserController::class, 'inspect'])->name('user.inspect');
//
//        Route::get('/work/new', [WorkController::class, 'new'])->name('work.new');
//        Route::post('/work/add', [WorkController::class, 'add'])->name('work.add');
//        Route::post('/work-orders/{id}/update', [WorkController::class, 'update'])->name('work.update');
//        Route::get('/materials-by-work-type/{id}', [WorkController::class, 'getMaterials'])->name('work.materials');
//    });
//});
