<?php
namespace App\Providers;
use App\Models\Blog;
use App\Models\CategoryBlog;
use App\Models\CategoryHelp;
use App\Models\CategoryProduct;
use App\Models\ClientSays;
use App\Models\Settings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
use View;
use Response;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Kiểm tra môi trường
        if (env('APP_ENV') !== 'dev') {
            // Trong môi trường production hoặc không phải dev
            \URL::forceScheme('https');
        }
        
        // Giữ nguyên kiểm tra HTTP_X_FORWARDED_PROTO cho proxy servers
        if (request()->server('HTTP_X_FORWARDED_PROTO') == 'https') {
            \URL::forceScheme('https');
        }

        // Thêm cài đặt khác nếu cần
        if (env('APP_ENV') === 'dev') {
            // Cài đặt đặc biệt cho môi trường dev
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}