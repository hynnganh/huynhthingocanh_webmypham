<?php

namespace App\Providers;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
{
    // Kiểm tra nếu ứng dụng đang chạy trên môi trường production
    if ($this->app->environment('production') || config('app.env') === 'production') {
        // Khai báo proxy tin cậy để Laravel nhận biết HTTPS
        // Hoặc bạn có thể dùng Request::HEADER_X_FORWARDED_AWS_ELB nếu dùng AWS,
        // nhưng Request::HEADER_X_FORWARDED_FOR là đủ trong nhiều trường hợp.
        Request::setTrustedProxies(
            ['*'], // Chấp nhận tất cả proxy headers, an toàn nếu bạn chỉ dùng Render
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );

        // Bắt buộc sử dụng HTTPS cho tất cả các URL được tạo ra
        URL::forceScheme('https');
    }
}
}
