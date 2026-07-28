<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BeadTypeController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\DiamondClarityController;
use App\Http\Controllers\DiamondColorController;
use App\Http\Controllers\DiamondCutController;
use App\Http\Controllers\DiamondTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinishProductController;
use App\Http\Controllers\GoldImpurityPurchaseController;
use App\Http\Controllers\GoldRateController;
use App\Http\Controllers\JobPurchaseController;
use App\Http\Controllers\JobTaskActivityController;
use App\Http\Controllers\JobTaskController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\OtherProductController;
use App\Http\Controllers\OtherPurchaseController;
use App\Http\Controllers\OtherSaleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RattiKaatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockTakingController;
use App\Http\Controllers\StoneCategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

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

Route::get('/', [App\Http\Controllers\StorefrontController::class, 'index'])->name('storefront.index');
Route::redirect('/storefront', '/');
Route::redirect('/storefront/categories', '/categories');
Route::redirect('/storefront/search', '/search');
Route::redirect('/storefront/contact', '/contact');
Route::redirect('/storefront/product/{id}', '/product/{id}');
Route::redirect('/storefront/cart', '/cart');
Route::redirect('/storefront/checkout', '/checkout');

Route::get('/categories', [App\Http\Controllers\StorefrontController::class, 'categories'])->name('storefront.categories');
Route::get('/search', [App\Http\Controllers\StorefrontController::class, 'search'])->name('storefront.search');
Route::get('/contact', [App\Http\Controllers\StorefrontController::class, 'contact'])->name('storefront.contact');
Route::get('/product/{id}', [App\Http\Controllers\StorefrontController::class, 'show'])->name('storefront.show');
Route::get('/cart', [App\Http\Controllers\StorefrontController::class, 'cart'])->name('storefront.cart');
Route::post('/cart/add/{id}', [App\Http\Controllers\StorefrontController::class, 'addToCart'])->name('storefront.cart.add');
Route::post('/cart/update/{id}', [App\Http\Controllers\StorefrontController::class, 'updateCart'])->name('storefront.cart.update');
Route::post('/cart/remove/{id}', [App\Http\Controllers\StorefrontController::class, 'removeFromCart'])->name('storefront.cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\StorefrontController::class, 'clearCart'])->name('storefront.cart.clear');
Route::get('/checkout', [App\Http\Controllers\StorefrontController::class, 'checkout'])->name('storefront.checkout');
Route::post('/checkout', [App\Http\Controllers\StorefrontController::class, 'placeOrder'])->name('storefront.checkout.place');
Route::get('/checkout/success', [App\Http\Controllers\StorefrontController::class, 'checkoutSuccess'])->name('storefront.checkout.success');
Route::get('/checkout/cancel', [App\Http\Controllers\StorefrontController::class, 'checkoutCancel'])->name('storefront.checkout.cancel');
Route::post('/checkout/paypal', [App\Http\Controllers\StorefrontController::class, 'payWithPaypal'])->name('storefront.paypal.pay');
Route::get('/checkout/paypal/return', [App\Http\Controllers\StorefrontController::class, 'paypalReturn'])->name('storefront.paypal.return');
Route::get('/checkout/paypal/cancel', [App\Http\Controllers\StorefrontController::class, 'paypalCancel'])->name('storefront.paypal.cancel');
Route::get('/page/{slug}', [App\Http\Controllers\StorefrontController::class, 'page'])->name('storefront.page');

Route::group(['middleware' => ['auth', 'locale']], function () {
    Route::get('/cms', [App\Http\Controllers\CmsController::class, 'index'])->name('cms.index');
    Route::get('/cms/settings', [App\Http\Controllers\CmsController::class, 'settings'])->name('cms.settings');
    Route::get('/cms/homepage', [App\Http\Controllers\CmsController::class, 'homepage'])->name('cms.homepage');
    Route::get('/cms/pages', [App\Http\Controllers\CmsController::class, 'pages'])->name('cms.pages');
    Route::get('/cms/catalog', [App\Http\Controllers\CmsController::class, 'catalog'])->name('cms.catalog');
    Route::post('/cms/setting', [App\Http\Controllers\CmsController::class, 'saveSetting'])->name('cms.setting.save');
    Route::post('/cms/section', [App\Http\Controllers\CmsController::class, 'saveSection'])->name('cms.section.save');
    Route::post('/cms/page', [App\Http\Controllers\CmsController::class, 'savePage'])->name('cms.page.save');
    Route::delete('/cms/section/{section}', [App\Http\Controllers\CmsController::class, 'deleteSection'])->name('cms.section.delete');
    Route::delete('/cms/page/{page}', [App\Http\Controllers\CmsController::class, 'deletePage'])->name('cms.page.delete');
});

Route::get('language/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'ar'], true)) {
        abort(404);
    }

    Session::put('locale', $locale);

    return back();
})->name('language.switch');

