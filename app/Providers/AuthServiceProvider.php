<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function(object $notifiable, string $url){
            return (new MailMessage)
            ->subject('Phenikaa Clinic | Vui lòng Xác thực Địa chỉ Email của Bạn')
            ->markdown('emails.verify', ['url' => $url]);
        });

        ResetPassword::toMailUsing(function(object $notifiable, string $token){
            return (new MailMessage)
            ->subject('Phenikaa Clinic | Yêu cầu Đặt lại Mật khẩu Tài khoản của Bạn')
            ->markdown('emails.recovery', ['url' => url(config('app.url').route('password.reset', $token, false))]);
        });
        
    }
}
