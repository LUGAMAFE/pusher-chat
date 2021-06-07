<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
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
    public function boot()
    {
        Fortify::ignoreRoutes();

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            if($request->exists("g-recaptcha-response")){
                return Limit::none();
            }
            return Limit::perMinute(5)->by($request->email.$request->ip())->response(function ($request) {
                $errors = new MessageBag();
                // add your error messages:
                $errors->add('recaptcha', 'Need Captcha, too many attempts!');

                $errors_array = [
                    "errors" => $errors->getMessages()
                ];

                if($request->ajax()){
                    return new Response($errors_array, 429);
                }

                return redirect()->to(route("login"))->withInput($request->input())->withErrors($errors);
            });
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
