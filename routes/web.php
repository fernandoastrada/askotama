<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Public Routes (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home.index');
//Route::get('/home', [HomeController::class, 'index'])->name('home.index');

    // Blog Publik
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Sitemap (Tambahkan di sini)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');

Route::get('/hubungi-kami', [ContactController::class, 'index'])->name('contact.index');
Route::post('/hubungi-kami', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');

//Route::get('/aboutUs', [AboutUsController::class, 'index'])->name('aboutUs.index');
Route::get('/about-us', [AboutUsController::class, 'index'])->name('aboutUs.index');
// Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_cart'])->name('cart.item.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
Route::get('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation');

Route::get('/klien-kami', [HomeController::class, 'clients'])->name('clients.index');



/*
|--------------------------------------------------------------------------
| User Routes (hanya bisa diakses setelah login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/account-address', [UserController::class, 'address'])->name('user.address');
    Route::get('/user/address/add', [UserController::class, 'add_address'])->name('user.address.add');
    Route::post('/user/address/proses', [UserController::class, 'address_proses'])->name('user.address.proses');

    
});

/*
|--------------------------------------------------------------------------
| Admin Routes (hanya login + role ADM)
|--------------------------------------------------------------------------
|
| Gunakan middleware 'admin' (alias dari AuthAdmin di Kernel.php).
| Jadi hanya user dengan utype === 'ADM' yang bisa akses.
|
*/
Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    //list customer
    Route::get('/clients', [AdminController::class, 'clients'])->name('admin.clients');
    Route::get('/client/add', [AdminController::class, 'add_client'])->name('admin.clients.add');
    Route::post('/client/store', [AdminController::class, 'client_store'])->name('admin.client.store');
    Route::delete('/client/delete/{id}', [AdminController::class, 'client_delete'])->name('admin.client.delete');



    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////BLOG//////////////////////////////////////////////////////
// Management Blog (Hanya Admin)
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog/store', [BlogController::class, 'store'])->name('blog.store');
    // Route Baru: Edit & Delete
    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
    //////////////////////////////////////////////////////////////////////////////////////

    // Brands
    Route::get('/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/brand/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
    Route::post('/brand/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/brand/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('/brand/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/category/add', [AdminController::class, 'category_add'])->name('admin.category.add');
    Route::post('/category/store', [AdminController::class, 'category_store'])->name('admin.category.store');

    // Products
    // List semua produk
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');

    //Form tambah produk
    Route::get('/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');

    //Simpan produk baru 
    Route::post('/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');

    //Form Edit produk
    Route::get('/product/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');

    //Update produk
    Route::put('/product/update/{id}', [AdminController::class, 'product_update'])->name('admin.product.update');

    // Detail Produk
    Route::get('/products/{id}/show', [AdminController::class, 'show'])->name('admin.product.show');

    //Hapus Produk
    Route::delete('/product/delete/{id}', [AdminController::class, 'product_delete'])->name('admin.product.delete');
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');

    // Slides
    Route::get('/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/slide/add', [AdminController::class, 'slide_add'])->name('admin.slide.add');
    Route::post('/slide/store', [AdminController::class, 'slide_store'])->name('admin.slide.store');
    Route::get('/slide/edit/{id}', [AdminController::class, 'slide_edit'])->name('admin.slide.edit');
    Route::put('/slide/update', [AdminController::class, 'slide_update'])->name('admin.slide.update');
    Route::delete('/slide/delete/{id}', [AdminController::class, 'slide_delete'])->name('admin.slide.delete');

    // Promotions
    Route::get('/promotions', [AdminController::class, 'promotions'])->name('admin.promotions');
    Route::get('/promotion/add', [AdminController::class, 'promotion_add'])->name('admin.promotion.add');
    Route::post('/promotion/store', [AdminController::class, 'promotion_store'])->name('admin.promotion.store');
    Route::get('/promotion/edit/{id}', [AdminController::class, 'promotion_edit'])->name('admin.promotion.edit');
    Route::put('/promotion/update', [AdminController::class, 'promotion_update'])->name('admin.promotion.update');
    Route::delete('/promotion/delete/{id}', [AdminController::class, 'promotion_delete'])->name('admin.promotion.delete');
});

Route::get('/optimize-server', function() {
    // Membersihkan semua cache (config, view, cache, route)
    Artisan::call('optimize:clear');
    
    // Opsional: Membuat symlink storage jika belum ada
    // Artisan::call('storage:link');

    return "Server Berhasil Dioptimasi! Cache telah dihapus.";
});

Route::get('/run-migration-v2', function () {
    try {
        // Output akan ditampung di variabel
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        
        return "<pre>Laporan Migrasi:\n" . ($output ?: "Tidak ada file migrasi baru yang dijalankan (Semua sudah dianggap selesai).") . "</pre>";
    } catch (\Exception $e) {
        return "Terjadi Kesalahan: " . $e->getMessage();
    }
});

Route::get('/fix-database', function() {
    // 1. Clear Config Cache agar membaca .env terbaru
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    
    // 2. Jalankan Migrasi ulang ke MySQL
    Artisan::call('migrate', ['--force' => true]);
    
    return "Cache dibersihkan dan Migrasi dijalankan ulang ke MySQL!";
});

Route::get('/cek-koneksi', function() {
    try {
        $dbName = DB::connection()->getDatabaseName();
        $driver = DB::connection()->getDriverName();
        $user = config('database.connections.mysql.username');
        
        return "Driver: $driver | DB Name: $dbName | User: $user";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/migrasi-total', function () {
    try {
        // Membersihkan cache config terlebih dahulu secara internal
        Artisan::call('config:clear');
        
        // Menjalankan migrasi fresh (menghapus sisa-sisa tabel dan buat baru)
        Artisan::call('migrate:fresh', ['--force' => true]);
        
        $output = Artisan::output();
        return "<pre>Hasil Migrasi Paksa:\n" . $output . "</pre>";
    } catch (\Exception $e) {
        return "Gagal Migrasi: " . $e->getMessage();
    }
});

Route::get('/migrasi-final', function () {
    try {
        // 1. Bersihkan Cache Konfigurasi agar membaca .env terbaru
        Artisan::call('config:clear');
        
        // 2. Jalankan migrasi biasa (karena database sudah dikosongkan di Langkah 1)
        Artisan::call('migrate', ['--force' => true]);
        
        $output = Artisan::output();
        return "<h1>Migrasi Berhasil!</h1><pre>" . ($output ?: "Semua tabel sudah terbuat.") . "</pre>";
        
    } catch (\Exception $e) {
        return "<h1>Gagal Migrasi:</h1><p>" . $e->getMessage() . "</p>";
    }
});

Route::get('/reset-database-final', function () {
    try {
        // 1. Paksa hapus cache konfigurasi
        Artisan::call('config:clear');
        
        // 2. Jalankan migrasi dari awal
        // Kita gunakan 'migrate' saja karena tabel sudah kita DROP manual di phpMyAdmin
        Artisan::call('migrate', ['--force' => true]);
        
        $output = Artisan::output();
        return "<h1>BERHASIL!</h1><pre>Log Migrasi:\n" . $output . "</pre>";
        
    } catch (\Exception $e) {
        return "<h1>MASIH GAGAL:</h1><p>" . $e->getMessage() . "</p>";
    }
});

Route::get('/migrasi-ulang-total', function () {
    try {
        // Membersihkan cache agar Laravel mendeteksi file yang tadi di-rename
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        
        // Jalankan migrasi
        Artisan::call('migrate', ['--force' => true]);
        
        $output = Artisan::output();
        return "<h1>MIGRASI SUKSES!</h1><pre>$output</pre>";
    } catch (\Exception $e) {
        return "<h1>MASIH ERROR:</h1><pre>" . $e->getMessage() . "</pre>";
    }
});