Auth::routes();

Route::group(['middleware' => ['auth', 'locale']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('data', [PermissionController::class, 'getData'])->name('permission.data');
        Route::get('create', [PermissionController::class, 'create']);
        Route::post('store', [PermissionController::class, 'store']);
        Route::get('edit/{id}', [PermissionController::class, 'edit']);
        Route::post('update', [PermissionController::class, 'update']);
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('data', [RoleController::class, 'getData'])->name('role.data');
        Route::get('create', [RoleController::class, 'create']);
        Route::post('store', [RoleController::class, 'store']);
        Route::get('edit/{id}', [RoleController::class, 'edit']);
        Route::post('update', [RoleController::class, 'update']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('data', [UserController::class, 'getData'])->name('user.data');
        Route::get('create', [UserController::class, 'create']);
        Route::post('store', [UserController::class, 'store']);
        Route::get('edit/{id}', [UserController::class, 'edit']);
        Route::post('update', [UserController::class, 'update']);
        // Route::get('destroy/{id}', [UserController::class,'destroy']);
        Route::get('status/{id}', [UserController::class, 'status']);
    });

    Route::group(['prefix' => 'warehouses'], function () {
        Route::get('/', [WarehouseController::class, 'index']);
        Route::post('data', [WarehouseController::class, 'getData'])->name('warehouse.data');
        Route::get('create', [WarehouseController::class, 'create']);
        Route::post('store', [WarehouseController::class, 'store']);
        Route::get('edit/{id}', [WarehouseController::class, 'edit']);
        Route::post('update', [WarehouseController::class, 'update']);
        Route::get('destroy/{id}', [WarehouseController::class, 'destroy']);
    });


    Route::group(['prefix' => 'accounts'], function () {
        Route::get('/', [AccountController::class, 'index']);
        Route::get('getMainAccounts', [AccountController::class, 'getMainAccounts'])->name('accounts.getMainAccounts');
        Route::get('getChildAccounts/{id}', [AccountController::class, 'getChildAccountsByParentId'])->name('accounts.getChildAccounts');
        Route::get('getAccountById/{id}', [AccountController::class, 'getAccountById'])->name('accounts.getAccountById');
        Route::post('addEditAccount', [AccountController::class, 'addEditAccount'])->name('accounts.addEditAccount');
        Route::get('statusAccounts/{id}', [AccountController::class, 'status'])->name('accounts.statusAccounts');
        Route::get('deleteAccounts/{id}', [AccountController::class, 'destroy'])->name('accounts.deleteAccounts');

        Route::get('/js/accounts.js', function () {
            $path = resource_path('views/accounts/js/accounts.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    Route::group(['prefix' => 'suppliers'], function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('data', [SupplierController::class, 'getData'])->name('supplier.data');
        Route::get('create', [SupplierController::class, 'create']);
        Route::post('store', [SupplierController::class, 'store']);
        Route::get('edit/{id}', [SupplierController::class, 'edit']);
        Route::post('update', [SupplierController::class, 'update']);
        Route::get('destroy/{id}', [SupplierController::class, 'destroy']);
        Route::get('status/{id}', [SupplierController::class, 'status']);
        Route::get('get-by-id/{id}', [SupplierController::class, 'getSupplierById']);
    });

    Route::group(['prefix' => 'journals'], function () {
        Route::get('/', [JournalController::class, 'index']);
        Route::post('data', [JournalController::class, 'getData'])->name('journal.data');
        Route::get('create', [JournalController::class, 'create']);
        Route::post('store', [JournalController::class, 'store']);
        Route::get('edit/{id}', [JournalController::class, 'edit']);
        Route::post('update', [JournalController::class, 'update']);
        Route::get('destroy/{id}', [JournalController::class, 'destroy']);
        Route::get('status/{id}', [JournalController::class, 'status']);
        Route::get('/js/JournalForm.js', function () {
            $path = resource_path('views/journal/js/JournalForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Bead Type
    Route::group(['prefix' => 'bead-type'], function () {
        Route::get('/', [BeadTypeController::class, 'index']);
        Route::post('data', [BeadTypeController::class, 'getData'])->name('bead-type.data');
        Route::get('create', [BeadTypeController::class, 'create']);
        Route::post('store', [BeadTypeController::class, 'store']);
        Route::get('edit/{id}', [BeadTypeController::class, 'edit']);
        Route::post('update', [BeadTypeController::class, 'update']);
        Route::get('destroy/{id}', [BeadTypeController::class, 'destroy']);
        Route::get('status/{id}', [BeadTypeController::class, 'status']);
        Route::get('/js/BeadTypeForm.js', function () {
            $path = resource_path('views/bead_type/js/BeadTypeForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Stone Category
    Route::group(['prefix' => 'stone-category'], function () {
        Route::get('/', [StoneCategoryController::class, 'index']);
        Route::post('data', [StoneCategoryController::class, 'getData'])->name('stone-category.data');
        Route::get('create', [StoneCategoryController::class, 'create']);
        Route::post('store', [StoneCategoryController::class, 'store']);
        Route::get('edit/{id}', [StoneCategoryController::class, 'edit']);
        Route::post('update', [StoneCategoryController::class, 'update']);
        Route::get('destroy/{id}', [StoneCategoryController::class, 'destroy']);
        Route::get('status/{id}', [StoneCategoryController::class, 'status']);
        Route::get('/js/StoneCategoryForm.js', function () {
            $path = resource_path('views/stone_category/js/StoneCategoryForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Diamond Type
    Route::group(['prefix' => 'diamond-type'], function () {
        Route::get('/', [DiamondTypeController::class, 'index']);
        Route::post('data', [DiamondTypeController::class, 'getData'])->name('diamond-type.data');
        Route::get('create', [DiamondTypeController::class, 'create']);
        Route::post('store', [DiamondTypeController::class, 'store']);
        Route::get('edit/{id}', [DiamondTypeController::class, 'edit']);
        Route::post('update', [DiamondTypeController::class, 'update']);
        Route::get('destroy/{id}', [DiamondTypeController::class, 'destroy']);
        Route::get('status/{id}', [DiamondTypeController::class, 'status']);
        Route::get('/js/DiamondTypeForm.js', function () {
            $path = resource_path('views/diamond_type/js/DiamondTypeForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Diamond Color
    Route::group(['prefix' => 'diamond-color'], function () {
        Route::get('/', [DiamondColorController::class, 'index']);
        Route::post('data', [DiamondColorController::class, 'getData'])->name('diamond-color.data');
        Route::get('create', [DiamondColorController::class, 'create']);
        Route::post('store', [DiamondColorController::class, 'store']);
        Route::get('edit/{id}', [DiamondColorController::class, 'edit']);
        Route::post('update', [DiamondColorController::class, 'update']);
        Route::get('destroy/{id}', [DiamondColorController::class, 'destroy']);
        Route::get('status/{id}', [DiamondColorController::class, 'status']);
        Route::get('/js/DiamondColorForm.js', function () {
            $path = resource_path('views/diamond_color/js/DiamondColorForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Diamond Cut
    Route::group(['prefix' => 'diamond-cut'], function () {
        Route::get('/', [DiamondCutController::class, 'index']);
        Route::post('data', [DiamondCutController::class, 'getData'])->name('diamond-cut.data');
        Route::get('create', [DiamondCutController::class, 'create']);
        Route::post('store', [DiamondCutController::class, 'store']);
        Route::get('edit/{id}', [DiamondCutController::class, 'edit']);
        Route::post('update', [DiamondCutController::class, 'update']);
        Route::get('destroy/{id}', [DiamondCutController::class, 'destroy']);
        Route::get('status/{id}', [DiamondCutController::class, 'status']);
        Route::get('/js/DiamondCutForm.js', function () {
            $path = resource_path('views/diamond_cut/js/DiamondCutForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Diamond Clarity
    Route::group(['prefix' => 'diamond-clarity'], function () {
        Route::get('/', [DiamondClarityController::class, 'index']);
        Route::post('data', [DiamondClarityController::class, 'getData'])->name('diamond-clarity.data');
        Route::get('create', [DiamondClarityController::class, 'create']);
        Route::post('store', [DiamondClarityController::class, 'store']);
        Route::get('edit/{id}', [DiamondClarityController::class, 'edit']);
        Route::post('update', [DiamondClarityController::class, 'update']);
        Route::get('destroy/{id}', [DiamondClarityController::class, 'destroy']);
        Route::get('status/{id}', [DiamondClarityController::class, 'status']);
        Route::get('/js/DiamondClarityForm.js', function () {
            $path = resource_path('views/diamond_clarity/js/DiamondClarityForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    Route::group(['prefix' => 'journal-entries'], function () {
        Route::get('/', [JournalEntryController::class, 'index']);
        Route::post('data', [JournalEntryController::class, 'getData'])->name('journal-entry.data');
        Route::get('create', [JournalEntryController::class, 'create']);
        Route::post('store', [JournalEntryController::class, 'store']);
        Route::get('edit/{id}', [JournalEntryController::class, 'edit']);
        Route::get('destroy/{id}', [JournalEntryController::class, 'destroy']);
        Route::get('print/{id}', [JournalEntryController::class, 'print']);
        Route::get('all-jvs', [JournalEntryController::class, 'allJvs']);
        Route::get('grid-edit/{id}', [JournalEntryController::class, 'grid_journal_edit']);
        Route::get('get-sale-order-advance/{sale_order_id}',[JournalEntryController::class,'saleOrderAdvance']);
        Route::get('/js/JournalEntryForm.js', function () {
            $path = resource_path('views/journal_entries/js/JournalEntryForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/editJournalEntry.js', function () {
            $path = resource_path('views/journal_entries/js/editJournalEntry.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('data', [CustomerController::class, 'getData'])->name('customer.data');
        Route::get('create', [CustomerController::class, 'create']);
        Route::post('store', [CustomerController::class, 'store']);
        Route::get('edit/{id}', [CustomerController::class, 'edit']);
        Route::get('detail/{id}', [CustomerController::class, 'detailJson']);
        Route::get('json', [CustomerController::class, 'allJson']);
        Route::post('json-store', [CustomerController::class, 'storeJson']);
        Route::post('update', [CustomerController::class, 'update']);
        Route::get('destroy/{id}', [CustomerController::class, 'destroy']);
        Route::get('status/{id}', [CustomerController::class, 'status']);
    });

    // Customer Payment
    Route::group(['prefix' => 'customer-payment'], function () {
        Route::get('/', [CustomerPaymentController::class, 'index']);
        Route::post('data', [CustomerPaymentController::class, 'getData'])->name('customer-payment.data');
        Route::get('create', [CustomerPaymentController::class, 'create']);
        Route::post('store', [CustomerPaymentController::class, 'store']);
        Route::get('edit/{id}', [CustomerPaymentController::class, 'edit']);
        Route::post('update', [CustomerPaymentController::class, 'update']);
        Route::get('destroy/{id}', [CustomerPaymentController::class, 'destroy']);
        Route::get('status/{id}', [CustomerPaymentController::class, 'status']);
        Route::post('advance', [CustomerPaymentController::class, 'advance']);
        Route::get('/js/CustomerPaymentForm.js', function () {
            $path = resource_path('views/customer_payment/js/CustomerPaymentForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('data', [ProductController::class, 'getData'])->name('product.data');
        Route::get('create', [ProductController::class, 'create']);
        Route::post('store', [ProductController::class, 'store']);
        Route::post('import-csv', [ProductController::class, 'importCsv'])->name('products.import-csv');
        Route::get('import-sample', [ProductController::class, 'downloadCsvSample'])->name('products.import-sample');
        Route::get('edit/{id}', [ProductController::class, 'edit']);
        Route::post('update', [ProductController::class, 'update']);
        Route::get('destroy/{id}', [ProductController::class, 'destroy']);
        Route::get('status/{id}', [ProductController::class, 'status']);
        Route::get('/js/ProductForm.js', function () {
            $path = resource_path('views/products/js/ProductForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    Route::group(['prefix' => 'product-categories'], function () {
        Route::get('/', [ProductCategoryController::class, 'index']);
        Route::post('data', [ProductCategoryController::class, 'getData'])->name('product-categories.data');
        Route::get('create', [ProductCategoryController::class, 'index']);
        Route::post('store', [ProductCategoryController::class, 'store']);
        Route::get('edit/{id}', [ProductCategoryController::class, 'edit']);
        Route::post('update', [ProductCategoryController::class, 'update']);
        Route::get('destroy/{id}', [ProductCategoryController::class, 'destroy']);
        Route::get('status/{id}', [ProductCategoryController::class, 'status']);
        Route::get('/js/ProductCategoryForm.js', function () {
            $path = resource_path('views/product_category/js/ProductCategoryForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // HRM
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('data', [EmployeeController::class, 'getData'])->name('employee.data');
        Route::get('create', [EmployeeController::class, 'create']);
        Route::post('store', [EmployeeController::class, 'store']);
        Route::get('edit/{id}', [EmployeeController::class, 'edit']);
        Route::post('update', [EmployeeController::class, 'update']);
        Route::get('destroy/{id}', [EmployeeController::class, 'destroy']);
        Route::get('status/{id}', [EmployeeController::class, 'status']);
    });

    // HRM
    Route::group(['prefix' => 'ratti-kaats'], function () {
        Route::get('/', [RattiKaatController::class, 'index']);
        Route::post('data', [RattiKaatController::class, 'getData'])->name('ratti-kaat.data');
        Route::get('create', [RattiKaatController::class, 'create']);
        Route::post('store', [RattiKaatController::class, 'store']);
        Route::get('edit/{id}', [RattiKaatController::class, 'edit']);
        Route::get('get-ratti-kaats-detail/{id}', [RattiKaatController::class, 'rattiKaatDetail']);
        Route::post('update', [RattiKaatController::class, 'update']);
        Route::get('destroy/{id}', [RattiKaatController::class, 'destroy']);
        Route::post('post-ratti_kaat', [RattiKaatController::class, 'postRattiKaat']);
        Route::get('unpost-ratti_kaat/{id}', [RattiKaatController::class, 'unpostRattiKaat']);
        Route::get('ratti-kaat-by-product-id/{product_id}', [RattiKaatController::class, 'getRattiKaatByProductId']);
        Route::get('get-detail-by-id/{id}', [RattiKaatController::class, 'getRattiKaatDetailById']);

        Route::get('/js/rattiKaat.js', function () {
            $path = resource_path('views/purchases/ratti_kaat/js/rattiKaat.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });

        // Bead weight
        Route::get('beads/{ratti_kaat_detail_id}', [RattiKaatController::class, 'getBeadWeight']);
        // Route::post('bead-store', [RattiKaatController::class, 'storeBeadWeight']);
        // Route::get('bead-destroy/{id}', [RattiKaatController::class, 'destroyBeadWeight']);

        Route::get('/js/beadWeight.js', function () {
            $path = resource_path('views/purchases/ratti_kaat/js/beadWeight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });

        // Stone weight
        Route::get('stones/{ratti_kaat_detail_id}', [RattiKaatController::class, 'getStoneWeight']);
        // Route::post('stone-store', [RattiKaatController::class, 'storeStoneWeight']);
        // Route::get('stone-destroy/{id}', [RattiKaatController::class, 'destroyStoneWeight']);

        Route::get('/js/stoneWeight.js', function () {
            $path = resource_path('views/purchases/ratti_kaat/js/stoneWeight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });

        // diamond carat
        Route::get('diamonds/{ratti_kaat_detail_id}', [RattiKaatController::class, 'getDiamondCarat']);
        // Route::post('diamond-store', [RattiKaatController::class, 'storeDiamondCarat']);
        // Route::get('diamond-destroy/{id}', [RattiKaatController::class, 'destroyDiamondCarat']);

        Route::get('/js/diamondCarat.js', function () {
            $path = resource_path('views/purchases/ratti_kaat/js/diamondCarat.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });

        // Change kaat
        Route::post('change-kaat', [RattiKaatController::class, 'ChangeKaat']);
    });

    // Supplier Payment
    Route::group(['prefix' => 'supplier-payment'], function () {
        Route::get('/', [SupplierPaymentController::class, 'index']);
        Route::post('data', [SupplierPaymentController::class, 'getData'])->name('supplier-payment.data');
        Route::get('create', [SupplierPaymentController::class, 'create']);
        Route::post('store', [SupplierPaymentController::class, 'store']);
        Route::get('edit/{id}', [SupplierPaymentController::class, 'edit']);
        Route::post('update', [SupplierPaymentController::class, 'update']);
        Route::get('destroy/{id}', [SupplierPaymentController::class, 'destroy']);
        Route::get('status/{id}', [SupplierPaymentController::class, 'status']);
        Route::get('/js/SupplierPaymentForm.js', function () {
            $path = resource_path('views/supplier_payment/js/SupplierPaymentForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Gold Rate
    Route::group(['prefix' => 'gold-rate'], function () {
        Route::get('/', [GoldRateController::class, 'index']);
        Route::get('logs', [GoldRateController::class, 'logs']);
        Route::post('data', [GoldRateController::class, 'getData'])->name('gold-rate.data');
        Route::post('store', [GoldRateController::class, 'store']);
    });

    // Dollar Rate
    Route::group(['prefix' => 'dollar-rate'], function () {
        Route::get('logs', [GoldRateController::class, 'dollarLog']);
        Route::post('data', [GoldRateController::class, 'getDollarData'])->name('dollar-rate.data');
        Route::post('store', [GoldRateController::class, 'storeDollar']);
    });

    // Finish Product
    Route::group(['prefix' => 'finish-product'], function () {
        Route::get('/', [FinishProductController::class, 'index']);
        Route::get('create', [FinishProductController::class, 'create']);
        Route::post('data', [FinishProductController::class, 'getData'])->name('finish-product.data');
        Route::post('store', [FinishProductController::class, 'store']);
        Route::get('view/{id}', [FinishProductController::class, 'show']);
        Route::post('images/{id}', [FinishProductController::class, 'addImages'])->name('finish-product.images.add');
        Route::get('images/{imageId}/delete', [FinishProductController::class, 'deleteImage'])->name('finish-product.images.delete');
        Route::get('get-by-tag-no/{tag}', [FinishProductController::class, 'getByTagNoJson']);
        Route::get('destroy/{id}', [FinishProductController::class, 'destroy']);
        Route::get('status/{id}', [FinishProductController::class, 'status']);
        Route::get('print', [FinishProductController::class, 'print']);


        Route::get('get-bead-by-id/{id}', [FinishProductController::class, 'beadByFinishProductId']);
        Route::get('get-stone-by-id/{id}', [FinishProductController::class, 'stoneByFinishProductId']);
        Route::get('get-diamond-by-id/{id}', [FinishProductController::class, 'diamondByFinishProductId']);

        Route::get('/js/finishProduct.js', function () {
            $path = resource_path('views/finish_product/js/finishProduct.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/beadWeight.js', function () {
            $path = resource_path('views/finish_product/js/beadWeight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/stoneWeight.js', function () {
            $path = resource_path('views/finish_product/js/stoneWeight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/diamondCarat.js', function () {
            $path = resource_path('views/finish_product/js/diamondCarat.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Sale
    Route::group(['prefix' => 'sale'], function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::get('create', [SaleController::class, 'create']);
        Route::post('data', [SaleController::class, 'getData'])->name('sale.data');
        Route::post('store', [SaleController::class, 'store']);
        Route::get('print/{id}', [SaleController::class, 'print']);
        Route::get('destroy/{id}', [SaleController::class, 'destroy']);
        Route::get('status/{id}', [SaleController::class, 'status']);
        Route::get('get-sale-detail/{id}', [SaleController::class, 'saleDetail']);
        Route::post('post-sale', [SaleController::class, 'postSale']);
        Route::get('unpost-sale/{id}', [SaleController::class, 'unpostSale']);
        Route::get('sale-by-product-id/{product_id}', [SaleController::class, 'getSaleByProductId']);
        Route::get('get-sale-detail-by-id/{id}', [SaleController::class, 'getSaleDetailById']);
        Route::post('sale-payment', [SaleController::class, 'salePayment']);

        Route::get('/js/sale.js', function () {
            $path = resource_path('views/sale/js/sale.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/beadWeight.js', function () {
            $path = resource_path('views/sale/js/beadWeight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/stoneWeight.js', function () {
            $path = resource_path('views/sale/js/stoneWeight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/diamondCarat.js', function () {
            $path = resource_path('views/sale/js/diamondCarat.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });


    // Other product
    Route::group(['prefix' => 'other-product'], function () {
        Route::get('/', [OtherProductController::class, 'index']);
        Route::post('data', [OtherProductController::class, 'getData'])->name('other-product.data');
        Route::get('create', [OtherProductController::class, 'create']);
        Route::post('store', [OtherProductController::class, 'store']);
        Route::get('edit/{id}', [OtherProductController::class, 'edit']);
        Route::post('update', [OtherProductController::class, 'update']);
        Route::get('destroy/{id}', [OtherProductController::class, 'destroy']);
        Route::get('status/{id}', [OtherProductController::class, 'status']);
        Route::get('/js/OtherProductForm.js', function () {
            $path = resource_path('views/other_product/js/OtherProductForm.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // stock
    Route::group(['prefix' => 'stock'], function () {
        Route::get('/', [StockController::class, 'index']);
        Route::post('data', [StockController::class, 'getData'])->name('stock.data');
        Route::get('detail', [StockController::class, 'getDetail']);
    });

    // Stock Taking
    Route::group(['prefix' => 'stock-taking'], function () {
        Route::get('/', [StockTakingController::class, 'index']);
        Route::get('create', [StockTakingController::class, 'create']);
        Route::post('data', [StockTakingController::class, 'getData'])->name('stock-taking.data');
        Route::post('store', [StockTakingController::class, 'store']);
        Route::get('view/{id}', [StockTakingController::class, 'view']);
        Route::get('print', [StockTakingController::class, 'print']);
        Route::get('destroy/{id}', [StockTakingController::class, 'destroy']);

        Route::get('/js/stock_taking.js', function () {
            $path = resource_path('views/stock_taking/js/stock_taking.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // transaction
    Route::group(['prefix' => 'transaction'], function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('data', [TransactionController::class, 'getData'])->name('transaction.data');
        Route::get('destroy/{id}', [TransactionController::class, 'destroy']);
    });

    // Other Sale
    Route::group(['prefix' => 'other-sale'], function () {
        Route::get('/', [OtherSaleController::class, 'index']);
        Route::get('create', [OtherSaleController::class, 'create']);
        Route::post('data', [OtherSaleController::class, 'getData'])->name('other-sale.data');
        Route::post('store', [OtherSaleController::class, 'store']);
        Route::get('print/{id}', [OtherSaleController::class, 'print']);
        Route::get('destroy/{id}', [OtherSaleController::class, 'destroy']);
        Route::get('status/{id}', [OtherSaleController::class, 'status']);
        Route::post('post', [OtherSaleController::class, 'post']);
        Route::get('unpost/{id}', [OtherSaleController::class, 'unpost']);

        Route::get('/js/other_sale.js', function () {
            $path = resource_path('views/other_sale/js/other_sale.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Gold Impurity
    Route::group(['prefix' => 'gold-impurity'], function () {
        Route::get('/', [GoldImpurityPurchaseController::class, 'index']);
        Route::get('create', [GoldImpurityPurchaseController::class, 'create']);
        Route::post('data', [GoldImpurityPurchaseController::class, 'getData'])->name('gold-impurity.data');
        Route::post('store', [GoldImpurityPurchaseController::class, 'store']);
        Route::get('print/{id}', [GoldImpurityPurchaseController::class, 'print']);
        Route::get('destroy/{id}', [GoldImpurityPurchaseController::class, 'destroy']);
        Route::get('status/{id}', [GoldImpurityPurchaseController::class, 'status']);
        Route::post('post', [GoldImpurityPurchaseController::class, 'post']);
        Route::get('unpost/{id}', [GoldImpurityPurchaseController::class, 'unpost']);

        Route::get('/js/gold_impurity.js', function () {
            $path = resource_path('views/purchases/gold_impurity/js/gold_impurity.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Other Purchase
    Route::group(['prefix' => 'other-purchase'], function () {
        Route::get('/', [OtherPurchaseController::class, 'index']);
        Route::get('create', [OtherPurchaseController::class, 'create']);
        Route::post('data', [OtherPurchaseController::class, 'getData'])->name('other-purchase.data');
        Route::post('store', [OtherPurchaseController::class, 'store']);
        Route::get('print/{id}', [OtherPurchaseController::class, 'print']);
        Route::get('destroy/{id}', [OtherPurchaseController::class, 'destroy']);
        Route::get('status/{id}', [OtherPurchaseController::class, 'status']);
        Route::post('post', [OtherPurchaseController::class, 'post']);
        Route::get('unpost/{id}', [OtherPurchaseController::class, 'unpost']);

        Route::get('/js/other_purchase.js', function () {
            $path = resource_path('views/purchases/other_purchase/js/other_purchase.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Other Purchase
    Route::group(['prefix' => 'company-setting'], function () {
        Route::get('/', [CompanySettingController::class, 'index']);
        Route::post('store', [CompanySettingController::class, 'store']);
    });

    // Sale Order
    Route::group(['prefix' => 'sale-order'], function () {
        Route::get('/', [SaleOrderController::class, 'index']);
        Route::get('create', [SaleOrderController::class, 'create']);
        Route::post('data', [SaleOrderController::class, 'getData'])->name('sale-order.data');
        Route::post('store', [SaleOrderController::class, 'store']);
        Route::get('print/{id}', [SaleOrderController::class, 'print']);
        Route::get('destroy/{id}', [SaleOrderController::class, 'destroy']);
        Route::get('by-warehouse/{warehouse_id}', [SaleOrderController::class, 'byWarehouse']);
        Route::get('by-customer/{customer_id}', [SaleOrderController::class, 'byCustomer']);
        Route::get('get-detail/{id}', [SaleOrderController::class, 'getDetail']);

        Route::get('/js/sale_order.js', function () {
            $path = resource_path('views/sale_order/js/sale_order.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Purchase Order
    Route::group(['prefix' => 'purchase-order'], function () {
        Route::get('/', [PurchaseOrderController::class, 'index']);
        Route::get('create', [PurchaseOrderController::class, 'create']);
        Route::post('data', [PurchaseOrderController::class, 'getData'])->name('purchase-order.data');
        Route::post('store', [PurchaseOrderController::class, 'store']);
        Route::get('print/{id}', [PurchaseOrderController::class, 'print']);
        Route::get('destroy/{id}', [PurchaseOrderController::class, 'destroy']);
        Route::get('approve/{id}', [PurchaseOrderController::class, 'approve']);
        Route::get('reject/{id}', [PurchaseOrderController::class, 'reject']);

        Route::get('/js/purchase_order.js', function () {
            $path = resource_path('views/purchase_order/js/purchase_order.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Job Task
    Route::group(['prefix' => 'job-task'], function () {
        Route::get('/', [JobTaskController::class, 'index']);
        Route::post('data', [JobTaskController::class, 'getData'])->name('job-task.data');
        Route::post('store', [JobTaskController::class, 'store']);
        Route::get('print/{id}', [JobTaskController::class, 'print']);
        Route::get('destroy/{id}', [JobTaskController::class, 'destroy']);

        Route::get('/js/job_task.js', function () {
            $path = resource_path('views/job_task/js/job_task.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Job Task
    Route::group(['prefix' => 'job-task-activity'], function () {
        Route::get('/{id}', [JobTaskActivityController::class, 'index']);
        Route::post('data', [JobTaskActivityController::class, 'getData'])->name('job-task-activity.data');
        Route::post('store', [JobTaskActivityController::class, 'store']);
        Route::get('destroy/{id}', [JobTaskActivityController::class, 'destroy']);

        Route::get('/js/job_task_activity.js', function () {
            $path = resource_path('views/job_task_activity/js/job_task_activity.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });


    // Sale
    Route::group(['prefix' => 'job-purchase'], function () {
        Route::get('/', [JobPurchaseController::class, 'index']);
        Route::get('create/{id}', [JobPurchaseController::class, 'create']);
        Route::post('data', [JobPurchaseController::class, 'getData'])->name('job-purchase.data');
        Route::post('store', [JobPurchaseController::class, 'store']);
        Route::get('print/{id}', [JobPurchaseController::class, 'print']);
        Route::get('destroy/{id}', [JobPurchaseController::class, 'destroy']);
        Route::get('status/{id}', [JobPurchaseController::class, 'status']);
        Route::get('get-detail/{id}', [JobPurchaseController::class, 'jobPurchaseDetail']);
        Route::post('post', [JobPurchaseController::class, 'post']);
        Route::get('unpost/{id}', [JobPurchaseController::class, 'unpost']);
        Route::get('beads/{detail_id}/{product_id}', [JobPurchaseController::class, 'jobPurchaseBeadDetail']);
        Route::get('stones/{detail_id}/{product_id}', [JobPurchaseController::class, 'jobPurchaseStoneDetail']);
        Route::get('diamonds/{detail_id}/{product_id}', [JobPurchaseController::class, 'jobPurchaseDiamondDetail']);

        Route::get('/js/job_purchase.js', function () {
            $path = resource_path('views/purchases/job_purchase/js/job_purchase.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/job_purchase_detail.js', function () {
            $path = resource_path('views/purchases/job_purchase/js/job_purchase_detail.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/bead_weight.js', function () {
            $path = resource_path('views/purchases/job_purchase/js/bead_weight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/stone_weight.js', function () {
            $path = resource_path('views/purchases/job_purchase/js/stone_weight.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
        Route::get('/js/diamond_carat.js', function () {
            $path = resource_path('views/purchases/job_purchase/js/diamond_carat.js');
            if (file_exists($path)) {
                return Response::file($path, [
                    'Content-Type' => 'application/javascript',
                ]);
            }
            abort(404);
        });
    });

    // Reports
    Route::group(['prefix' => 'reports'], function () {

        // Ledger Report
        Route::get('ledger-report', [ReportController::class, 'ledgerReport']);
        Route::get('get-preview-ledger-report', [ReportController::class, 'getPreviewLedgerReport']);
        Route::get('get-ledger-report', [ReportController::class, 'getLedgerReport']);

        // Tag History Report
        Route::get('tag-history-report', [ReportController::class, 'tagHistoryReport']);
        Route::get('get-preview-tag-history-report', [ReportController::class, 'getPreviewTagHistoryReport']);
        Route::get('get-tag-history-report', [ReportController::class, 'getTagHistoryReport']);

        // Profit Loss Report
        Route::get('profit-loss-report', [ReportController::class, 'profitLossReport']);
        Route::get('get-preview-profit-loss-report', [ReportController::class, 'getPreviewProfitLossReport']);
        Route::get('get-profit-loss-report', [ReportController::class, 'getProfitLossReport']);

        // Stock Ledger Report
        Route::get('stock-ledger-report', [ReportController::class, 'stockLedger']);
        Route::get('get-preview-stock-ledger-report', [ReportController::class, 'getPreviewStockLedgerReport']);
        Route::get('get-stock-ledger-report', [ReportController::class, 'getStockLedgerReport']);

        // Product Ledger Report
        Route::get('product-ledger-report', [ReportController::class, 'productLedger']);
        Route::get('get-preview-product-ledger-report', [ReportController::class, 'getPreviewProductLedgerReport']);
        Route::get('get-product-ledger-report', [ReportController::class, 'getProductLedgerReport']);

        // Customer List Report
        Route::get('customer-list-report', [ReportController::class, 'customerList']);
        Route::get('get-preview-customer-list-report', [ReportController::class, 'getPreviewCustomerListReport']);
        Route::get('get-customer-list-report', [ReportController::class, 'getCustomerListReport']);

        // Product Consumption Report
        Route::get('product-consumption-report', [ReportController::class, 'productConsumption']);
        Route::get('get-preview-product-consumption-report', [ReportController::class, 'getPreviewProductConsumptionReport']);
        Route::get('get-product-consumption-report', [ReportController::class, 'getProductConsumptionReport']);

        // Financial Report
        Route::get('financial-report', [ReportController::class, 'financialReport']);
        Route::get('get-preview-financial-report', [ReportController::class, 'getPreviewFinancialReport']);
        Route::get('get-financial-report', [ReportController::class, 'getFinancialReport']);
    });
});
